<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera;

use ninjaknights\CameraUtils\APIRegistry;
use ninjaknights\CameraUtils\CameraPlayer;
use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CameraShakePacket;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEaseType;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\TaskHandler;
use ninjaknights\CameraUtils\camera\types\{
	ClearCamera,
	ShakeStartCamera,
	ShakeStopCamera,
	ZoomInCamera,
	FovCamera,
	DefaultCamera,
	FadeCamera,
	ZoomOutCamera,
	TargetCamera
};

/**
 * CameraAPI class for managing camera actions and timings for a player.
 * 
 * @method Player getPlayer()
 * @method CameraAPI startTimings(callable $callback, int|float $durationSeconds = 20, float $intervalSeconds = 1.0, ?callable $onSkip = null): self
 * @method CameraAPI stopTimings(): self
 * @method void scheduleAll()
 * @method void play()
 * @method void stop(bool $resetQueue = true)
 * @method bool isRunning()
 * @method CameraAPI queue(callable $callback, float $delay = 0.0): self
 * @method CameraAPI wait(float $seconds): self
 * @method CameraAPI clearQueue(): self
 * @method void restart()
 * @method bool isPaused()
 * @method void pause()
 * @method void resume()
 * @method CameraAPI speed(float $multiplier): self
 * @method CameraAPI onFinish(?callable $callback): self
 * @method CameraAPI onSkip(?callable $callback): self
 * @method CameraAPI setLoop(bool $loop): self
 * @method CameraAPI shakeStart(float $intensity = 0.5, float $duration = 1.0, int $type = CameraShakePacket::TYPE_POSITIONAL): self
 * @method CameraAPI shakeStop(int $type = CameraShakePacket::TYPE_POSITIONAL): self
 * @method CameraAPI zoomIn(float $fov = 1.6, float $time = 0.0, int $type = CameraSetInstructionEaseType::LINEAR): self
 * @method CameraAPI zoomOut(): self
 * @method CameraAPI fov(float $fov = 1.0, float $duration = 0.0, int $type = CameraSetInstructionEaseType::LINEAR): self
 * @method CameraAPI fade(float $fadeIn = 1.0, float $stay = 0.5, float $fadeOut = 1.0, Color|DyeColor|null $color = null): self
 * @method CameraAPI targetPlayer(?Player $player = null, ?Vector3 $offset = null): self
 * @method CameraAPI attachToEntity(Player|Entity|Living|null $value = null): self
 * @method CameraAPI set(?string $preset = null, int $easeType = CameraSetInstructionEaseType::LINEAR, float $easeTime = 1.0, ?Vector3 $position = null, float $pitch = 0.0, float $yaw = 0.0, ?Vector3 $facingPosition = null, ?Vector2 $viewOffset = null, ?Vector3 $entityOffset = null): self
 * @method CameraAPI clear(bool $resetFov = false): self
 * @method void saveWaypoints(string $fileName)
 * @method void loadWaypoints(string $fileName)
 * @method CameraWaypoint createWaypoint(int $i = 0, float $delay = 0.0, ?Vector3 $position = null, float $yaw = 0.0, float $pitch = 0.0, float $duration = 1.0, int $easeType = CameraSetInstructionEaseType::LINEAR, string $preset = "minecraft:free"): CameraWaypoint
 * @method CameraAPI addWaypoint(CameraWaypoint $waypoint): self
 * @method CameraAPI setWaypoints(array $waypoints): self
 * @method array getWaypoints()
 * @method CameraAPI clearWaypoints(): self
 * @method CameraAPI playWaypoint(CameraWaypoint $wp, float $delay = 0.0): self
 * @method CameraAPI playWaypoints(bool $clearQueue = true, bool $relative = true): self
 */
final class CameraAPI {

	/** @var Player */
	public Player $player;
	/** @var array */
	public array $actions = [];
	/** @var array */
	public array $scheduled = [];
	/** @var CameraWaypoint[] */
	public array $waypoints = [];
	/** @var TaskHandler|null */
	public TaskHandler|null $timingTask = null;

