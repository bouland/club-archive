<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\RegistrationFormHandler;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class PlayerRegistrationHandler extends RegistrationFormHandler
{
	private $em;
	
	public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, EntityManager $entityManager)
	{
		parent::__construct($form,$request,$userManager,$mailer,$tokenGenerator);
		$this->em = $entityManager;
	}
	public function onSuccess(UserInterface $user, $confirmation){
		$user->setTeam($this->em->getRepository('AueioClubBundle:Config')->find(1)->getTeamDefault());
		parent::onSuccess($user, $confirmation);
	}
}