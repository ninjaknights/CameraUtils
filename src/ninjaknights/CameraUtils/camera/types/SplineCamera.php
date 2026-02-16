<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;
use pocketmine\network\mcpe\protocol\CameraSplinePacket;
use pocketmine\network\mcpe\protocol\types\camera\CameraProgressOption;
use pocketmine\network\mcpe\protocol\types\camera\CameraRotationOption;
use pocketmine\network\mcpe\protocol\types\camera\CameraSplineDefinition;
use pocketmine\network\mcpe\protocol\types\camera\CameraSplineInstruction;

# @todo implement this properly.
final class SplineCamera extends BaseCamera {

	public float $totalTime = 0.0;
	public int $easeType = 0;
	public array $curve = [];
	public array $progressKeyFrames = [];
	public array $rotationOptions = [];
	public array $splineDefinitions = [];

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear();
		return $this;
	}

	public function totalTime(float $value = 0.0): self {
		$this->totalTime = $value;
		return $this;
	}

	public function easeType(int $value = 0): self {
		$this->easeType = $value;
		return $this;
	}

	public function createCurvePoint(float $x, float $y, float $z): Vector3 {
		return new Vector3($x, $y, $z);
	}

	public function addCurvePoint(Vector3 $point): self {
		$this->curve[] = $point;
		return $this;
	}

	public function createSplineRotation(Vector3 $value, float $time): CameraRotationOption{
		return new CameraRotationOption($value, $time);
	}

	public function addSplineRotationOptions(CameraRotationOption $option): self {
		$this->rotationOptions[] = $option;
		return $this;
	}

	public function createProgressKeyFrame(float $value, float $time, int $easeType): CameraProgressOption {
		return new CameraProgressOption($value, $time, $easeType);
	}

	public function addProgressKeyFrame(CameraProgressOption $option): self {
		$this->progressKeyFrames[] = $option;
		return $this;
	}

	public function createDefinition(string $name, CameraSplineInstruction $instruction): CameraSplineDefinition {
		return new CameraSplineDefinition($name, $instruction);
	}

	public function addSplineDefinition(CameraSplineDefinition $definition): self {
		$this->splineDefinitions[] = $definition;
		return $this;
	}

	public function createSplinePacket(): CameraSplinePacket {
		return CameraSplinePacket::create($this->splineDefinitions);
	}

	public function create(): void {
		$this->createPacket(
			CameraInstructionPacket::create(
				set: null,
				clear: null,
				fade: null,
				target: null,
				removeTarget: null, 
				fieldOfView: null,
				spline: $this->createSpline(
					totalTime: $this->totalTime,
					easeType: $this->easeType,
					curve: $this->curve,
					progressKeyFrames: $this->progressKeyFrames,
					rotationOptions: $this->rotationOptions
				),
				attachToEntity: null,
				detachFromEntity: null
			)
		);
	}
}