	/** @var float */
	private float $timeOffset = 0.0;
	/** @var float */
	private float $playbackSpeed = 1.0;
	/** @var bool */
	private bool $running = false;
	/** @var bool */
	private bool $paused = false;
	/** @var bool */
	private bool $loop = false;
	/** @var callable|null */
	public $onFinish = null;
	/** @var callable|null */
	public $onSkip = null;

	/**
	 * Constructor for CameraAPI.
	 *
	 * @param Player $player
	 */
	public function __construct(Player $player){
		$this->player = $player;
	}

	/**
	 * Retrieves the player associated with this CameraAPI.
	 *
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->player;
	}

	/**
	 * Starts timing callbacks at specified intervals for a duration.
	 *
	 * @param callable $callback
	 * @param int|float $durationSeconds
	 * @param float $intervalSeconds
	 * @param callable|null $onSkip
	 * @return self
	 */
	public function startTimings(
		callable $callback,
		int|float $durationSeconds = 20,
		float $intervalSeconds = 1.0,
		?callable $onSkip = null
	): self {
		$this->stopTimings();
		$elapsed = 0;
		$totalTicks = (int) round($durationSeconds * 20);
		$intervalTicks = (int) round($intervalSeconds * 20);
		$this->timingTask = APIRegistry::getPlugin()->getScheduler()->scheduleRepeatingTask(
			new ClosureTask(function() use (&$elapsed, $callback, $totalTicks, $onSkip): void {
				if(!$this->player->isConnected()){
					$this->stopTimings();
					return;
				}
				if($this->player->isSneaking() && $onSkip){
					$onSkip($this, $this->player);
					$this->stopTimings();
					return;
				}
				$callback($this, (int) ($elapsed / 20), $this->player);
				$elapsed += 20;
				if($elapsed >= $totalTicks){
					$this->stopTimings();
				}
			}),
			$intervalTicks
		);
		return $this;
	}

	/**
	 * Stops the timing callbacks.
	 *
	 * @return self
	 */
	public function stopTimings(): self {
		if($this->timingTask !== null && !$this->timingTask->isCancelled()){
			$this->timingTask->cancel();
		}
		$this->timingTask = null;
		return $this;
	}

	/**
	 * Schedules all queued camera actions.
	 *
	 * @return void
	 */
	public function scheduleAll(): void {
		foreach($this->actions as $entry){
			$delayTicks = (int) round(($entry['delay'] / $this->playbackSpeed) * 20);
			$handler = APIRegistry::getPlugin()->getScheduler()->scheduleDelayedTask(
				new ClosureTask(function() use ($entry): void {
					if(!$this->player->isConnected() || $this->paused){
						return;
					}
					($entry['callback'])($this, $this->player);
				}),
				$delayTicks
			);
			$this->scheduled[] = $handler;
		}
	}

	/**
	 * Starts playing the scheduled camera actions.
	 *
	 * @return void
	 */
	public function play(): void {
		if($this->running) return;
		$this->running = true;
		$this->paused = false;
		$this->scheduleAll();
		$delays = array_column($this->actions, 'delay');
		$totalDuration = empty($delays) ? 0.0 : max($delays);
		$finalDelay = (int) round(($totalDuration + 0.2) * 20);
		$skipTask = null;
		$skipTask = APIRegistry::getPlugin()->getScheduler()->scheduleRepeatingTask(
			new ClosureTask(function() use (&$skipTask): void {
				if(!$this->player->isConnected()){
					$this->stop();
					$skipTask?->cancel();
					return;
				}
				if($this->player->isSneaking()){
					if($this->onSkip){
						($this->onSkip)($this, $this->player);
					}
					$this->stop();
					$skipTask?->cancel();
				}
			}),
			5
		);
		APIRegistry::getPlugin()->getScheduler()->scheduleDelayedTask(
			new ClosureTask(function() use ($skipTask): void {
				$skipTask->cancel();
				if($this->onFinish){
					($this->onFinish)($this, $this->player);
				}
				if($this->loop && $this->player->isConnected()){
					APIRegistry::getPlugin()->getScheduler()->scheduleDelayedTask(
						new ClosureTask(fn() => $this->restart()),
						5
					);
				}else{
					$this->stop();
				}
			}),
			$finalDelay
		);
	}

