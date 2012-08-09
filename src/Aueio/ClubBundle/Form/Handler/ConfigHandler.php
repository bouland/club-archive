<?php 
// src/Auieo/ClubBundle/Form/Handler/ConfigHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Config;

class ConfigHandler extends FormHandler
{
	public function onSuccess(Config $config){
		$this->em->persist($config);
		$this->em->flush();
	}
}
