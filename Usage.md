# CameraUtils Functions Reference

This document provides a reference for all
CameraUtils functions, including CameraPlayer, CameraAPI, timings,
effects, and waypoint examples.
<div align="center">
<br>
`Note: The Usage.md file does not cover all the functions in detail`
<br>
</div>

### CameraPlayer Class

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `init` | `Player $player` | `void` | Initializes the CameraPlayer for a specific player. |
| `get` | None | `Player` | Retrieves the player associated with this CameraPlayer instance. |
| `has` | None | `bool` | Checks if the CameraPlayer instance is valid. |
| `remove` | None | `void` | Removes the CameraPlayer instance and cleans up resources. |
| `all` | None | `CameraAPI[]` | Retrieves all active CameraAPI instances. |

Example Usage:

```php
use ninjaknights\CameraUtils\camera\CameraPlayer;
use pocketmine\player\Player;
$cameraPlayer = CameraPlayer::init($player);
$cameraPlayer->play();
```

### CameraAPI Class

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `__construct` | `Player $player` | `void` | Constructor for CameraAPI. Initializes the camera API for a specific player. |
| `getPlayer` | None | `Player` | Retrieves the player associated with this CameraAPI instance. |

### Timing & Playback Control

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `startTimings` | `callable $callback`<br>`int\|float $durationSeconds = 20`<br>`float $intervalSeconds = 1.0`<br>`?callable $onSkip = null` | `self` | Starts timing callbacks at specified intervals for a duration. Executes callback repeatedly until duration expires or player skips. |
| `stopTimings` | None | `self` | Stops the timing callbacks and cancels the timing task. |
| `play` | None | `void` | Starts playing the scheduled camera actions in sequence. |
| `stop` | `bool $resetQueue = true` | `void` | Stops the camera actions and optionally resets the action queue. |
| `pause` | None | `void` | Pauses the currently playing camera actions. |
| `resume` | None | `void` | Resumes paused camera actions. |
| `restart` | None | `void` | Restarts the camera actions from the beginning without clearing the queue. |
| `isRunning` | None | `bool` | Checks if the camera is currently running. |
| `isPaused` | None | `bool` | Checks if the camera is currently paused. |

### Queue Management

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `queue` | `callable $callback`<br>`float $delay = 0.0` | `self` | Queues a camera action to be executed after a specified delay. |
| `wait` | `float $seconds` | `self` | Adds a wait time before the next queued action. |
| `clearQueue` | None | `self` | Clears all queued camera actions and resets time offset. |
| `scheduleAll` | None | `void` | Schedules all queued camera actions for execution. |

### Configuration & Callbacks

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `speed` | `float $multiplier` | `self` | Sets the playback speed multiplier (minimum 0.1). |
| `setLoop` | `bool $loop` | `self` | Sets whether the camera actions should loop continuously. |
| `onFinish` | `?callable $callback` | `self` | Sets the callback to be executed when camera actions finish. |
| `onSkip` | `?callable $callback` | `self` | Sets the callback to be executed when camera actions are skipped (by sneaking). |

### Camera Effects

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `shakeStart` | `float $intensity = 0.5`<br>`float $duration = 1.0`<br>`int $type = CameraShakePacket::TYPE_POSITIONAL` | `self` | Starts a camera shake effect with specified intensity and duration. |
| `shakeStop` | `int $type = CameraShakePacket::TYPE_POSITIONAL` | `self` | Stops a camera shake effect of the specified type. |
| `zoomIn` | `float $fov = 1.6`<br>`float $time = 0.0`<br>`int $type = CameraSetInstructionEaseType::LINEAR` | `self` | Starts a zoom-in effect with specified field of view and easing. |
| `zoomOut` | None | `self` | Starts a zoom-out effect, returning to default zoom level. |
| `fov` | `float $fov = 1.0`<br>`float $duration = 0.0`<br>`int $type = CameraSetInstructionEaseType::LINEAR` | `self` | Sets the field of view with specified duration and easing. |
| `fade` | `float $fadeIn = 1.0`<br>`float $stay = 0.5`<br>`float $fadeOut = 1.0`<br>`Color\|DyeColor\|null $color = null` | `self` | Starts a fade effect with specified fade-in, stay, and fade-out times. |
| `clear` | `bool $resetFov = false` | `self` | Clears all camera effects and optionally resets field of view. |