	/**
	 * Stops the camera actions.
	 *
	 * @param bool $resetQueue
	 * @return void
	 */
	public function stop(bool $resetQueue = true): void {
		foreach($this->scheduled as $handler){
			if(!$handler->isCancelled()){
				$handler->cancel();
			}
		}
		$this->scheduled = [];
		$this->stopTimings();
		if($resetQueue){
			$this->clearQueue();
			$this->waypoints = [];
		}
		$this->running = false;
		$this->paused = false;
	}

	/**
	 * Checks if the camera is currently running.
	 *
	 * @return bool
	 */
	public function isRunning(): bool {
		return $this->running;
	}

	/**
	 * Queues a camera action to be executed after a delay.
	 *
	 * @param callable $callback
	 * @param float $delay
	 * @return self
	 */
	public function queue(callable $callback, float $delay = 0.0): self {
		$this->actions[] = [
			'callback' => $callback,
			'delay' => $this->timeOffset + $delay
		];
		return $this;
	}

	/**
	 * Adds a wait time before the next queued action.
	 *
	 * @param float $seconds
	 * @return self
	 */
	public function wait(float $seconds): self {
		$this->timeOffset += max(0.0, $seconds);
		return $this;
	}

	/**
	 * Clears all queued camera actions.
	 *
	 * @return self
	 */
	public function clearQueue(): self {
		$this->actions = [];
		$this->timeOffset = 0.0;
		return $this;
	}

	/**
	 * Restarts the camera actions from the beginning.
	 *
	 * @return void
	 */
	public function restart(): void {
		$this->stop(false);
		if(empty($this->actions) && !empty($this->waypoints)){
			$this->playWaypoints(true, false);
		}
		$this->play();
	}

	/**
	 * Checks if the camera is currently paused.
	 *
	 * @return bool
	 */
	public function isPaused(): bool {
		return $this->paused;
	}

	/**
	 * Pauses the camera actions.
	 *
	 * @return void
	 */
	public function pause(): void {
		$this->paused = true;
	}

	/**
	 * Resumes the camera actions.
	 *
	 * @return void
	 */
	public function resume(): void {
		$this->paused = false;
	}

	/**
	 * Sets the playback speed multiplier.
	 *
	 * @param float $multiplier
	 * @return self
	 */
	public function speed(float $multiplier): self {
		$this->playbackSpeed = max(0.1, $multiplier);
		return $this;
	}

	/**
	 * Sets the callback to be executed when the camera actions finish.
	 *
	 * @param callable|null $callback
	 * @return self
	 */
	public function onFinish(?callable $callback): self {
		$this->onFinish = $callback;
		return $this;
	}

	/**
	 * Sets the callback to be executed when the camera actions are skipped.
	 *
	 * @param callable|null $callback
	 * @return self
	 */
	public function onSkip(?callable $callback): self {
		$this->onSkip = $callback;
		return $this;
	}

	/**
	 * Sets whether the camera actions should loop.
	 *
	 * @param bool $loop
	 * @return self
	 */
	public function setLoop(bool $loop): self {
		$this->loop = $loop;
		return $this;
	}

	/**
	 * Starts a camera shake effect.
	 *
	 * @param float $intensity
	 * @param float $duration
	 * @param int $type
	 * @return self
	 */
	public function shakeStart(float $intensity = 0.5, float $duration = 1.0, int $type = CameraShakePacket::TYPE_POSITIONAL): self {
		return $this->queue(fn() => (new ShakeStartCamera(CameraPlayer::get($this->player)))->intensity($intensity)->duration($duration)->type($type)->create());
	}

	/**
	 * Stops a camera shake effect.
	 *
	 * @param int $type
	 * @return self
	 */
	public function shakeStop(int $type = CameraShakePacket::TYPE_POSITIONAL): self {
		return $this->queue(fn() => (new ShakeStopCamera(CameraPlayer::get($this->player)))->type($type)->create());
	}

