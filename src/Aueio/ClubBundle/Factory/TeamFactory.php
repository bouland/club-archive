<?php
namespace Aueio\ClubBundle\Factory;

use Aueio\ClubBundle\Entity\Player;

class TeamFactory
{
	private $player;

	public function __construct(Player $player = null)
	{
		$this->player = $player;
	}

	public function get()
	{
		$team = null;

		if ($this->player) {
			$team = $this->player->getTeam();
		}

		return $team;
	}
}