### Camera Positioning & Targeting

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `set` | `int $easeType = CameraSetInstructionEaseType::LINEAR`<br>`float $easeTime = 1.0`<br>`?Vector3 $position = null`<br>`float $pitch = 0.0`<br>`float $yaw = 0.0`<br>`?Vector3 $facingPosition = null`<br>`?Vector2 $viewOffset = null`<br>`?Vector3 $entityOffset = null` | `self` | Sets comprehensive camera parameters including position, rotation, and offsets. |
| `targetPlayer` | `?Player $player = null` | `self` | Targets the camera at a specific player. |
| `targetPos` | `?Vector3 $position = null` | `self` | Targets the camera at a specific position. |
| `attachToEntity` | `Player\|Entity\|Living\|null $value = null` | `self` | Attaches the camera to follow an entity. |

### Waypoint Management

| Function | Parameters | Return Type | Description |
|----------|-----------|-------------|-------------|
| `createWaypoint` | `int $i = 0`<br>`float $delay = 0.0`<br>`?Vector3 $position = null`<br>`float $yaw = 0.0`<br>`float $pitch = 0.0`<br>`float $duration = 1.0`<br>`int $easeType = CameraSetInstructionEaseType::LINEAR` | `CameraWaypoint` | Creates a new CameraWaypoint with specified parameters. |
| `addWaypoint` | `CameraWaypoint $waypoint` | `self` | Adds a CameraWaypoint to the waypoints list. |
| `setWaypoints` | `CameraWaypoint[] $waypoints` | `self` | Sets the complete list of CameraWaypoints, replacing existing ones. |
| `getWaypoints` | None | `CameraWaypoint[]` | Retrieves the list of all CameraWaypoints. |
| `clearWaypoints` | None | `self` | Clears all CameraWaypoints from the list. |
| `playWaypoint` | `CameraWaypoint $wp`<br>`float $delay = 0.0` | `self` | Plays a single CameraWaypoint with optional delay. |
| `playWaypoints` | `bool $clearQueue = true`<br>`bool $relative = true` | `self` | Plays all CameraWaypoints in sequence. If relative is true, plays sequentially; if false, uses absolute delays. |
| `saveWaypoints` | `string $fileName` | `void` | Saves the waypoints to a JSON file in the cameras directory. |
| `loadWaypoints` | `string $fileName` | `void` | Loads waypoints from a JSON file in the cameras directory. |

### Method Chaining

Most methods return `self`, allowing for fluent method chaining. Example:

```php
$cameraApi = CameraPlayer::get($player);
// or
$cameraApi = new CameraAPI($player);
$cameraApi->fade(1.0, 0.5, 1.0, Color::BLACK)
	->wait(2.0)
	->zoomIn(1.5, 2.0)
	->shakeStart(0.5, 1.0)
	->wait(1.0)
	->clear()
	->play();
```

#### Notes

- The `queue()` method is used internally by most camera effect methods to schedule actions
- Player sneaking can trigger skip callbacks in `startTimings()` and `play()` methods
- Waypoints can be saved/loaded as JSON files for reusable camera paths
- The `playbackSpeed` affects timing but maintains relative proportions between actions

### CameraAPI Chain Building Examples

This document provides practical examples for building camera sequences using the CameraAPI, focusing on timings and waypoints.

---
#### Timings Examples

#### Example 1: Simple Countdown Timer
```php
use ninjaknights\CameraUtils\camera\CameraAPI;
use pocketmine\utils\TextFormat as TF;

$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

// Display a countdown from 10 to 0
$camera->startTimings(
	callback: function(CameraAPI $api, int $elapsed, Player $player) {
		$remaining = 10 - $elapsed; // duration
		$player->sendTitle(
			TF::BOLD . TF::GOLD . $remaining,
			TF::GRAY . "seconds remaining"
		);
	},
	durationSeconds: 10, // duration
	intervalSeconds: 1.0, // speed
	onSkip: function(CameraAPI $api, Player $player) {
		$player->sendMessage(TF::YELLOW . "Countdown skipped!");
	}
);
```
#### Example 2: Cinematic Intro with Timings
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

