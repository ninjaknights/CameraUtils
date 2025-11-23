<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera\types;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\entity\Entity;
use pocketmine\entity\Living;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\CameraInstructionPacket;
use pocketmine\player\Player;

final class TargetCamera extends BaseCamera {

	public Vector3|null $target = null;
	public int|null $targetId = null;
	public int|null $attachID = null;

	public function __construct(CameraAPI $api){
		parent::__construct($api);
	}

	public function resetCamera(): self {
		$this->clear(resetTarget: true, resetAttach: true);
		return $this;
	}

	public function setTargetPlayer(Player|null $target = null, ?Vector3 $offset = null): self {
		$this->target = $offset ?? new Vector3(0, 0, 0);
		$this->targetId = $target->getId();
		return $this;
	}

	public function attachToEntity(Player|Entity|Living|null $target = null): self {
		$this->attachID = $target->getId();
		return $this;
	}

	public function create(): void {
		$this->createPacket(
			CameraInstructionPacket::create(
				set: null,
				clear: null,
				fade: null,
				target: $this->createTarget(
					targetCenterOffset: $this->target,
					actorUniqueId: $this->targetId
				),
				removeTarget: null, 
				fieldOfView: null,
				spline: null,
				attachToEntity: $this->attachID,
				detachFromEntity: null
			)
		);
	}
}