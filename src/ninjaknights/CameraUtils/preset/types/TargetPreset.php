<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\preset\types;

use ninjaknights\CameraUtils\preset\BasePreset;
use pocketmine\network\mcpe\protocol\types\camera\CameraPreset;
use pocketmine\math\Vector2;

final class TargetPreset extends BasePreset {

	public function __construct() {
		parent::__construct("minecraft:target", "minecraft:free");
	}

	public function create(): CameraPreset {
		return new CameraPreset(
			name: $this->name,
			parent: $this->parent,
			xPosition: null,
			yPosition: null,
			zPosition: null,
			pitch: null,
			yaw: null,
			rotationSpeed: 0.0,
			snapToTarget: false,
			horizontalRotationLimit: new Vector2(0.0, 360.0),
			verticalRotationLimit: new Vector2(0.0, 180.0),
			continueTargeting: false,
			blockListeningRadius: 50.0,
			viewOffset: null,
			entityOffset: null,
			radius: null,
			yawLimitMin: null,
			yawLimitMax: null,
			audioListenerType: CameraPreset::AUDIO_LISTENER_TYPE_CAMERA,
			playerEffects: false,
			aimAssist: null,
			controlScheme: null
		);
	}
}