$camera->startTimings(
	callback: function(CameraAPI $api, int $elapsed, Player $player) {
		match($elapsed) {
			0 => $player->sendTitle(TF::BOLD . TF::AQUA . "Welcome", TF::GRAY . "to the server"),
			2 => $player->sendTitle(TF::BOLD . TF::GOLD . "Prepare", TF::GRAY . "for adventure"),
			4 => $player->sendTitle(TF::BOLD . TF::GREEN . "Get Ready", TF::GRAY . "to explore"),
			6 => $player->sendTitle(TF::BOLD . TF::RED . "Go!", ""),
			default => null
		};
	},
	durationSeconds: 8,
	intervalSeconds: 0.5
);
```
#### Example 3: Progress Bar with Camera Effects
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

$camera->fade(0.5, 0.0, 0.0, DyeColor::BLACK())->play();

$camera->startTimings(
	callback: function(CameraAPI $api, int $elapsed, Player $player) {
		$progress = ($elapsed / 20) * 100;
		$bars = str_repeat("█", (int)($progress / 5));
		$empty = str_repeat("░", 20 - strlen($bars));
		$player->sendActionBarMessage(
			TF::GREEN . $bars . TF::GRAY . $empty . TF::YELLOW . " " . round($progress) . "%"
		);
		
		// Add camera shake at 50% progress
		if($elapsed == 10) {
			$api->shakeStart(0.3, 1.0);
		}
	},
	durationSeconds: 20,
	intervalSeconds: 0.5,
	onSkip: function(CameraAPI $api, Player $player) {
		$api->clear(true);
		$player->sendMessage(TF::RED . "Loading cancelled!");
	}
);
```
#### Example 4: Interactive Tutorial with Timings
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

$tutorialSteps = [
	0 => ["title" => "Movement", "subtitle" => "Use WASD to move"],
	3 => ["title" => "Jumping", "subtitle" => "Press SPACE to jump"],
	6 => ["title" => "Inventory", "subtitle" => "Press E to open inventory"],
	9 => ["title" => "Combat", "subtitle" => "Left click to attack"],
	12 => ["title" => "Complete!", "subtitle" => "You're ready to play!"]
];

$camera
	->zoomIn(1.3, 1.0)
	->play();

$camera->startTimings(
	callback: function(CameraAPI $api, int $elapsed, Player $player) use ($tutorialSteps) {
		if(isset($tutorialSteps[$elapsed])) {
			$step = $tutorialSteps[$elapsed];
			$player->sendTitle(
				TF::BOLD . TF::GOLD . $step["title"],
				TF::AQUA . $step["subtitle"]
			);
		}
	},
	durationSeconds: 15,
	intervalSeconds: 1.0,
	onSkip: function(CameraAPI $api, Player $player) {
		$api->zoomOut()->clear()->play();
		$player->sendMessage(TF::GREEN . "Tutorial skipped!");
	}
);
```

---
### Waypoint Examples

#### Example 5: Simple Camera Path
```php
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\types\camera\CameraSetInstructionEaseType;

$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

// Define waypoints for a camera path
$waypoint1 = $camera->createWaypoint(
	i: 1,
	delay: 0.0,
	position: new Vector3(100, 70, 100),
	yaw: 0.0,
	pitch: 0.0,
	duration: 2.0,
	easeType: CameraSetInstructionEaseType::LINEAR
);

$waypoint2 = $camera->createWaypoint(
	i: 2,
	delay: 0.0,
	position: new Vector3(110, 75, 100),
	yaw: 45.0,
	pitch: -10.0,
	duration: 3.0,
	easeType: CameraSetInstructionEaseType::EASE_IN
);

$waypoint3 = $camera->createWaypoint(
	i: 3,
	delay: 0.0,
	position: new Vector3(120, 70, 110),
	yaw: 90.0,
	pitch: 0.0,
	duration: 2.0,
	easeType: CameraSetInstructionEaseType::EASE_OUT
);

$camera
	->addWaypoint($waypoint1)
	->addWaypoint($waypoint2)
	->addWaypoint($waypoint3)
	->playWaypoints(clearQueue: true, relative: true)
	->play();
