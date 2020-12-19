<?php

namespace souppvp\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use souppvp\Main;

class SoupKitCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("kit", "Gives you the a soupkit #SoupPvP", null, ["soupkit"]);
		$this->main = $main;
	}

	function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if($s instanceof Player){
			$this->main->getSoupKit()->soupKit($s);
			$s->sendPopup("»§o§bKit Claimed");
		}else{
			$s->sendMessage("§o§bSoupPvP §8» §cPlease use this command in game");
		}
		return true;
	}
}