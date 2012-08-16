<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;

use FOS\UserBundle\Form\Handler\ProfileFormHandler;
use FOS\UserBundle\Model\UserInterface;

class PlayerProfileHandler extends ProfileFormHandler
{
	public function onSuccess(UserInterface $user){
		parent::onSuccess($user);

	}
}