```
#### Example 6: Circular Camera Movement
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);
$centerX = 100;
$centerZ = 100;
$radius = 20;
$height = 75;
$numPoints = 12;

// Create circular path waypoints
for($i = 0; $i < $numPoints; $i++) {
	$angle = ($i / $numPoints) * 2 * M_PI;
	$x = $centerX + ($radius * cos($angle));
	$z = $centerZ + ($radius * sin($angle));
	
	// Calculate yaw to always face the center
	$yaw = rad2deg(atan2($centerZ - $z, $centerX - $x));
	
	$waypoint = $camera->createWaypoint(
		i: $i + 1,
		position: new Vector3($x, $height, $z),
		yaw: $yaw,
		pitch: -15.0,
		duration: 1.5,
		easeType: CameraSetInstructionEaseType::EASE_IN_OUT
	);
	
	$camera->addWaypoint($waypoint);
}

$camera
	->setLoop(true) // Loop the circular movement
	->playWaypoints()
	->play();
```
#### Example 7: Cinematic Building Showcase
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

// Fade in
$camera->fade(1.0, 0.0, 0.0, DyeColor::BLACK());

// Ground level view
$wp1 = $camera->createWaypoint(
	i: 1,
	position: new Vector3(50, 65, 50),
	yaw: 0.0,
	pitch: 0.0,
	duration: 3.0,
	easeType: CameraSetInstructionEaseType::EASE_IN
);

// Rising view
$wp2 = $camera->createWaypoint(
	i: 2,
	position: new Vector3(50, 80, 50),
	yaw: 45.0,
	pitch: -20.0,
	duration: 4.0,
	easeType: CameraSetInstructionEaseType::LINEAR
);

// Top view with slow rotation
$wp3 = $camera->createWaypoint(
	i: 3,
	position: new Vector3(45, 100, 45),
	yaw: 180.0,
	pitch: -45.0,
	duration: 5.0,
	easeType: CameraSetInstructionEaseType::EASE_OUT
);

// Final dramatic angle
$wp4 = $camera->createWaypoint(
	i: 4,
	position: new Vector3(60, 70, 60),
	yaw: 225.0,
	pitch: -10.0,
	duration: 3.0,
	easeType: CameraSetInstructionEaseType::EASE_IN_OUT
);

$camera
	->addWaypoint($wp1)
	->addWaypoint($wp2)
	->addWaypoint($wp3)
	->addWaypoint($wp4)
	->onFinish(function(CameraAPI $api, Player $player) {
		$api->fade(0.0, 0.0, 1.0, DyeColor::BLACK())
		   ->clear(true)
		   ->play();
		$player->sendMessage(TF::GREEN . "Showcase complete!");
	})
	->playWaypoints()
	->play();
```
#### Example 8: Save and Load Waypoints
```php
// Creating and saving waypoints
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

$waypoints = [
	$camera->createWaypoint(1, 0, new Vector3(100, 70, 100), 0, 0, 2.0),
	$camera->createWaypoint(2, 0, new Vector3(110, 75, 110), 45, -10, 2.5),
	$camera->createWaypoint(3, 0, new Vector3(120, 70, 120), 90, 0, 2.0),
];

$camera->setWaypoints($waypoints);
$camera->saveWaypoints("spawn_tour");

