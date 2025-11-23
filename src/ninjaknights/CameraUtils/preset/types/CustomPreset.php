<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\preset\types;

use ninjaknights\CameraUtils\preset\BasePreset;
use ninjaknights\CameraUtils\preset\PresetRegistry;
use pocketmine\network\mcpe\protocol\types\camera\CameraPreset;
use pocketmine\network\mcpe\protocol\types\camera\CameraPresetAimAssist;
use pocketmine\network\mcpe\protocol\types\ControlScheme;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;

class CustomPreset extends BasePreset {

	private ?float $x = null;
	private ?float $y = null;
	private ?float $z = null;
	private ?float $pitch = null;
	private ?float $yaw = null;
	private ?float $rotationSpeed = null;
	private ?bool $snapToTarget = null;
	private ?Vector2 $horizontalRotationLimit = null;
	private ?Vector2 $verticalRotationLimit = null;
	private ?bool $continueTargeting = null;
	private ?float $blockListeningRadius = null;
	private ?Vector2 $viewOffset = null;
	private ?Vector3 $entityOffset = null;
	private ?float $radius = null;
	private ?float $yawLimitMin = null;
	private ?float $yawLimitMax = null;
	private ?int $audioListenerType = CameraPreset::AUDIO_LISTENER_TYPE_PLAYER;
	private ?bool $playerEffects = null;
	private ?CameraPresetAimAssist $aimAssist = null;
	private ?ControlScheme $controlScheme = null;

	public function __construct(string $name, string|null $parent = null) {
		parent::__construct($name, $parent);
	}

	public function create(): CameraPreset {
		return new CameraPreset(
			name: $this->name,
			parent: $this->parent,
			xPosition: $this->x,
			yPosition: $this->y,
			zPosition: $this->z,
			pitch: $this->pitch,
			yaw: $this->yaw,
			rotationSpeed: $this->rotationSpeed,
			snapToTarget: $this->snapToTarget,
			horizontalRotationLimit: $this->horizontalRotationLimit,
			verticalRotationLimit: $this->verticalRotationLimit,
			continueTargeting: $this->continueTargeting,
			blockListeningRadius: $this->blockListeningRadius,
			viewOffset: $this->viewOffset,
			entityOffset: $this->entityOffset,
			radius: $this->radius,
			yawLimitMin: $this->yawLimitMin,
			yawLimitMax: $this->yawLimitMax,
			audioListenerType: $this->audioListenerType,
			playerEffects: $this->playerEffects,
			aimAssist: $this->aimAssist,
			controlScheme: $this->controlScheme
		);
	}

	public function setPosition(float $x, float $y, float $z): self {
		$this->x = $x;
		$this->y = $y;
		$this->z = $z;
		return $this;
	}

	public function setPitch(float $pitch): self {
		$this->pitch = $pitch;
		return $this;
	}

	public function setYaw(float $yaw): self {
		$this->yaw = $yaw;
		return $this;
	}

	public function setRotationSpeed(float $value): self {
		$this->rotationSpeed = $value;
		return $this;
	}

	public function setSnapToTarget(bool $value): self {
		$this->snapToTarget = $value;
		return $this;
	}

	public function setHorizontalRotationLimit(float $min, float $max): self {
		$this->horizontalRotationLimit = new Vector2($min, $max);
		return $this;
	}

	public function setVerticalRotationLimit(float $min, float $max): self {
		$this->verticalRotationLimit = new Vector2($min, $max);
		return $this;
	}

	public function setContinueTargeting(bool $value): self {
		$this->continueTargeting = $value;
		return $this;
	}

	public function setBlockListeningRadius(float $value): self {
		$this->blockListeningRadius = $value;
		return $this;
	}

	public function setViewOffset(Vector2 $value): self {
		$this->viewOffset = $value;
		return $this;
	}

	public function setEntityOffset(Vector3 $value): self {
		$this->entityOffset = $value;
		return $this;
	}

	public function setRadius(float $value): self {
		$this->radius = $value;
		return $this;
	}

	public function setYawLimitMin(float $value): self {
		$this->yawLimitMin = $value;
		return $this;
	}

	public function setYawLimitMax(float $value): self {
		$this->yawLimitMax = $value;
		return $this;
	}

	public function setAudioListenerType(int $value): self {
		$this->audioListenerType = $value;
		return $this;
	}

	public function showPlayerEffects(bool $value): self {
		$this->playerEffects = $value;
		return $this;
	}

	public function setCameraAimAssist(CameraPresetAimAssist $value): self {
		$this->aimAssist = $value;
		return $this;
	}

	public function setControlScheme(ControlScheme $value): self {
		$this->controlScheme = $value;
		return $this;
	}

	public function register(): self {
		PresetRegistry::registerPreset($this);
		return $this;
	}
}