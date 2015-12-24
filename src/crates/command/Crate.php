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
    
    parent::__construct("crate", "MysteryCrate's main command", "/crates <?> [?]", []);
  }
  
  public function execute(CommandSender $sender, $label, array $args){
    
  }
  
  public function getPlugin(){
    return $this->main;
  }

}
