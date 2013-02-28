<?php
namespace Aueio\ClubBundle\Factory;

use Aueio\ClubBundle\Entity\Player;

class TeamFactory extends \Twig_Extension
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
	
	public function getGlobals()
	{
		return array('current_team' => $this->get());
	}
	
	public function getName()
	{
		return 'team_extension';
	}
}