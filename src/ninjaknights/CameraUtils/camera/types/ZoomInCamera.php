<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEaseType;

final class ZoomInCamera extends BaseCamera {

	public float $fov = 1.6;
	public float $easeTime = 0.0;
	public int $easeType = CameraSetInstructionEaseType::LINEAR;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function fov(float $value = 1.6): self {
		$this->fov = $value;
		return $this;
	}

	public function easeTime(float $value = 0.0): self {
		$this->easeTime = $value;
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
					$this->fov,
					$this->easeTime,
					$this->easeType,
					false
				),
				spline: null,
				attachToEntity: null,
				detachFromEntity: null
			)
		);
	}
}