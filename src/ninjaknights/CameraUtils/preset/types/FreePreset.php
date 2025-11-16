<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\preset\types;

use ninjaknights\CameraUtils\preset\BasePreset;
use pocketmine\network\mcpe\protocol\types\camera\CameraPreset;

final class FreePreset extends BasePreset {

	public function __construct() {
		parent::__construct("minecraft:free");
	}

	public function create(): CameraPreset {
		return new CameraPreset(
			name: $this->name,
			parent: $this->parent ?? "",
			xPosition: 0.0,
			yPosition: 0.0,
			zPosition: 0.0,
			pitch: 0.0,
			yaw: 0.0,
			rotationSpeed: null,
			snapToTarget: null,
			horizontalRotationLimit: null,
			verticalRotationLimit: null,
			continueTargeting: null,
			blockListeningRadius: null,
			viewOffset: null,
			entityOffset: null,
			radius: null,
			yawLimitMin: 0,
			yawLimitMax: 0,
			audioListenerType: CameraPreset::AUDIO_LISTENER_TYPE_CAMERA,
			playerEffects: null,
			aimAssist: null,
			controlScheme: null
		);
	}
}