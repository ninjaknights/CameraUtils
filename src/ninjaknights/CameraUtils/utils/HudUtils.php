<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\utils;

use pocketmine\network\mcpe\protocol\SetHudPacket;
use pocketmine\network\mcpe\protocol\types\hud\{HudElement, HudVisibility};
use pocketmine\player\Player;

/**
 * Utility functions for managing HUD elements.
 * 
 * @method static void hide(Player $player, ?array $elements = null)
 * @method static void show(Player $player, ?array $elements = null)
 * @method static void toggle(Player $player, array $elements, bool $visible)
 * @method static void hideAll(Player $player)
 * @method static void showAll(Player $player)
 * @method static void sendPacket(Player $player, array $elements, HudVisibility $visibility)
 * @method static array getDefaultElements()
 * @method static array getAllElements()
 * @method static void applyProfile(Player $player, string $profile)
 */
final class HudUtils {

	/**
	 * Hides specified HUD elements for the player.
	 *
	 * @param Player $player
	 * @param array|null $elements
	 * @return void
	 */
	public static function hide(Player $player, ?array $elements = null): void {
		$elements ??= self::getDefaultElements();
		self::sendPacket($player, $elements, HudVisibility::HIDE);
	}

	/**
	 * Shows specified HUD elements for the player.
	 *
	 * @param Player $player
	 * @param array|null $elements
	 * @return void
	 */
	public static function show(Player $player, ?array $elements = null): void {
		$elements ??= self::getDefaultElements();
		self::sendPacket($player, $elements, HudVisibility::RESET);
	}

	/**
	 * Toggles visibility of specified HUD elements for the player.
	 *
	 * @param Player $player
	 * @param array $elements
	 * @param bool $visible
	 * @return void
	 */
	public static function toggle(Player $player, array $elements, bool $visible): void {
		$visibility = $visible ? HudVisibility::RESET : HudVisibility::HIDE;
		self::sendPacket($player, $elements, $visibility);
	}

	/**
	 * Hides all HUD elements for the player.
	 *
	 * @param Player $player
	 * @return void
	 */
	public static function hideAll(Player $player): void {
		self::sendPacket($player, self::getAllElements(), HudVisibility::HIDE);
	}

	/**
	 * Shows all HUD elements for the player.
	 *
	 * @param Player $player
	 * @return void
	 */
	public static function showAll(Player $player): void {
		self::sendPacket($player, self::getAllElements(), HudVisibility::RESET);
	}

	/**
	 * Sends a HUD packet to the player.
	 *
	 * @param Player $player
	 * @param array $elements
	 * @param HudVisibility $visibility
	 * @return void
	 * @throws \LogicException if the player is disconnected or offline.
	 */
	public static function sendPacket(Player $player, array $elements, HudVisibility $visibility): void {
		if(!$player->isConnected() || !$player->isOnline()){
			throw new \LogicException("Cannot send HUD packet to a disconnected player.");
		}
		$packet = SetHudPacket::create($elements, $visibility);
		$player->getNetworkSession()->sendDataPacket($packet, true);
	}

	/**
	 * Retrieves the default HUD elements.
	 *
	 * @return array
	 */
	public static function getDefaultElements(): array {
		return [
			HudElement::AIR_BUBBLES,
			HudElement::FOOD,
			HudElement::HEALTH,
			HudElement::PAPER_DOLL,
			HudElement::CROSSHAIR,
			HudElement::HOTBAR,
			HudElement::ITEM_TEXT,
			HudElement::TOOLTIPS,
			HudElement::TOUCH_CONTROLS
		];
	}

	/**
	 * Retrieves all HUD elements.
	 *
	 * @return array
	 */
	public static function getAllElements(): array {
		return [
			HudElement::PAPER_DOLL,
			HudElement::ARMOR,
			HudElement::TOOLTIPS,
			HudElement::TOUCH_CONTROLS,
			HudElement::CROSSHAIR,
			HudElement::HOTBAR,
			HudElement::HEALTH,
			HudElement::XP,
			HudElement::FOOD,
			HudElement::AIR_BUBBLES,
			HudElement::HORSE_HEALTH,
			HudElement::STATUS_EFFECTS,
			HudElement::ITEM_TEXT
		];
	}

	/**
	 * Applies a HUD profile to the player.
	 *
	 * @param Player $player
	 * @param string $profile
	 * @return void
	 */
	public static function applyProfile(Player $player, string $profile): void {
		$profile = strtolower($profile);
		$elements = HudProfile::get($profile);
		if($elements === null){
			$customProfiles = HudProfile::getCustomProfiles();
			$elements = $customProfiles[$profile] ?? null;
		}
		if($elements === null){
			throw new \InvalidArgumentException("Unknown HUD profile '$profile'");
		}
		self::hideAll($player);
		if(count($elements) > 0){
			self::show($player, $elements);
		}
	}
}