<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\preset;

use pocketmine\network\mcpe\protocol\CameraPresetsPacket;
use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\types\camera\CameraPreset;

/**
 * Base class for camera presets.
 * 
 * @method CameraPreset create()
 * @method void sendTo(Player $player)
 */
abstract class BasePreset {

	/** @var string */
	protected string $name;
	/** @var string|null */
	protected string|null $parent = null;

	/**
	 * Constructor for BasePreset.
	 *
	 * @param string $name
	 * @param string|null $parent
	 */
	public function __construct(string $name, ?string $parent = null) {
		$this->name = $name;
		$this->parent = $parent;
	}

	/**
	 * Gets the name of the preset.
	 *
	 * @return string
	 */
	public function getName(): string {
		return $this->name;
	}

	/**
	 * Gets the parent preset name, if any.
	 *
	 * @return string|null
	 */
	public function getParent(): ?string {
		return $this->parent;
	}

	/**
	 * Creates a CameraPreset object for this preset.
	 *
	 * @return CameraPreset
	 */
	abstract public function create(): CameraPreset;

	/**
	 * Sends the camera preset packet to the specified player.
	 *
	 * @param Player $player
	 * @return void
	 * @throws \LogicException if the player is disconnected or offline.
	 */
	public function sendTo(Player $player): void {
		if(!$player->isConnected() || !$player->isOnline()){
			throw new \LogicException("Cannot send Preset packet to a disconnected player.");
		}
		$player->getNetworkSession()->sendDataPacket(CameraPresetsPacket::create([$this->create()]), true);
	}
}