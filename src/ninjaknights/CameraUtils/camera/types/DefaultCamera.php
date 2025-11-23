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

	public int|null $preset = null;
	public CameraSetInstructionEase|null $easing = null;
	public Vector3|null $position = null;
	public CameraSetInstructionRotation|null $rotation = null;
	public Vector3|null $facingPosition = null;
	public Vector2|null $viewOffset = null;
	public Vector3|null $entityOffset = null;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function setPreset(string|null $value = null): self {
		$this->preset = PresetRegistry::getPresetId($value ?? "minecraft:free") ?? 0;
		return $this;
	}

	public function setEase(int $type, float $duration): self {
		$this->easing = $this->createEase($type, $duration);
		return $this;
	}

	public function setPosition(Vector3 $value): self {
		$this->position = $value;
		return $this;
	}

	public function setRotation(float $pitch, float $yaw): self {
		$this->rotation = $this->createRotation($pitch, $yaw);
		return $this;
	}

	public function setFacing(Vector3 $value): self {
		$this->facingPosition = $value;
		return $this;
	}

	public function setViewOffset(Vector2 $value): self {
		$this->viewOffset = $value;
		return $this;
	}

	public function setEntityOffset(Vector3 $value): self {
		$this->entityOffset = $value;
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
					entityOffset: $this->entityOffset,
					default: null,
					ignoreStartingValuesComponent: false
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