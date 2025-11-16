<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils;

use pocketmine\plugin\PluginBase;

/**
 * Registry for CameraUtils API registration status and plugin reference.
 * 
 * @method static bool isRegistered()
 * @method static void checkRegistered()
 * @method static PluginBase|null getPlugin()
 * @method static void register(PluginBase $plugin)
 * @method static void unregister()
 */
final class APIRegistry {

	/** @var bool */
	protected static bool $registered = false;
	/** @var PluginBase|null */
	public static PluginBase|null $plugin = null;

	/**
	 * Checks if CameraUtils is registered.
	 *
	 * @return bool
	 */
	public static function isRegistered(): bool {
		return self::$registered;
	}

	/**
	 * Ensures that CameraUtils is registered, throwing an exception if not.
	 *
	 * @return void
	 * @throws \LogicException if not registered.
	 */
	public static function checkRegistered(): void {
		if(!self::isRegistered()){
			throw new \LogicException("CameraUtils must be registered before use.");
		}
	}

	/**
	 * Retrieves the registered plugin instance.
	 *
	 * @return PluginBase|null
	 */
	public static function getPlugin(): PluginBase|null {
		self::checkRegistered();
		return self::$plugin;
	}

	/**
	 * Registers CameraUtils with the given plugin.
	 *
	 * @param PluginBase $plugin
	 * @return void
	 * @throws \LogicException if already registered.
	 */
	public static function register(PluginBase $plugin): void {
		if(self::$registered){
			throw new \LogicException("CameraUtils is already registered");
		}
		self::$plugin = $plugin;
		self::$registered = true;
	}

	/**
	 * Unregisters CameraUtils.
	 *
	 * @return void
	 */
	public static function unregister(): void {
		self::$plugin = null;
		self::$registered = false;
	}
}