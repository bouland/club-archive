<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;
use Aueio\ClubBundle\Entity\Player;
use Aueio\ClubBundle\Entity\Team;
use Aueio\ClubBundle\Entity\Relation;


class PlayerHandler extends FormHandler
{
	public function onSuccess(array $data){
		$player = $data['player'];
		$this->em->persist($player);
		
		$team_id = $data['team_id'];
		$team = $this->em->getRepository('AueioClubBundle:Team')->find($team_id);
		if (!$team) {
			throw $this->createNotFoundException('No team found for id ' . $team_id);
		}
		$relation = new Relation(array(	'idOne' => $player,
										'idTwo' => $team,
										'name' => 'member'));
		$this->em->persist($relation);
		$this->em->flush();
	}
}
