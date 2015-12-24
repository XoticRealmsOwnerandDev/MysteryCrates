<?php
namespace crates\handler;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;

use crates\MysteryCrates;

class EventListener implements Listener {

  /** @var MysteryCrates */
  protected $main;
  
  public function __construct(MysteryCrates $plugin){
    $this->main = $plugin;
  }
  
  /////////////////////////////////////////////////////////////////////////////////////
  
  	public function onTouchCrate(PlayerInteractEvent $e){
		if($e->getBlock()->getId() == $this->getConfig()->get("target-block")){
			if($e->getItem()->getId() == $this->getConfig()->get("cratekey-item")){
				if($e->getPlayer()->hasPermission("mysterycrates.crates.open")){
					$e->setCancelled(true);
					if(!$this->getConfig()->get("enable-luck")){
						if($this->getConfig()->get("default-chance")) $this->openCrate($e->getPlayer());
					} else {
						$this->openCrate($e->getPlayer());
					}
				}
			}
		}
	}
	
	public function onItemHeld(PlayerItemHeldEvent $event){
	    if(!$this->getPlugin()->getConfig()->get("send-popup")) return;
	    if($event->getItem()->getId() !== $this->getPlugin()->getConfig()->get("cratekey-item")) return;
	    $event->getPlayer()->sendPopup($this->getPlugin()->getConfig()->get("createkey-popup"));
  }
  
  public function getPlugin(){
    return $this->main;
  }
  
}
