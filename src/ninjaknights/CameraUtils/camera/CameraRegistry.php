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
 * @method static void registerDefaults()                     Initializes all built-in camera types.
 * @method static void register(string $name, string $class)  Registers a camera type by name.
 * @method static string|null get(string $name)               Retrieves a camera class name by type name.
 * @method static array<string, class-string<BaseCamera>> all() Returns all registered camera types.
 */
final class CameraRegistry {

	/** @var array<string, class-string<BaseCamera>> */
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
	 * @param string $name  Unique name for this camera type (case-insensitive).
	 * @param class-string<BaseCamera> $class  Class name of the camera action.
	 * 
	 * @return void
	 * @throws \LogicException If the camera name is already registered.
	 * @throws \InvalidArgumentException If the class does not extend BaseCamera.
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
	 * @param string $name Camera type name (case-insensitive).
	 * @return class-string<BaseCamera>|null Fully-qualified class name or null if not found.
	 */
	public static function get(string $name): string|null {
		return self::$cameras[strtolower($name)] ?? null;
	}

	/**
	 * Retrieves all registered camera types.
	 *
	 * @return array<string, class-string<BaseCamera>>
	 */
	public static function all(): array {
		return self::$cameras;
	}
}