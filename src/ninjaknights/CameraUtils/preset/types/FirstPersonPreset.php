<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\preset\types;

use ninjaknights\CameraUtils\preset\BasePreset;
use pocketmine\network\mcpe\protocol\types\camera\CameraPreset;

final class FirstPersonPreset extends BasePreset {

	public function __construct() {
		parent::__construct("minecraft:first_person");
	}

	public function create(): CameraPreset {
		return new CameraPreset(
			name: $this->name,
			parent: $this->parent ?? "",
			xPosition: null,
			yPosition: null,
			zPosition: null,
			pitch: null,
			yaw: null,
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
			audioListenerType: CameraPreset::AUDIO_LISTENER_TYPE_PLAYER,
			playerEffects: null,
			aimAssist: null,
			controlScheme: null
		);
	}
}