// Later, load and play the saved waypoints
$camera2 = new CameraAPI($anotherPlayer);
$camera2->loadWaypoints("spawn_tour");
$camera2->playWaypoints()->play();
```
#### Example 9: Complex Chain with Waypoints and Effects
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

// Load pre-made waypoints
$camera->loadWaypoints("epic_intro");

$camera
	// Start with fade in
	->fade(2.0, 0.5, 0.0, DyeColor::BLACK())
	->wait(2.5)
	
	// Play the waypoint sequence
	->playWaypoints(clearQueue: false, relative: true)
	
	// Add effects during the sequence
	->queue(function(CameraAPI $api, Player $player) {
		$api->shakeStart(0.2, 0.5);
	}, 5.0) // Shake at 5 seconds
	
	->queue(function(CameraAPI $api, Player $player) {
		$player->sendTitle(TF::BOLD . TF::GOLD . "Welcome", TF::GRAY . "to our server");
	}, 7.0)
	
	->wait(3.0)
	
	// End with fade out and clear
	->fade(0.0, 0.0, 2.0, DyeColor::BLACK())
	->wait(2.0)
	->clear(true)
	
	->onFinish(function(CameraAPI $api, Player $player) {
		$player->sendMessage(TF::GREEN . "Enjoy your stay!");
	})
	->onSkip(function(CameraAPI $api, Player $player) {
		$api->clear(true);
		$player->sendMessage(TF::YELLOW . "Intro skipped!");
	})
	->play();
```
#### Example 10: Dynamic Waypoint Generation
```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

// Generate waypoints based on player's position
$playerPos = $player->getPosition();
$baseX = $playerPos->getX();
$baseY = $playerPos->getY() + 10;
$baseZ = $playerPos->getZ();

// Create a spiral upward effect
for($i = 0; $i < 8; $i++) {
	$angle = ($i / 8) * 2 * M_PI;
	$radius = 10 + ($i * 2); // Increasing radius
	$x = $baseX + ($radius * cos($angle));
	$z = $baseZ + ($radius * sin($angle));
	$y = $baseY + ($i * 3); // Rising height
	
	$yaw = rad2deg($angle) + 90;
	$pitch = -30 + ($i * 3); // Gradually looking down more
	
	$waypoint = $camera->createWaypoint(
		i: $i,
		position: new Vector3($x, $y, $z),
		yaw: $yaw,
		pitch: $pitch,
		duration: 2.0,
		easeType: CameraSetInstructionEaseType::EASE_IN_OUT
	);
	
	$camera->addWaypoint($waypoint);
}

$camera
	->fade(0.5, 0.0, 0.0)
	->wait(0.5)
	->playWaypoints()
	->onFinish(function(CameraAPI $api, Player $player) {
		$api->fade(0.0, 0.0, 0.5, DyeColor::WHITE())
		   ->clear(true)
		   ->play();
	})
	->play();
```

---
### Combined Example: Movie-Style Sequence

```php
$camera = CameraPlayer::get($player);
// or
$camera = new CameraAPI($player);

// Load pre-saved cinematic waypoints
$camera->loadWaypoints("cinematic_scene1");

// Black screen start
$camera
	->fade(1.5, 1.0, 0.0, DyeColor::BLACK())
	->wait(2.5);

// Start timed narration
$camera->startTimings(
	callback: function(CameraAPI $api, int $elapsed, Player $player) {
		$narration = [
			0 => "Long ago...",
			3 => "In a world of blocks...",
			6 => "Heroes emerged...",
			9 => "Will you be one?",
		];
		
		if(isset($narration[$elapsed])) {
			$player->sendTitle(
				TF::BOLD . TF::LIGHT_PURPLE . $narration[$elapsed],
				""
			);
		}
	},
	durationSeconds: 12,
	intervalSeconds: 1.0
);

// Play waypoints during narration
$camera
	->playWaypoints(clearQueue: false, relative: true)
	->wait(1.0)
	
	// Dramatic shake
	->shakeStart(0.5, 1.0)
	->wait(1.0)
	
	// Fade to white then clear
	->fade(0.0, 0.0, 2.0, DyeColor::WHITE())
	->wait(2.0)
	->clear(true)
	
	->speed(1.0) // Normal speed
	->setLoop(false)
	->onFinish(function(CameraAPI $api, Player $player) {
		$player->sendMessage(TF::GOLD . "Your adventure begins now!");
	})
	->onSkip(function(CameraAPI $api, Player $player) {
		$api->stopTimings();
		$api->clear(true);
		$player->sendMessage(TF::GRAY . "Cinematic skipped.");
	})
	->play();
```

---
## Tips for Building Chains

1. **Use `wait()` between effects** to control timing precisely
2. **Combine waypoints with effects** for dynamic sequences
3. **Save common waypoint paths** for reuse across different players
4. **Use `onFinish()` and `onSkip()`** to handle completion and interruption
5. **Test with different `speed()` multipliers** to find the right pacing
6. **Use `setLoop(true)`** for ambient/background camera movements
7. **Chain `queue()` calls** for custom timed events not covered by built-in effects
8. **Use relative waypoint timing** for sequential movements
9. **Use absolute waypoint timing** for synchronized events
10. **Always call `play()`** at the end of your chain to start execution