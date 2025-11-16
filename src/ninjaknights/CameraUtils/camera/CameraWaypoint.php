<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera;

use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEaseType;

/**
 * Represents a camera waypoint used in camera splines.
 * 
 * @method static self fromArray(array $data)
 * @method array toArray()
 */
final class CameraWaypoint {

	/** @var float */
	public float $delay = 0.0;

	/**
	 * Constructor for CameraWaypoint.
	 *
	 * @param string|null $id
	 * @param string|null $name
	 * @param Vector3|null $position
	 * @param float|null $yaw
	 * @param float|null $pitch
	 * @param float|null $duration
	 * @param int|null $easeType
	 */
	public function __construct(
		public ?string $id = null,
		public ?string $name = null,
		public ?Vector3 $position = null,
		public ?float $yaw = 0.0,
		public ?float $pitch = 0.0,
		public ?float $duration = 1.0,
		public ?int $easeType = CameraSetInstructionEaseType::LINEAR
	) {
		$this->id ??= uniqid("wp_", true);
		$this->name ??= "Waypoint #" . substr($this->id, -4);
	}

	public function getId(): ?string {
		return $this->id;
	}

	public function getName(): ?string {
		return $this->name;
	}

	public function getEaseType(): ?int {
		return $this->easeType;
	}

	public function getEaseDuration(): ?float {
		return $this->duration;
	}

	public function getPosition(): ?Vector3 {
		return $this->position;
	}

	public function getPitch(): ?float {
		return $this->pitch;
	}

	public function getYaw(): ?float {
		return $this->yaw;
	}

	public function getDelay(): ?float {
		return $this->delay;
	}

	public function setName(string $name): self {
		$this->name = $name;
		return $this;
	}

	public function setEase(int $easeType, float $duration): self {
		$this->easeType = $easeType;
		$this->duration = $duration;
		return $this;
	}

	public function setPosition(?Vector3 $pos): self {
		$this->position = $pos;
		return $this;
	}

	public function setRotation(float $pitch, float $yaw): self {
		$this->pitch = $pitch;
		$this->yaw = $yaw;
		return $this;
	}

	public function setDelay(float $delay): self {
		$this->delay = max(0.0, $delay);
		return $this;
	}

	/**
	 * Creates a CameraWaypoint from an associative array.
	 *
	 * @param array $data
	 * @return self
	 */
	public static function fromArray(array $data): self {
		$waypoint = new self(
			id: $data["id"] ?? uniqid(),
			name: $data["name"] ?? "Waypoint",
			position: isset($data["position"])
				? new Vector3($data["position"]["x"], $data["position"]["y"], $data["position"]["z"])
				: null,
			yaw: $data["yaw"] ?? 0.0,
			pitch: $data["pitch"] ?? 0.0,
			duration: $data["duration"] ?? ($data["easeTime"] ?? 1.0),
			easeType: $data["easeType"] ?? CameraSetInstructionEaseType::LINEAR
		);
		if(isset($data["delay"])){
			$waypoint->setDelay((float)$data["delay"]);
		}
		return $waypoint;
	}

	/**
	 * Converts the CameraWaypoint to an associative array.
	 *
	 * @return array
	 */
	public function toArray(): array {
		return [
			"id" => $this->id,
			"name" => $this->name,
			"position" => [
				"x" => $this->position?->x ?? 0,
				"y" => $this->position?->y ?? 0,
				"z" => $this->position?->z ?? 0,
			],
			"yaw" => $this->yaw,
			"pitch" => $this->pitch,
			"duration" => $this->duration,
			"easeType" => $this->easeType,
			"delay" => $this->delay
		];
	}
}