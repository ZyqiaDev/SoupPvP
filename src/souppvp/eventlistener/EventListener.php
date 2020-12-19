<?php

namespace souppvp\eventlistener;

use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerExhaustEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\Item;
use pocketmine\Player;
use souppvp\Main;

class EventListener implements Listener
{
	private $main;

	function __construct(Main $main)
	{
		$this->main = $main;
	}

	function soupConsume(PlayerInteractEvent $ev): void
	{
		$p = $ev->getPlayer();
		$item = $ev->getItem();
		if ($ev->getAction() === PlayerInteractEvent::RIGHT_CLICK_AIR or $ev->getAction() === PlayerInteractEvent::LEFT_CLICK_AIR or $ev->getAction() === PlayerInteractEvent::LEFT_CLICK_BLOCK) {
			if ($item->getCustomName() == $this->main->getConfig()->get("SoupName")) {
				$bowl = Item::get(Item::BOWL, 0, 1);
				$bowl->setCustomName($this->main->getConfig()->get("BowlName"));
				$bowl->setLore([$this->main->getConfig()->get("BowlLore")]);
				$p->getInventory()->removeItem(Item::get(Item::MUSHROOM_STEW, 0, 1));
				$p->getInventory()->addItem($bowl);
				$p->setHealth($p->getHealth() + $this->main->getConfig()->get("HealthGained"));
			}
		}
	}

	function soupkitWhenJoin(PlayerJoinEvent $ev)
	{
		$p = $ev->getPlayer();
		$ev->setJoinMessage(str_replace(["{player}"], [$p->getDisplayName()], $this->main->getConfig()->get("JoinMsg")));
		if ($this->main->getConfig()->get("KitOnJoin") == "True") {
			$this->main->getSoupKit()->soupKit($p);
		}
	}

	function soupkitWhenRespawn(PlayerRespawnEvent $ev)
	{
		$p = $ev->getPlayer();
		if ($this->main->getConfig()->get("KitOnRespawn") == "True") {
			$this->main->getSoupKit()->soupKit($p);
		}
	}

	function playerLeaves(PlayerQuitEvent $ev)
	{
		$p = $ev->getPlayer();
		$ev->setQuitMessage(str_replace(["{player}"], [$p->getDisplayName()], $this->main->getConfig()->get("QuitMsg")));
	}

	function noFall(EntityDamageEvent $ev)
	{
		$p = $ev->getEntity();
		if ($this->main->getConfig()->get("NoFall") == "True") {
			if ($ev->getCause() === EntityDamageEvent::CAUSE_FALL)
				$ev->setCancelled();
		}
		if ($this->main->getConfig()->get("AntiVoid") == "True") {
			if ($ev->getCause() === EntityDamageEvent::CAUSE_VOID) {
				$p->teleport($this->main->getServer()->getDefaultLevel()->getSpawnLocation());
				$ev->setCancelled();
			}
		}
	}

	function noHunger(PlayerExhaustEvent $ev)
	{
		if ($this->main->getConfig()->get("NoHunger") == "True") {
			$ev->setCancelled(true);
		}
	}

	function killHealthSoup(PlayerDeathEvent $ev): void
	{
		$killer = $ev->getPlayer()->getLastDamageCause();
		if ($killer instanceof EntityDamageByEntityEvent) {
			$killer = $killer->getDamager();
			if ($killer instanceof Player) {
				$ev->setDeathMessage(str_replace(["{victim}", "{killer}"], [$ev->getPlayer()->getName(), $killer->getName()], $this->main->getConfig()->get("KillMsg")));
				if ($this->main->getConfig()->get("KillHealth") == "True") {
					$killer->setHealth($killer->getHealth() + $this->main->getConfig()->get("HealthGainedPerKill"));
				}
				if ($this->main->getConfig()->get("KillSoup") == "True") {
					$killer->getInventory()->addItem(Item::get(Item::MUSHROOM_STEW, 0, $this->main->getConfig()->get("SoupGainedPerKill")));
				}
			}
		}
	}

	function throwingItems(PlayerDropItemEvent $ev)
	{
		if ($this->main->getConfig()->get("AllowDroppingItems") == "True") {
			$ev->setCancelled(false);
		}
	}
}