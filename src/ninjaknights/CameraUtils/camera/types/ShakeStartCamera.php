<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\network\mcpe\protocol\CameraShakePacket;

final class ShakeStartCamera extends BaseCamera {

	public float $intensity = 1.0;
	public float $duration = 1.0;
	public int $type = CameraShakePacket::TYPE_POSITIONAL;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function intensity(float $value = 1.0): self {
		$this->intensity = $value;
		return $this;
	}

	public function duration(float $value = 1.0): self {
		$this->duration = $value;
		return $this;
	}

	public function type(int $value = CameraShakePacket::TYPE_POSITIONAL): self {
		$this->type = $value;
		return $this;
	}

	public function create(): void {
		$this->createPacket(
			$this->createShake(
				$this->intensity,
				$this->duration,
				$this->type
			)
		);
	}
}