<?php
namespace crates;

use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\inventory\Inventory;

use crates\command\Crate;
use crates\handler\EventListener;

class MysteryCrates extends PluginBase {

	public $plugin, $votereward;
	
	public function onEnable(){
		$this->saveDefaultConfig();
		$this->cratekey = new CratekeyCommand($this);
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
		$this->votereward = $this->getServer()->getPluginManager()->getPlugin("VoteReward");
		
		if(!$this->votereward instanceof PluginBase and !$this->votereward->isEnabled()){
			$this->getLogger()->notice("VoteReward not found! Running without it.");
			// No need to reset $this->votereward, its already null
		} else {
			$this->getLogger()->info(TextFormat::GREEN."VoteReward".TextFormat::WHITE." loaded!");
		}
		
		$this->registerCommands();
	}
	
	public function registerCommands(){
		$commandMap = $this->getServer()->getCommandMap();
		
		$commandMap->register("MysteryCrates", new Crate($this));
	}
	
	////////////////////////////////////////////////////////
	
	/**
	 * Gives everyone or given array of players an crate key!
	 * 
	 * @param bool $checkperm = true
	 * @param Player[] $targets
	 */
	public function giveCratekeyAll($checkperm = true, $targets = []){
		if(empty($targets)) $targets = $this->getServer()->getOnlinePlayers();
		
		foreach($targets as $p){
			if(!$p instanceof Player) continue;
			if(!$checkperm){
				$p->getInventory()->addItem(Item::get($this->getConfig()->get("cratekey-item"), 0, 1));
			} else {
				if($p->hasPermission('mysterycrates.crates.get')) $p->getInventory()->addItem(Item::get(Item::get($this->getConfig()->get('cratekey-item'), 0, 1)));	
			}
		}
	}
	
	/**
	 * @param Player $p
	 * @return bool
	 */
	public function openCrate(Player $p, Inventory $inventory){
		if($this->getConfig()->get("broadcast-message-on-open")) $this->getServer()->broadcastMessage(TextFormat::BOLD. TextFormat::GREEN. "[MysteryCrates] ". TextFormat::RESET. TextFormat::RED. $p->getName(). " opened a crate!");
		
	}
	
	
	/**
	 * @param Player $p
	 * @return array|null
	 */
	public function giveCrateKey(Player $p){
		return $p->getInventory()->addItem(Item::get($this->getConfig()->get("cratekey-item"), 0, 1));
	}
	
	/**
	 * RandomAltThingy complete this i dont know whacha thinkin about!
	 * 
	 */
	public function onVote(){
		//TODO
	}
	
	/**
	 * 
	 * @param int $chance
	 * @return bool
	 */
	public function isLucky($chance){
		if(!is_numeric($chance)) return;
		if($chance < 1) return false;
		if($chance === mt_rand(1, $chance)) return true;
	}
	
}
