<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler;
use Symfony\Component\HttpFoundation\Request;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use Doctrine\ORM\EntityManager;

class PlayerRegistrationHandler extends RegistrationFormHandler
{
	private $em;
	
	public function __construct(Form $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, EntityManager $entityManager)
	{
		parent::__construct($form,$request,$userManager,$mailer);
		$this->em = $entityManager;
	}
	public function onSuccess(UserInterface $user, $confirmation){
		$user->setTeam($this->em->getRepository('AueioClubBundle:Config')->find(1)->getTeamDefault());
		parent::onSuccess($user, $confirmation);
	}
}