	/**
	 * Starts a zoom-in effect.
	 *
	 * @param float $fov
	 * @param float $time
	 * @param int $type
	 * @return self
	 */
	public function zoomIn(float $fov = 1.6, float $time = 0.0, int $type = CameraSetInstructionEaseType::LINEAR): self {
		return $this->queue(fn() => (new ZoomInCamera(CameraPlayer::get($this->player)))->fov($fov)->easeTime($time)->easeType($type)->create());
	}

	/**
	 * Starts a zoom-out effect.
	 *
	 * @return self
	 */
	public function zoomOut(): self {
		return $this->queue(fn() => (new ZoomOutCamera(CameraPlayer::get($this->player)))->create());
	}

	/**
	 * Sets the field of view.
	 *
	 * @param float $fov
	 * @param float $duration
	 * @param int $type
	 * @return self
	 */
	public function fov(float $fov = 1.0, float $duration = 0.0, int $type = CameraSetInstructionEaseType::LINEAR): self {
		return $this->queue(fn() => (new FovCamera(CameraPlayer::get($this->player)))->fov($fov)->duration($duration)->easeType($type)->create());
	}

	/**
	 * Starts a fade effect.
	 *
	 * @param float $fadeIn
	 * @param float $stay
	 * @param float $fadeOut
	 * @param Color|DyeColor|null $color
	 * @return self
	 */
	public function fade(float $fadeIn = 1.0, float $stay = 0.5, float $fadeOut = 1.0, Color|DyeColor|null $color = null): self {
		return $this->queue(fn() => (new FadeCamera(CameraPlayer::get($this->player)))->fadeIn($fadeIn)->stay($stay)->fadeOut($fadeOut)->color($color)->create());
	}

	/**
	 * Targets a specific player.
	 *
	 * @param Player|null $player
	 * @return self
	 */
	public function targetPlayer(?Player $player = null, ?Vector3 $offset = null): self {
		return $this->queue(fn() => (new TargetCamera(CameraPlayer::get($this->player)))->setTargetPlayer($player, $offset)->create());
	}

	/**
	 * Attaches the camera to an entity.
	 *
	 * @param Player|Entity|Living|null $value
	 * @return self
	 */
	public function attachToEntity(Player|Entity|Living|null $value = null): self {
		return $this->queue(fn() => (new TargetCamera(CameraPlayer::get($this->player)))->attachToEntity($value)->create());
	}

	/**
	 * Sets camera parameters.
	 *
	 * @param string|null $preset Default: `minecraft:free`
	 * @param int $easeType
	 * @param float $easeTime
	 * @param Vector3|null $position
	 * @param float $pitch
	 * @param float $yaw
	 * @param Vector3|null $facingPosition
	 * @param Vector2|null $viewOffset
	 * @param Vector3|null $entityOffset
	 * @return self
	 */
	public function set(
		?string $preset = null,
		int $easeType = CameraSetInstructionEaseType::LINEAR,
		float $easeTime = 1.0,
		?Vector3 $position = null,
		float $pitch = 0.0,
		float $yaw = 0.0,
		?Vector3 $facingPosition = null,
		?Vector2 $viewOffset = null,
		?Vector3 $entityOffset = null,
	): self {
		return $this->queue(fn() => (new DefaultCamera(CameraPlayer::get($this->player)))->setPreset($preset)->setEase($easeType, $easeTime)->setPosition($position)->setRotation($pitch, $yaw)->setFacing($facingPosition)->setViewOffset($viewOffset)->setEntityOffset($entityOffset)->create());
	}

	/**
	 * Clears the camera effects.
	 *
	 * @param bool $resetFov
	 * @return self
	 */
	public function clear(bool $resetFov = false): self {
		return $this->queue(fn() => (new ClearCamera(CameraPlayer::get($this->player)))->resetFov($resetFov)->create());
	}

	/**
	 * Saves the waypoints to a JSON file.
	 *
	 * @param string $fileName
	 * @return void
	 */
	public function saveWaypoints(string $fileName): void {
		$path = APIRegistry::getPlugin()->getDataFolder() . "cameras/";
		@mkdir($path);
		$data = array_map(fn(CameraWaypoint $wp) => $wp->toArray(), $this->waypoints);
		file_put_contents($path . $fileName . ".json", json_encode($data, JSON_PRETTY_PRINT));
	}

