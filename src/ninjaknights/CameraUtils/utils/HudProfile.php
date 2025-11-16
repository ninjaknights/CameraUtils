<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\utils;

use pocketmine\network\mcpe\protocol\types\hud\HudElement;

/**
 * Manages predefined HUD profiles.
 * 
 * @method static array getProfiles()
 * @method static ?array get(string $profile)
 * @method static bool exists(string $profile)
 * @method static void register(string $name, array $elements)
 * @method static ?array getCustomProfiles()
 */
final class HudProfile {

	/** @var array<string, array> */
	public static array $customProfiles = [];
	/** Predefined HUD profile names */
	public const CINEMATIC = 'cinematic';
	public const DEFAULT = 'default';
	public const COMBAT = 'combat';
	public const EXPLORATION = 'exploration';

	/**
	 * Retrieves all predefined HUD profiles.
	 *
	 * @return array<string, array>
	 */
	public static function getProfiles(): array {
		return [
			self::CINEMATIC => HudUtils::getDefaultElements(),
			self::DEFAULT => HudUtils::getAllElements(),
			self::COMBAT => [
				HudElement::HEALTH,
				HudElement::FOOD,
				HudElement::HOTBAR,
				HudElement::CROSSHAIR,
				HudElement::STATUS_EFFECTS,
			],
			self::EXPLORATION => [
				HudElement::HEALTH,
				HudElement::ITEM_TEXT,
				HudElement::HOTBAR,
			],
		];
	}

	/**
	 * Retrieves a specific HUD profile by name.
	 *
	 * @param string $profile
	 * @return array|null
	 */
	public static function get(string $profile): ?array {
		$profiles = self::getProfiles();
		return $profiles[strtolower($profile)] ?? null;
	}

	/**
	 * Checks if a HUD profile exists.
	 *
	 * @param string $profile
	 * @return bool
	 */
	public static function exists(string $profile): bool {
		return isset(self::getProfiles()[strtolower($profile)]);
	}

	/**
	 * Registers a custom HUD profile.
	 *
	 * @param string $name
	 * @param array $elements
	 * @return void
	 */
	public static function register(string $name, array $elements): void {
		self::$customProfiles[strtolower($name)] = $elements;
	}

	/**
	 * Retrieves all custom HUD profiles.
	 *
	 * @return array|null
	 */
	public static function getCustomProfiles(): ?array {
		return self::$customProfiles;
	}
}