<?php
namespace crates\command;

use pocketmine\command\Command;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class Crate extends Command implements PluginIdentifiableCommand {

  /** @var MysteryCrates */
  protected $main;
  
  public function __construct(MysteryCrates $plugin){
    $this->main = $plugin;
    
    parent::__construct("crate", "MysteryCrate's main command", "/crate <give|giveall> [player]", ['cratekey', 'ck']);
  }
  
  public function execute(CommandSender $sender, $label, array $args){
    if(count($args) == 0 or count($args) > 2){
			$sender->sendMessage(TextFormat::RED. "/crate <give/giveall> [player]");
			return true;
		}
		
		switch(strtolower($args[0])){
		  case 'give':
		    if($sender->hasPermission("mysterycrates.command.cratekey.give")){
						$player = $sender->getServer()->getPlayer($args[1]);
					if($player instanceof Player){
						if ( $this->plugin->giveCratekey($player) ) {
						  $player->sendMessage(TextFormat::GREEN. "You have been given a cratekey by ". TextFormat::GOLD. $sender->getName());
						  $sender->sendMessage(TextFormat::GOLD. "Given a cratekey to ".TextFormat::GOLD. $player->getName());
						  return true;
						} else {
						  $sender->sendMessage(TextFormat::RED."Target player doesn't have permission to get cratekey");
						  return true;
						}
					} else{
						$sender->sendMessage(TextFormat::RED. "That player cannot be found");
						return true;
					}
			  }
		  break;
		  case 'ga':
		  case 'givea':
		  case 'giveall':
		    if($sender->hasPermission("mysterycrates.command.cratekey.giveall")){
					$this->plugin->giveCratekeyAll();
					$sender->sendMessage(TextFormat::GOLD. "You have given a cratekey to everyone on the server!");
					$sender->getServer()->broadcastMessage(TextFormat::BOLD. TextFormat::BLUE. "[MysteryCrates]". TextFormat::GREEN. TextFormat::RESET. " Everyone has been given a cratekey by ".TextFormat::GOLD. $sender->getName()."! ");
				}
			break;
			default:
			   return false; // Send usage (Invalid sub-command)
		}
  }
  
  public function getPlugin(){
    return $this->main;
  }

}
