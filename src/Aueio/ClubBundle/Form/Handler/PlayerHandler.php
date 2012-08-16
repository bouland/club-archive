<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler;
use FOS\UserBundle\Model\UserInterface;

class PlayerHandler extends RegistrationFormHandler
{
	public function onSuccess(UserInterface $user, $confirmation){
		parent::onSuccess($user, $confirmation);

	}
}