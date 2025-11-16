<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera;

use ninjaknights\CameraUtils\camera\CameraAPI;
use ninjaknights\CameraUtils\CameraPlayer;
use ninjaknights\CameraUtils\utils\VectorUtils;
use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;
use pocketmine\network\mcpe\protocol\CameraShakePacket;
use pocketmine\network\mcpe\protocol\ClientboundPacket;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionColor;
use pocketmine\network\mcpe\protocol\types\camera\CameraFadeInstructionTime;
use pocketmine\network\mcpe\protocol\types\camera\CameraFovInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEase;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEaseType;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionRotation;
use pocketmine\network\mcpe\protocol\types\camera\CameraSplineInstruction;
use pocketmine\network\mcpe\protocol\types\camera\CameraTargetInstruction;
use pocketmine\player\Player;

/**
 * Base class for camera effects and instructions.
 */
abstract class BaseCamera {

	/** @var CameraAPI|null */
	protected CameraAPI|null $cameraApi = null;

	/**
	 * Constructor for BaseCamera.
	 *
	 * @param CameraAPI|null $cameraApi
	 */
	public function __construct(CameraAPI $cameraApi) {
		$this->cameraApi = $cameraApi;
	}

	/**
	 * Retrieves the associated CameraAPI instance.
	 *
	 * @return CameraAPI|null
	 */
	public function getCameraAPI(): ?CameraAPI {
		return $this->cameraApi ?? null;
	}

	/**
	 * Retrieves the player associated with the CameraAPI.
	 *
	 * @return Player
	 */
	public function getPlayer(): Player {
		return $this->cameraApi->getPlayer();
	}

	/**
	 * Creates and sends the camera instructions to the player.
	 *
	 * @return void
	 */
	abstract public function create(): void;

	/**
	 * Sends the camera instructions to all players with CameraAPI instances.
	 *
	 * @return void
	 */
	public function sendToAll(): void {
		foreach(CameraPlayer::all() as $cameraApi){
			$camera = clone $this;
			$camera->cameraApi = $cameraApi;
			$camera->create();
		}
	}

	/**
	 * Clears camera effects for all players with CameraAPI instances.
	 *
	 * @param bool $resetFov
	 * @param bool $resetTarget
	 * @param bool $resetAttach
	 * @return void
	 */
	public function clearAll(bool $resetFov = true, bool $resetTarget = true, bool $resetAttach = true): void {
		foreach(CameraPlayer::all() as $cameraApi){
			$camera = clone $this;
			$camera->cameraApi = $cameraApi;
			try{
				$camera->clear($resetFov, $resetTarget, $resetAttach);
			}catch(\LogicException $e){
				continue;
			}
		}
	}

	/**
	 * Sends a camera packet to the associated player.
	 *
	 * @param ClientboundPacket $packet
	 * @return void
	 * @throws \LogicException if the player is disconnected or offline.
	 */
	public function createPacket(ClientboundPacket $packet): void {
		$player = $this->getPlayer();
		if(!$player->isConnected() || !$player->isOnline()){
			throw new \LogicException("Cannot send Camera packet to a disconnected player.");
		}
		$player->getNetworkSession()->sendDataPacket($packet, true);
	}

	/**
	 * Creates a CameraSetInstruction instance.
	 *
	 * @param int $preset
	 * @param CameraSetInstructionEase|null $ease
	 * @param Vector3|null $cameraPosition
	 * @param CameraSetInstructionRotation|null $rotation
	 * @param Vector3|null $facingPosition
	 * @param Vector2|null $viewOffset
	 * @param Vector3|null $entityOffset
	 * @param bool $default
	 * @param bool $ignoreStartingValuesComponent
	 * @return CameraSetInstruction
	 */
	public function createInstruction(
		int $preset = 0,
		?CameraSetInstructionEase $ease = null,
		?Vector3 $cameraPosition = null,
		?CameraSetInstructionRotation $rotation = null,
		?Vector3 $facingPosition = null,
		?Vector2 $viewOffset = null,
		?Vector3 $entityOffset = null,
		?bool $default = true,
		bool $ignoreStartingValuesComponent = false
	): CameraSetInstruction {
		return new CameraSetInstruction(
			preset: $preset,
			ease: $ease,
			cameraPosition: $cameraPosition ?? null,
			rotation: $rotation,
			facingPosition: $facingPosition,
			viewOffset: $viewOffset,
			entityOffset: $entityOffset,
			default: $default,
			ignoreStartingValuesComponent: $ignoreStartingValuesComponent
		);
	}

	/**
	 * Creates a CameraSetInstructionEase instance.
	 *
	 * @param int $type
	 * @param float $duration
	 * @return CameraSetInstructionEase
	 */
	public function createEase(
		int $type = CameraSetInstructionEaseType::LINEAR,
		float $duration = 1.0
	): CameraSetInstructionEase {
		return new CameraSetInstructionEase($type, $duration);
	}

	/**
	 * Creates a CameraSetInstructionRotation instance.
	 *
	 * @param int|float $pitch
	 * @param int|float $yaw
	 * @return CameraSetInstructionRotation
	 */
	public function createRotation(
		int|float $pitch = 0,
		int|float $yaw = 0
	): CameraSetInstructionRotation {
		return new CameraSetInstructionRotation($pitch, $yaw);
	}

