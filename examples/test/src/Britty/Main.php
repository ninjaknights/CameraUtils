<?php
declare(strict_types=1);
namespace Britty;

use ninjaknights\CameraUtils\APIRegistry;
use ninjaknights\CameraUtils\camera\CameraAPI;
use ninjaknights\CameraUtils\CameraPlayer;
use ninjaknights\CameraUtils\preset\PresetRegistry;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;

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
		if($item->getTypeId() === VanillaItems::STICK()->getTypeId()){
			$camera->clear(true)
				->fade(1.0, 0.5, 1.0)
				->wait(4.0)
				->zoomIn(1.6, 2.0)
				->wait(2.0)
				->shakeStart(0.7, 1.5)
				->shakeStop()
				->wait(2.0)
				->zoomOut()
				->wait(4.0)
				->fade(1.0, 0.5, 1.0)
				->onFinish(function(CameraAPI $cam, Player $p) {
					$p->sendMessage("Camera finished playing");
					$cam->clear(true);
				})
				->play();
		}
	}
}