<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\network\mcpe\protocol\CameraShakePacket;

final class ShakeStopCamera extends BaseCamera {

	public int $type = CameraShakePacket::TYPE_POSITIONAL;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function type(int $value = CameraShakePacket::TYPE_POSITIONAL): self {
		$this->type = $value;
		return $this;
	}

	public function create(): void {
		$this->createPacket(
			$this->stopShake(
				$this->type
			)
		);
	}
}