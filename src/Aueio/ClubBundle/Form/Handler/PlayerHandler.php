<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Player;

class PlayerHandler extends FormHandler
{
	public function onSuccess(Player $player){
		$this->em->persist($player);
		$this->em->flush();
	}
}
