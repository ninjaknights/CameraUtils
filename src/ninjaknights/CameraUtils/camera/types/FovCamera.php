<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEaseType;

final class FovCamera extends BaseCamera {

	public float $fov = 1.0;
	public float $duration = 0.0;
	public int $easeType = CameraSetInstructionEaseType::LINEAR;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear(resetFov: true);
		return $this;
	}

	public function fov(float $value = 1.0): self {
		$this->fov = $value;
		return $this;
	}

	public function duration(float $value = 0.0): self {
		$this->duration = $value;
		return $this;
	}

	public function easeType(int $value = CameraSetInstructionEaseType::LINEAR): self {
		$this->easeType = $value;
		return $this;
	}

	public function create(): void {
		$this->createPacket(
			CameraInstructionPacket::create(
				set: null,
				clear: null,
				fade: null,
				target: null,
				removeTarget: null, 
				fieldOfView: $this->createFov(
					fieldOfView: $this->fov,
					easeTime: $this->duration,
					easeType: $this->easeType,
					clear: false
				),
				spline: null,
				attachToEntity: null,
				detachFromEntity: null
			)
		);
	}
}