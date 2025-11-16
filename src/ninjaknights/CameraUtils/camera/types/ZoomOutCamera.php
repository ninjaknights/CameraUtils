<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;

final class ZoomOutCamera extends BaseCamera {

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
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
				fieldOfView: $this->createFov(clear: true),
				spline: null,
				attachToEntity: null,
				detachFromEntity: null
			)
		);
	}
}