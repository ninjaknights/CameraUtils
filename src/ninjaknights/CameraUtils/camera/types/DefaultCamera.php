<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use ninjaknights\CameraUtils\preset\PresetRegistry;
use pocketmine\math\Vector2;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEase;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionRotation;

final class DefaultCamera extends BaseCamera {

	public ?int $preset = null;
	public ?CameraSetInstructionEase $easing = null;
	public ?Vector3 $position = null;
	public ?CameraSetInstructionRotation $rotation = null;
	public ?Vector3 $facingPosition = null;
	public ?Vector2 $viewOffset = null;
	public ?Vector3 $entityOffset = null;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function setPreset(?string $value = null): self {
		$this->preset = PresetRegistry::getPresetId($value ?? "minecraft:free");
		return $this;
	}

	public function setEase(int $type, float $duration): self {
		$this->easing = $this->createEase($type, $duration);
		return $this;
	}

	public function setPosition(?Vector3 $pos): self {
		$this->position = $pos;
		return $this;
	}

	public function setRotation(float $pitch, float $yaw): self {
		$this->rotation = $this->createRotation($pitch, $yaw);
		return $this;
	}

	public function setFacing(?Vector3 $pos = null): self {
		$this->facingPosition = $pos;
		return $this;
	}

	public function setViewOffset(?Vector2 $offset = null): self {
		$this->viewOffset = $offset;
		return $this;
	}

	public function setEntityOffset(?Vector3 $offset = null): self {
		$this->entityOffset = $offset;
		return $this;
	}

	public function create(): void {
		$this->createPacket(
			CameraInstructionPacket::create(
				set: $this->createInstruction(
					preset: $this->preset ?? PresetRegistry::getPresetId("minecraft:free"),
					ease: $this->easing ?? $this->createEase(),
					cameraPosition: $this->position,
					rotation: $this->rotation,
					facingPosition: $this->facingPosition,
					viewOffset: $this->viewOffset,
					entityOffset: $this->entityOffset
				),
				clear: null,
				fade: null,
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