<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;


class TeamPlayerHandler extends FormHandler
{
	public function onSuccess(array $data){
		$player = $data['player'];
		
		$team_id = $data['team_id'];
		$team = $this->em->getRepository('AueioClubBundle:Team')->find($team_id);
		if (!$team) {
			throw $this->createNotFoundException('No team found for id ' . $team_id);
		}
		$player->addTeam($team);
		$this->em->persist($player);
		$this->em->flush();
	}
}
