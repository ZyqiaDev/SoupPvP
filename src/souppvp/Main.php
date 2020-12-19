<?php

namespace souppvp;

use pocketmine\plugin\PluginBase;
use souppvp\commands\SoupKitCommand;
use souppvp\commands\SoupRefillCommand;
use souppvp\eventlistener\EventListener;
use souppvp\kit\SoupKit;

class Main extends PluginBase
{
	public $soupkit;

	function onEnable()
	{
		$this->getLogger()->info("§o§bSoupPvP §8» §2Enabled");
		@mkdir($this->getDataFolder());
		$this->saveDefaultConfig();
		$this->registerEvent();
		$this->registerCommands();
		$this->soupkit = new SoupKit($this);
	}

	function registerEvent(){
		$this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
	}

	function registerCommands()
	{
		$this->getServer()->getCommandMap()->register("kit", new SoupKitCommand($this));
		$this->getServer()->getCommandMap()->register("souprefill", new SoupRefillCommand($this));
	}

	public function getSoupKit(): SoupKit
	{
		return $this->soupkit;
	}

	function onDisable()
	{
		$this->reloadConfig();
		$this->getLogger()->info("§o§bSoupPvP §8» §cDisabled");
	}
}