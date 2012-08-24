<?php 
// src/Auieo/ClubBundle/Form/Handler/SeasonHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Season;

class SeasonHandler extends FormHandler
{
	public function onSuccess(Season $season){
		$this->em->persist($season);
		$this->em->flush();
	}
}
