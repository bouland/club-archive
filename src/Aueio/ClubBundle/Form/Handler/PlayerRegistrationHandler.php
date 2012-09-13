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
	public function onSuccess(UserInterface $player, $confirmation){
		$address_new = $player->getAddress();
		$address = $this->em->getRepository('AueioClubBundle:Address')->findBy(array(	'name' => $address_new->getName(),
																					'code' => $address_new->getCode(),
																					'city' => $address_new->getCity()));
		if(is_array($address) && count($address) > 0)
		{
			$player->setAddress($address[0]);
		}else{
			$this->em->persist($address_new);
		}
		$seasons = $player->getSeasons();
		if($player->getSeasons()->isEmpty())
		{
			$season = $this->em->getRepository('AueioClubBundle:Season')->findCurrent();
			$player->addSeason($this->em->getRepository('AueioClubBundle:Season')->findCurrent());
		}
		$player->setCredit(0);
		$player->addRole('ROLE_PLAYER');
		parent::onSuccess($player, $confirmation);
	}
}