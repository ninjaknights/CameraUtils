<?php
declare(strict_types=1);
namespace ninjaknights\CameraUtils\camera;

use ninjaknights\CameraUtils\camera\BaseCamera;
use ninjaknights\CameraUtils\camera\types\{
	ClearCamera,
	ShakeStartCamera,
	ShakeStopCamera,
	ZoomInCamera,
	FovCamera,
	DefaultCamera,
	FadeCamera,
	ZoomOutCamera,
	TargetCamera
};

/**
 * Registry for camera types.
 * 
 * @method static void registerDefaults()
 * @method static void register(string $name, string $class)
 * @method static string|null get(string $name)
 * @method static array all()
 */
final class CameraRegistry {

	public static array $cameras = [];

	/**
	 * Registers the default camera types.
	 *
	 * @return void
	 */
	public static function registerDefaults(): void {
		self::register("default", DefaultCamera::class);
		self::register("fade", FadeCamera::class);
		self::register("clear", ClearCamera::class);
		self::register("shakeStart", ShakeStartCamera::class);
		self::register("shakeStop", ShakeStopCamera::class);
		self::register("zoom_in", ZoomInCamera::class);
		self::register("zoom_out", ZoomOutCamera::class);
		self::register("fov", FovCamera::class);
		self::register("target", TargetCamera::class);
	}

	/**
	 * Registers a new camera type.
	 *
	 * @param string $name
	 * @param string $class
	 * @return void
	 * @throws \LogicException if the camera type is already registered.
	 * @throws \InvalidArgumentException if the class does not extend BaseCamera.
	 */
	public static function register(string $name, string $class): void {
		$name = strtolower($name);
		if(isset(self::$cameras[$name])){
			throw new \LogicException("Camera type '$name' is already registered");
		}
		if(!is_subclass_of($class, BaseCamera::class)){
			throw new \InvalidArgumentException("Camera class '$class' must extend BaseCamera");
		}
		self::$cameras[$name] = $class;
	}

	/**
	 * Retrieves the camera class by name.
	 *
	 * @param string $name
	 * @return string|null
	 */
	public static function get(string $name): string|null {
		return self::$cameras[strtolower($name)] ?? null;
	}

	/**
	 * Retrieves all registered camera types.
	 *
	 * @return array
	 */
	public static function all(): array {
		return self::$cameras;
	}
}