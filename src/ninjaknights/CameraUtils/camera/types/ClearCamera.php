<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;

final class ClearCamera extends BaseCamera {

	public bool $resetFov = false;
	public bool $resetTarget = false;
	public bool $resetAttach = false;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetFov(bool $value = true): self {
		$this->resetFov = $value;
		return $this;
	}

	public function resetTarget(bool $value = true): self {
		$this->resetTarget = $value;
		return $this;
	}

	public function resetAttachedEntity(bool $value = true): self {
		$this->resetAttach = $value;
		return $this;
	}

	public function create(): void {
		$this->clear($this->resetFov, $this->resetTarget, $this->resetAttach);
	}
}