<?php

namespace souppvp\commands;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\Item;
use pocketmine\Player;
use souppvp\Main;

class SoupRefillCommand extends Command
{
	private $main;

	function __construct(Main $main)
	{
		parent::__construct("souprefill", "Gives you more soups #SoupPvP", null);
		$this->main = $main;
	}

	function execute(CommandSender $s, string $commandLabel, array $args): bool
	{
		if($s instanceof Player){
			$soup = Item::get(Item::MUSHROOM_STEW, 0, 35);
			$soup->setCustomName($this->main->getConfig()->get("SoupName"));
			$soup->setLore([str_replace(["{line}"], ["\n"], $this->main->getConfig()->get("SoupLore"))]);
			$s->getInventory()->addItem($soup);
			$s->sendPopup("»§o§bYou have been given more soup");
		}else{
			$s->sendMessage("§o§bSoupPvP §8» §cPlease use this command in game");
		}
		return true;
	}
}