	/**
	 * Creates a CameraFadeInstruction instance.
	 *
	 * @param CameraFadeInstructionTime|null $time
	 * @param CameraFadeInstructionColor|null $color
	 * @return CameraFadeInstruction
	 */
	public function createFade(
		CameraFadeInstructionTime|null $time = null,
		CameraFadeInstructionColor|null $color = null
	): CameraFadeInstruction {
		return new CameraFadeInstruction($time, $color);
	}

	/**
	 * Creates a CameraFadeInstructionColor instance.
	 *
	 * @param Color|DyeColor|null $color
	 * @return CameraFadeInstructionColor
	 */
	public function createFadeColor(Color|DyeColor|null $color = null): CameraFadeInstructionColor {
		$data = VectorUtils::normalizeColor($color ?? DyeColor::BLACK());
		return new CameraFadeInstructionColor((float) $data[0], (float) $data[1], (float) $data[2]);
	}

	/**
	 * Creates a CameraFadeInstructionTime instance.
	 *
	 * @param float $fadeInTime
	 * @param float $stayTime
	 * @param float $fadeOutTime
	 * @return CameraFadeInstructionTime
	 */
	public function createFadeTime(
		float $fadeInTime = 1.0,
		float $stayTime = 0.5,
		float $fadeOutTime = 1.0
	): CameraFadeInstructionTime {
		return new CameraFadeInstructionTime($fadeInTime, $stayTime, $fadeOutTime);
	}

	/**
	 * Creates a CameraFovInstruction instance.
	 *
	 * @param float $fieldOfView
	 * @param float $easeTime
	 * @param int $easeType
	 * @param bool $clear
	 * @return CameraFovInstruction
	 */
	public function createFov(
		float $fieldOfView = 1.0,
		float $easeTime = 0.0,
		int $easeType = CameraSetInstructionEaseType::LINEAR,
		bool $clear = false
	): CameraFovInstruction {
		return new CameraFovInstruction($fieldOfView, $easeTime, $easeType, $clear);
	}

	/**
	 * Creates a CameraTargetInstruction instance.
	 *
	 * @param Vector3|null $targetCenterOffset
	 * @param int $actorUniqueId
	 * @return CameraTargetInstruction
	 */
	public function createTarget(
		?Vector3 $targetCenterOffset = null,
		int $actorUniqueId = 0
	): CameraTargetInstruction {
		return new CameraTargetInstruction($targetCenterOffset, $actorUniqueId);
	}

	/**
	 * Creates a CameraSplineInstruction instance.
	 *
	 * @param float $totalTime
	 * @param int $easeType
	 * @param array $curve
	 * @param array $progressKeyFrames
	 * @param array $rotationOptions
	 * @return CameraSplineInstruction
	 */
	public function createSpline(
		float $totalTime = 20.0,
		int $easeType = CameraSetInstructionEaseType::LINEAR,
		array $curve = [],
		array $progressKeyFrames = [],
		array $rotationOptions = []
	): CameraSplineInstruction {
		return new CameraSplineInstruction(
			totalTime: $totalTime,
			easeType: $easeType,
			curve: $curve,
			progressKeyFrames: $progressKeyFrames,
			rotationOptions: $rotationOptions
		);
	}

	/**
	 * Creates a CameraShakePacket instance to start a shake effect.
	 *
	 * @param float $intensity
	 * @param float $duration
	 * @param int $shakeType
	 * @return CameraShakePacket
	 */
	public function createShake(
		float $intensity = 0.2,
		float $duration = 1.0,
		int $shakeType = CameraShakePacket::TYPE_POSITIONAL
	): CameraShakePacket {
		return CameraShakePacket::create($intensity, $duration, $shakeType, CameraShakePacket::ACTION_ADD);
	}

	/**
	 * Creates a CameraShakePacket instance to stop a shake effect.
	 *
	 * @param int $shakeType
	 * @return CameraShakePacket
	 */
	public function stopShake(
		int $shakeType = CameraShakePacket::TYPE_POSITIONAL
	): CameraShakePacket {
		return CameraShakePacket::create(0.1, 0.1, $shakeType, CameraShakePacket::ACTION_STOP);
	}

	/**
	 * Clears camera effects for the associated player.
	 *
	 * @param bool $resetFov
	 * @param bool $resetTarget
	 * @param bool $resetAttach
	 * @return void
	 * @throws \LogicException if the player is disconnected or offline.
	 */
	public function clear(bool $resetFov = true, bool $resetTarget = true, bool $resetAttach = true): void {
		$player = $this->getPlayer();
		if(!$player->isConnected() || !$player->isOnline()){
			throw new \LogicException("Cannot send ClearCamera packet to a disconnected player.");
		}
		$target = $resetTarget ? true : null;
		$fov = $resetFov ? $this->createFov(1.0, 0.0, CameraSetInstructionEaseType::LINEAR, true) : null;
		$attach = $resetAttach ? true : null;
		$this->createPacket(
			CameraInstructionPacket::create(
				set: null,
				clear: true,
				fade: null,
				target: null,
				removeTarget: $target, 
				fieldOfView: $fov,
				spline: null,
				attachToEntity: null,
				detachFromEntity: $attach
			)
		);
	}
}