<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\utils;

use pocketmine\block\utils\DyeColor;
use pocketmine\color\Color;

/**
 * Utility functions for vector and color manipulations.
 * 
 * @method static array normalizeColor(Color|DyeColor $color)
 */
final class VectorUtils {

	/**
	 * Normalizes a Color or DyeColor to an array of RGB float values (0.0 to 1.0).
	 *
	 * @param Color|DyeColor $color
	 * @return array
	 */
	public static function normalizeColor(Color|DyeColor $color): array {
		if($color instanceof DyeColor){
			$rgb = $color->getRgbValue();
			return [
				$rgb->getR() / 255.0,
				$rgb->getG() / 255.0,
				$rgb->getB() / 255.0
			];
		}
		return [
			$color->getR() / 255.0,
			$color->getG() / 255.0,
			$color->getB() / 255.0
		];
	}
}