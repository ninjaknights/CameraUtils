<?php
declare(strict_types=1);
namespace Britty;

use ninjaknights\CameraUtils\APIRegistry;
use ninjaknights\CameraUtils\CameraPlayer;
use ninjaknights\CameraUtils\preset\PresetRegistry;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\VanillaItems;

class Main extends PluginBase implements Listener{
	use SingletonTrait;

	public function onLoad(): void {
		self::setInstance($this);
	}

	public function onEnable(): void {
		if(!APIRegistry::isRegistered()){
			APIRegistry::register($this);
		}
		PresetRegistry::registerDefaults();
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
	}

	public function onJoin(PlayerJoinEvent $event): void {
		PresetRegistry::sendTo($event->getPlayer());
		if(CameraPlayer::has($event->getPlayer())) return;
		CameraPlayer::init($event->getPlayer());
	}

	public function onItemUse(PlayerItemUseEvent $event): void {
		$player = $event->getPlayer();
		$item = $event->getItem();
		if(!$camera = CameraPlayer::get($player)) return;
		switch($item->getTypeId()){
			case VanillaItems::ECHO_SHARD()->getTypeId():
				$camera->zoomIn(1.6, 1.0);
				$event->cancel();
				break;
			case VanillaItems::AMETHYST_SHARD()->getTypeId():
				$camera->clear(true);
				$event->cancel();
		}
	}
}