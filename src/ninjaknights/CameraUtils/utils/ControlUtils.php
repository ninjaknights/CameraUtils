<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\utils;

use pocketmine\network\mcpe\protocol\types\ControlScheme;
use pocketmine\network\mcpe\protocol\ClientboundControlSchemeSetPacket;
use pocketmine\player\Player;


/**
 * Utility class for creating and sending control scheme packets.
 *
 * Available control scheme types:
 *
 *  - "locked_player_relative_strafe" | 0 → ControlScheme::LOCKED_PLAYER_RELATIVE_STRAFE
 *  - "camera_relative"               | 1 → ControlScheme::CAMERA_RELATIVE
 *  - "camera_relative_strafe"        | 2 → ControlScheme::CAMERA_RELATIVE_STRAFE
 *  - "player_relative"               | 3 → ControlScheme::PLAYER_RELATIVE
 *  - "player_relative_strafe"        | 4 → ControlScheme::PLAYER_RELATIVE_STRAFE
 *
 * @method static ClientboundControlSchemeSetPacket getScheme(string|int $type)
 * @method static ClientboundControlSchemeSetPacket getLockedPlayerRelativeStrafe()
 * @method static ClientboundControlSchemeSetPacket getCameraRelative()
 * @method static ClientboundControlSchemeSetPacket getCameraRelativeStrafe()
 * @method static ClientboundControlSchemeSetPacket getPlayerRelative()
 * @method static ClientboundControlSchemeSetPacket getPlayerRelativeStrafe()
 */
final class ControlUtils {

	/**
	 * Returns a control scheme packet based on the given type.
	 * Accepted values:
	 *  - int: 0–4 (matches ControlScheme constants)
	 *  - string: one of:
	 *        "locked_player_relative_strafe",
	 *        "camera_relative",
	 *        "camera_relative_strafe",
	 *        "player_relative",
	 *        "player_relative_strafe"
	 *
	 * @param string|int $type
	 *
	 * @return ClientboundControlSchemeSetPacket
	 * @throws \InvalidArgumentException If the type is invalid.
	 */
	public static function getScheme(string|int $type): ClientboundControlSchemeSetPacket {
		return match ($type) {
			"locked_player_relative_strafe", 0 => self::getLockedPlayerRelativeStrafe(),
			"camera_relative",               1 => self::getCameraRelative(),
			"camera_relative_strafe",        2 => self::getCameraRelativeStrafe(),
			"player_relative",               3 => self::getPlayerRelative(),
			"player_relative_strafe",        4 => self::getPlayerRelativeStrafe(),
			default => throw new \InvalidArgumentException("Invalid control scheme type: " . $type),
		};
	}

	/**
	 * Sends a control scheme packet to a player.
	 *
	 * @param Player $player Player receiving the control scheme update.
	 * @param string|int $type Control scheme type (@see getScheme()).
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