<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;

final class FadeCamera extends BaseCamera {

	public float $fadeInTime = 1.0;
	public float $stayTime = 0.5;
	public float $fadeOutTime = 1.0;
	public Color|DyeColor|null $color = null;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function fadeIn(float $value = 1.0): self {
		$this->fadeInTime = $value;
		return $this;
	}

	public function stay(float $value = 0.5): self {
		$this->stayTime = $value;
		return $this;
	}

	public function fadeOut(float $value = 1.0): self {
		$this->fadeOutTime = $value;
		return $this;
	}

	public function color(Color|DyeColor|null $value = null): self {
		$this->color = $value;
		return $this;
	}

	public function create(): void {
		$this->createPacket(
			CameraInstructionPacket::create(
				set: null,
				clear: null,
				fade: $this->createFade(
					$this->createFadeTime(
						$this->fadeInTime,
						$this->stayTime,
						$this->fadeOutTime
					),
					$this->createFadeColor($this->color ?? DyeColor::BLACK())
				),
				target: null,
				removeTarget: null, 
				fieldOfView: null,
				spline: null,
				attachToEntity: null,
				detachFromEntity: null
			)
		);
	}
}