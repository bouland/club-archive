<?php

namespace Aueio\ClubBundle\Service;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface,
	Symfony\Component\HttpFoundation\Session\SessionInterface,
	Doctrine\ORM\EntityManager,
	Aueio\ClubBundle\Entity\Player,
	Aueio\ClubBundle\Entity\Team;

class Mailer
{
	private $_mailer;
	private $_templating;
	private $_em;
	private $_session;
	
	public function __construct(\Swift_Mailer $mailer, EngineInterface $templating, EntityManager $em, SessionInterface $session)
	{
		$this->_mailer = $mailer;
		$this->_templating = $templating;
		$this->_em = $em;
		$this->_session = $session;
	}
	
	public function emailPlayer(Player $to, Player $from, Array $data)
	{
		$message = \Swift_Message::newInstance()
		->setSubject($data['subject'])
		->setFrom($from->getEmail())
		->setTo($player->getEmail())
		->setBody($this->renderView('AueioClubBundle:Player:contact.email.html.twig', array('from' => $from, 'subject' => $data['subject'], 'message' => $data['message'])));
		$this->_mailer->send($message);
	}
	
	public function emailTeam(Team $to, Player $from, Array $data)
	{
		$season_id = $this->_session->get('season_id');
		$emails = $this->_em->getRepository('AueioClubBundle:Player')->findSeasonTeamEmails($to, $season_id);
		$message = \Swift_Message::newInstance()
		->setSubject($data['subject'])
		->setFrom($from->getEmail())
		->setTo($emails)
		->setBody($this->_templating->render('AueioClubBundle:Player:contact.email.html.twig', array('from' => $from, 'subject' => $data['subject'], 'message' => $data['message'])));
		$this->_mailer->send($message);
	}
	
}