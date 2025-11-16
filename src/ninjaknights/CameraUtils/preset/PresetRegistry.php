<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\preset;

use pocketmine\player\Player;
use pocketmine\network\mcpe\protocol\CameraPresetsPacket;
use ninjaknights\CameraUtils\preset\BasePreset;
use ninjaknights\CameraUtils\preset\types\{FreePreset, FirstPersonPreset, ThirdPersonPreset, ThirdPersonFrontPreset, FollowOrbitPreset, FixedBoomPreset, TargetPreset};

/**
 * Registry for managing camera presets.
 * 
 * @method static void registerDefaults()
 * @method static int|null getPresetId(string $name)
 * @method static void registerPreset(BasePreset $preset)
 * @method static void unregisterPreset(string $name)
 * @method static BasePreset|null get(string $name)
 * @method static bool isRegistered(string $name)
 * @method static int count()
 * @method static array getAll()
 * @method static array getAllPresets()
 * @method static void sendTo(Player $player)
 */
final class PresetRegistry {

	/** @var array<string, BasePreset> */
	public static array $presets = [];

	/**
	 * Registers the default camera presets.
	 *
	 * @return void
	 */
	public static function registerDefaults(): void {
		if(!empty(self::$presets)){
			return;
		}
		self::registerPreset(new FreePreset());
		self::registerPreset(new FirstPersonPreset());
		self::registerPreset(new ThirdPersonPreset());
		self::registerPreset(new ThirdPersonFrontPreset());
		self::registerPreset(new FollowOrbitPreset());
		self::registerPreset(new FixedBoomPreset());
		self::registerPreset(new TargetPreset());
	}

	/**
	 * Retrieves the preset ID by name.
	 *
	 * @param string $name
	 * @return int|null
	 */
	public static function getPresetId(string $name): ?int {
		$needle = strtolower($name);
		$haystack = array_keys(self::$presets);
		$index = array_search($needle, $haystack, true);
		return $index === false ? null : $index;
	}

	/**
	 * Registers a new camera preset.
	 *
	 * @param BasePreset $preset
	 * @return void
	 * @throws \LogicException if the preset is already registered.
	 */
	public static function registerPreset(BasePreset $preset): void {
		$name = strtolower($preset->getName());
		if(isset(self::$presets[$name])){
			throw new \LogicException("Preset '$name' is already registered.");
		}
		self::$presets[$name] = $preset;
	}

	/**
	 * Unregisters a camera preset by name.
	 *
	 * @param string $name
	 * @return void
	 */
	public static function unregisterPreset(string $name): void {
		unset(self::$presets[strtolower($name)]);
	}

	/**
	 * Retrieves a camera preset by name.
	 *
	 * @param string $name
	 * @return BasePreset|null
	 */
	public static function get(string $name): BasePreset|null {
		return self::$presets[strtolower($name)] ?? null;
	}

	/**
	 * Checks if a camera preset is registered by name.
	 *
	 * @param string $name
	 * @return bool
	 */
	public static function isRegistered(string $name): bool {
		return isset(self::$presets[strtolower($name)]);
	}

	/**
	 * Counts the number of registered camera presets.
	 *
	 * @return int
	 */
	public static function count(): int {
		return count(self::$presets);
	}

	/**
	 * Retrieves all registered camera presets.
	 *
	 * @return array<string, BasePreset>
	 */
	public static function getAll(): array {
		return self::$presets;
	}

	/**
	 * Retrieves all registered camera presets as an indexed array.
	 *
	 * @return array<BasePreset>
	 */
	public static function getAllPresets(): array {
		return array_values(self::$presets);
	}

	/**
	 * Retrieves all registered camera presets as CameraPreset objects.
	 *
	 * @return array<\ninjaknights\CameraUtils\camera\CameraPreset>
	 */
	public static function getAllAsCameraPresets(): array {
		$array = [];
		foreach(self::getAll() as $preset){
			$array[] = $preset->create();
		}
		return $array;
	}

	/**
	 * Sends the camera presets packet to the specified player.
	 *
	 * @param Player $player
	 * @return void
	 * @throws \LogicException if the player is disconnected or offline.
	 */
	public static function sendTo(Player $player): void {
		if(!$player->isConnected() || !$player->isOnline()){
			throw new \LogicException("Cannot send Presets packet to a disconnected player.");
		}
		$player->getNetworkSession()->sendDataPacket(CameraPresetsPacket::create(self::getAllAsCameraPresets()), true);
	}
}