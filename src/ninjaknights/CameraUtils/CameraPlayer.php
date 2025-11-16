<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils;

use pocketmine\player\Player;
use ninjaknights\CameraUtils\camera\CameraAPI;

/**
 * Manages CameraAPI instances for players.
 * Used to ensure each player has a unique CameraAPI instance.
 * and doesn't create multiple instances for the same player or creating multiple instances.
 * 
 * @method static CameraAPI init(Player $player)
 * @method static CameraAPI|null get(Player $player)
 * @method static bool has(Player $player)
 * @method static void remove(Player $player)
 * @method static CameraAPI[] all()
 * @method static void clear()
 */
final class CameraPlayer {

	/** @var CameraAPI[] */
	public static array $instances = [];

	/**
	 * Initializes and returns a CameraAPI instance for the given player.
	 *
	 * @param Player $player
	 * @return CameraAPI
	 */
	public static function init(Player $player): CameraAPI {
		$uuid = $player->getUniqueId()->toString();
		if(!isset(self::$instances[$uuid])){
			self::$instances[$uuid] = new CameraAPI($player);
		}
		return self::$instances[$uuid];
	}

	/**
	 * Retrieves the CameraAPI instance for the given player, or null if not found.
	 *
	 * @param Player $player
	 * @return CameraAPI|null
	 */
	public static function get(Player $player): CameraAPI|null {
		return self::$instances[$player->getUniqueId()->toString()] ?? null;
	}

	/**
	 * Checks if a CameraAPI instance exists for the given player.
	 *
	 * @param Player $player
	 * @return bool
	 */
	public static function has(Player $player): bool {
		return isset(self::$instances[$player->getUniqueId()->toString()]);
	}

	/**
	 * Removes the CameraAPI instance for the given player.
	 *
	 * @param Player $player
	 * @return void
	 */
	public static function remove(Player $player): void {
		unset(self::$instances[$player->getUniqueId()->toString()]);
	}

	/**
	 * Returns all CameraAPI instances.
	 *
	 * @return CameraAPI[]
	 */
	public static function all(): array {
		return self::$instances;
	}

	/**
	 * Clears all CameraAPI instances.
	 *
	 * @return void
	 */
	public static function clear(): void {
		self::$instances = [];
	}
}