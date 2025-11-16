<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\utils;

use pocketmine\network\mcpe\protocol\types\ControlScheme;
use pocketmine\network\mcpe\protocol\ClientboundControlSchemeSetPacket;
use pocketmine\player\Player;

/**
 * Utility class for creating control scheme packets.
 * 
 * @method static ClientboundControlSchemeSetPacket getScheme(string $type)
 * @method static ClientboundControlSchemeSetPacket getLockedPlayerRelativeStrafe()
 * @method static ClientboundControlSchemeSetPacket getCameraRelative()
 * @method static ClientboundControlSchemeSetPacket getCameraRelativeStrafe()
 * @method static ClientboundControlSchemeSetPacket getPlayerRelative()
 * @method static ClientboundControlSchemeSetPacket getPlayerRelativeStrafe()
 */
final class ControlUtils {

	/**
	 * Returns a control scheme packet based on the provided type string.
	 * available types: `locked_player_relative_strafe` | `0`, `camera_relative`| `1`,
	 * `camera_relative_strafe`| `2`, `player_relative`| `3`, `player_relative_strafe`| `4`.
	 *
	 * @param string $type
	 * @return ClientboundControlSchemeSetPacket
	 * @throws \InvalidArgumentException
	 */
	public static function getScheme(string|int $type): ClientboundControlSchemeSetPacket {
		return match($type) {
			"locked_player_relative_strafe", 0 => self::getLockedPlayerRelativeStrafe(),
			"camera_relative", 1 => self::getCameraRelative(),
			"camera_relative_strafe", 2 => self::getCameraRelativeStrafe(),
			"player_relative", 3 => self::getPlayerRelative(),
			"player_relative_strafe", 4 => self::getPlayerRelativeStrafe(),
			default => throw new \InvalidArgumentException("Invalid control scheme type: " . $type),
		};
	}

	/**
	 * Sends a control scheme packet to the specified player.
	 *
	 * @param Player $player
	 * @param string $type available types: `locked_player_relative_strafe` | `0`, `camera_relative`| `1`,
	 * `camera_relative_strafe`| `2`, `player_relative`| `3`, `player_relative_strafe`| `4`.
	 * @return void
	 */
	public static function sendPacket(Player $player, string|int $type): void {
		$player->getNetworkSession()->sendDataPacket(self::getScheme($type));
	}

	/**
	 * Returns a packet to set the control scheme to LOCKED_PLAYER_RELATIVE_STRAFE.
	 *
	 * @return ClientboundControlSchemeSetPacket
	 */
	public static function getLockedPlayerRelativeStrafe(): ClientboundControlSchemeSetPacket{
		return ClientboundControlSchemeSetPacket::create(ControlScheme::LOCKED_PLAYER_RELATIVE_STRAFE);
	}

	/**
	 * Returns a packet to set the control scheme to CAMERA_RELATIVE.
	 *
	 * @return ClientboundControlSchemeSetPacket
	 */
	public static function getCameraRelative(): ClientboundControlSchemeSetPacket{
		return ClientboundControlSchemeSetPacket::create(ControlScheme::CAMERA_RELATIVE);
	}

	/**
	 * Returns a packet to set the control scheme to CAMERA_RELATIVE_STRAFE.
	 *
	 * @return ClientboundControlSchemeSetPacket
	 */
	public static function getCameraRelativeStrafe(): ClientboundControlSchemeSetPacket{
		return ClientboundControlSchemeSetPacket::create(ControlScheme::CAMERA_RELATIVE_STRAFE);
	}

	/**
	 * Returns a packet to set the control scheme to PLAYER_RELATIVE.
	 *
	 * @return ClientboundControlSchemeSetPacket
	 */
	public static function getPlayerRelative(): ClientboundControlSchemeSetPacket{
		return ClientboundControlSchemeSetPacket::create(ControlScheme::PLAYER_RELATIVE);
	}

	/**
	 * Returns a packet to set the control scheme to PLAYER_RELATIVE_STRAFE.
	 *
	 * @return ClientboundControlSchemeSetPacket
	 */
	public static function getPlayerRelativeStrafe(): ClientboundControlSchemeSetPacket{
		return ClientboundControlSchemeSetPacket::create(ControlScheme::PLAYER_RELATIVE_STRAFE);
	}
}