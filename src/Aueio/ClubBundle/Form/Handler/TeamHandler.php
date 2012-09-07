<?php 
// src/Auieo/ClubBundle/Form/Handler/TeamHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Team,
	Aueio\ClubBundle\Entity\Player;

class TeamHandler extends FormHandler
{
	public function onSuccess(Team $team)
	{
		foreach (array('boy', 'girl', 'goal') as $type){
			$player = new Player();
			$player->setUsername($team->getName() . $type);
			$player->setEmail($team->getName() . $type);
			$player->setPassword(sha1($type . time()));
			$player->setFirstname($type);
			$player->setLastname($team->getName());
			if($type == 'girl'){
				$player->setGender('F');
			}else{
				$player->setGender('M');
			}
			$player->setTeam($team);
			$this->em->persist($player);
		}
		
		$this->em->getRepository('AueioClubBundle:Adress');
		$this->em->persist($team->getAdress());
		$this->em->persist($team);
		$this->em->flush();
		return true;
	}
}
