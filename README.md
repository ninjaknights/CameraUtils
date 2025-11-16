<div align="center">
<a href="https://github.com/ninjaknights/CameraUtils"> <img src="assets/icon_banner.png?raw=true" alt="CameraUtils Banner" width="80%" height="80%" /></a>
<p align="center">
	<b>CameraUtils</b> is a PocketMine-MP virion designed to help developers/users use Camera Features.
</p>

[![GitHub stars](https://img.shields.io/github/stars/ninjaknights/CameraUtils)](https://github.com/ninjaknights/CameraUtils/stargazers) [![GitHub forks](https://img.shields.io/github/forks/ninjaknights/CameraUtils)](https://github.com/ninjaknights/CameraUtils/network/members) <br> [![Github downloads](https://img.shields.io/github/downloads/ninjaknights/CameraUtils/total)](https://github.com/ninjaknights/CameraUtils/releases) <br> [![GitHub license](https://img.shields.io/github/license/ninjaknights/CameraUtils)](https://github.com/ninjaknights/CameraUtils/blob/main/LICENSE) [![GitHub issues](https://img.shields.io/github/issues/ninjaknights/CameraUtils)](https://github.com/ninjaknights/CameraUtils/issues) <br>
[![Microsoft Docs](https://img.shields.io/badge/📖-Microsoft_Docs-blue)](https://learn.microsoft.com/en-us/minecraft/creator/documents/camerasystem/cameracommandintroduction?view=minecraft-bedrock-stable) [![Packagist](https://img.shields.io/badge/📦-Packagist-orange)](https://packagist.org/packages/ninjaknights/camerautils)

</div>

## 🔌 Installation
Add CameraUtils to your project via Composer:
```bash
composer require ninjaknights/camerautils
```

## 🔌 Basic Setup
Before using the API, you **must register** it in your plugin’s `onLoad()` or `onEnable()` method:
```php
<?php

use ninjaknights\CameraUtils\APIRegistry;
use ninjaknights\CameraUtils\preset\PresetRegistry;

class MyPlugin extends PluginBase {
	public function onEnable(): void {
		// Register CameraUtils API
		if(!APIRegistry::isRegistered()){
			APIRegistry::register($this);
		}
		 // Register default camera presets
		PresetRegistry::registerDefaults();
	}
}
```

## 🔧 Usage & Examples
Check out our detailed examples and documentation:
- 📁 **Example Code**: See the `examples/` folder for practical implementations
- 📖 **Detailed Guide**: [USAGE.md](Usage.md) for comprehensive documentation

## 📮 Contributing
- Found a bug or wish to suggest some changes? [Open an issue](https://github.com/ninjaknights/CameraUtils/issues)
- Want to contribute? Fork the repository and make a pull request!

---

<div align="center">

#### 💬 Get Help & Connect to Us
[![Discord](https://img.shields.io/badge/Join_Our_Discord-5865F2?logo=discord&logoColor=white)](https://discord.gg/ZKfh5ycJrU) [![Website](https://img.shields.io/badge/🌐-Visit_Our_Website-2ea44f)](https://ninjaknights.net)

---

### 📜 License
This project is licensed under the **GPL-3.0 License** - see the [LICENSE](LICENSE) file for details.

###### **Made with ❤️ by NinjaKnights**
© 2025-2026 NinjaKnights <br>
[Website](https://ninjaknights.net) | [Discord](https://discord.gg/ZKfh5ycJrU)
</div>