	/**
	 * Loads waypoints from a JSON file.
	 *
	 * @param string $fileName
	 * @return void
	 */
	public function loadWaypoints(string $fileName): void {
		$path = APIRegistry::getPlugin()->getDataFolder() . "cameras/" . $fileName . ".json";
		if(!file_exists($path)) return;
		$data = json_decode(file_get_contents($path), true);
		$this->waypoints = array_map(fn(array $w) => CameraWaypoint::fromArray($w), $data);
	}

	/**
	 * Creates a CameraWaypoint.
	 *
	 * @param int $i
	 * @param float $delay
	 * @param Vector3|null $position
	 * @param float $yaw
	 * @param float $pitch
	 * @param float $duration
	 * @param int $easeType
	 * @param string $preset
	 * @return CameraWaypoint
	 */
	public function createWaypoint(
		int $i = 0,
		float $delay = 0.0,
		?Vector3 $position = null,
		float $yaw = 0.0,
		float $pitch = 0.0,
		float $duration = 1.0,
		int $easeType = CameraSetInstructionEaseType::LINEAR,
		string $preset = "minecraft:free"
	): CameraWaypoint {
		return (new CameraWaypoint(
			id: uniqid(),
			name: "Point #{$i}",
			position: $position,
			yaw: $yaw,
			pitch: $pitch,
			duration: $duration,
			easeType: $easeType,
			preset: $preset
		))->setDelay($delay);
	}

	/**
	 * Adds a CameraWaypoint to the waypoints list.
	 *
	 * @param CameraWaypoint $waypoint
	 * @return self
	 */
	public function addWaypoint(CameraWaypoint $waypoint): self {
		$this->waypoints[] = $waypoint;
		return $this;
	}

	/**
	 * Sets the list of CameraWaypoints.
	 *
	 * @param CameraWaypoint[] $waypoints
	 * @return self
	 */
	public function setWaypoints(array $waypoints): self {
		$this->waypoints = $waypoints;
		return $this;
	}

	/**
	 * Retrieves the list of CameraWaypoints.
	 *
	 * @return CameraWaypoint[]
	 */
	public function getWaypoints(): array {
		return $this->waypoints;
	}

	/**
	 * Clears all CameraWaypoints.
	 *
	 * @return self
	 */
	public function clearWaypoints(): self {
		$this->waypoints = [];
		return $this;
	}

	/**
	 * Plays a single CameraWaypoint.
	 *
	 * @param CameraWaypoint $wp
	 * @param float $delay
	 * @return self
	 */
	public function playWaypoint(CameraWaypoint $wp, float $delay = 0.0): self {
		return $this->queue(function() use ($wp): void {
			CameraPlayer::get($this->player)->set(
				$wp->getPreset(),
				$wp->getEaseType(),
				$wp->getEaseDuration(),
				$wp->getPosition(),
				$wp->getPitch(),
				$wp->getYaw()
			);
		}, $delay);
	}

	/**
	 * Plays all CameraWaypoints in sequence.
	 *
	 * @param bool $clearQueue
	 * @param bool $relative
	 * @return self
	 */
	public function playWaypoints(bool $clearQueue = true, bool $relative = true): self {
		if($clearQueue){
			$this->clearQueue();
		}
		$wps = array_filter($this->waypoints, fn($w) => $w instanceof CameraWaypoint);
		if(empty($wps)) return $this;
		if(!$relative){
			usort($wps, fn(CameraWaypoint $a, CameraWaypoint $b) => $a->getDelay() <=> $b->getDelay());
			/** @var CameraWaypoint $wp */
			foreach($wps as $wp){
				$this->playWaypoint($wp, $wp->getDelay());
			}
		}else{
			/** @var CameraWaypoint $wp */
			foreach($wps as $wp){
				$this->playWaypoint($wp);
				$this->wait($wp->getEaseDuration());
			}
		}
		return $this;
	}
}