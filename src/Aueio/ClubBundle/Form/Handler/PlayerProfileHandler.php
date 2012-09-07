<?php 
// src/Auieo/ClubBundle/Form/Handler/PlayerHandler.php

namespace Aueio\ClubBundle\Form\Handler;

use FOS\UserBundle\Form\Handler\ProfileFormHandler;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Model\UserManagerInterface;
use FOS\UserBundle\Mailer\MailerInterface;
use FOS\UserBundle\Util\TokenGeneratorInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;

class PlayerProfileHandler extends ProfileFormHandler
{
	private $em;
	
	public function __construct(FormInterface $form, Request $request, UserManagerInterface $userManager, MailerInterface $mailer, TokenGeneratorInterface $tokenGenerator, EntityManager $entityManager)
	{
		parent::__construct($form,$request,$userManager,$mailer,$tokenGenerator);
		$this->em = $entityManager;
	}
	public function onSuccess(UserInterface $player){
		$address_new = $player->getAdress();
		$address = $this->em->getRepository('AueioClubBundle:Adress')->findBy(array(	'name' => $address_new->getName(),
																					'code' => $address_new->getCode(),
																					'city' => $address_new->getCity()));
		if(is_array($address))
		{
			$player->setAdress($address[0]);
		}else{
			$this->em->persist($address_new);
		}
		$seasons = $player->getSeasons();
		if($player->getSeasons()->isEmpty())
		{
			$season = $this->em->getRepository('AueioClubBundle:Season')->findCurrent();
			$player->addSeason($this->em->getRepository('AueioClubBundle:Season')->findCurrent());
		}
		
		parent::onSuccess($player);
	}
}