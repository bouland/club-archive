<?php 
// src/Auieo/ClubBundle/Form/Handler/TeamHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Team;

class TeamHandler extends FormHandler
{
	public function onSuccess(Team $team){
		$this->em->persist($team->getAdress());
		$this->em->persist($team);
		$this->em->flush();
	}
}
