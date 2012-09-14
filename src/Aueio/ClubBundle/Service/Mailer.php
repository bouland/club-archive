<?php

namespace Aueio\ClubBundle\Service;

use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface,
	Aueio\ClubBundle\Entity\Season,
	Doctrine\ORM\EntityManager,
	Aueio\ClubBundle\Entity\Player,
	Aueio\ClubBundle\Entity\Team;

class Mailer
{
	private $_mailer;
	private $_em;
	private $season;
	private $_twig;
	
	public function __construct(\Swift_Mailer $mailer, EntityManager $em, Season $season, \Twig_Environment $twig)
	{
		$this->_mailer = $mailer;
		$this->_em = $em;
		$this->season = $season;
		$this->_twig = $twig;
	}
	
	public function sendContactEmailToPlayer(Player $to, Player $from, Array $context)
	{
		$context = array_merge($context, array('to' => $to, 'from'=> $from));
		$this->sendMessage('AueioClubBundle:Player:email.contact.html.twig', $context, array( $from->getEmail() => $from->getFirstname() . ' ' . $from->getLastname()), array( $to->getEmail() => $to->getFirstname() . ' ' . $to->getLastname()));
	}
	
	public function sendContactEmailToTeam(Team $to, Player $from, Array $context)
	{
		$season = $this->season;
		$to_players = $this->_em->getRepository('AueioClubBundle:Player')->findSeasonTeamEmails($to, $season);
		$to_emails = array();
		foreach ($to_players as $player)
		{
			$to_emails[$player['email']] = $player['firstname'] . ' ' . $player['lastname'];
		}
		$context = array_merge($context, array('to' => $to, 'from'=> $from));
		$this->sendMessage('AueioClubBundle:Team:email.contact.html.twig', $context, array( $from->getEmail() => $from->getFirstname() . ' ' . $from->getLastname()), $to_emails);
	}
	
	public function sendRecallEmailToTeam(Team $to, Player $from)
	{
		$season = $this->season;
		$next_game = $this->_em->getRepository('AueioClubBundle:Game')->findSeasonTeamNextGame($to, new \DateTime('now'), $season);
		if($next_game)
		{
			$to_players = $this->_em->getRepository('AueioClubBundle:Player')->findTeamNextGameEmails($to, $next_game);
			$to_emails = array();
			foreach ($to_players as $player)
			{
				$to_emails[$player['email']] = $player['firstname'] . ' ' . $player['lastname'];
			}
			$context = array(
					'game' => $next_game,
					'to' => $to,
					'from' => $from
			);
			$this->sendMessage('AueioClubBundle:Team:email.recall.html.twig', $context, array( $from->getEmail() => $from->getFirstname() . ' ' . $from->getLastname()), $to_emails);
		}
		
	}
	
	protected function sendMessage($templateName, $context, $fromEmail, $toEmail)
	{
		$template = $this->_twig->loadTemplate($templateName);
		$subject = $template->renderBlock('subject', $context);
		$textBody = $template->renderBlock('body_text', $context);
		$htmlBody = $template->renderBlock('body_html', $context);
	
		$message = \Swift_Message::newInstance()
		->setSubject($subject)
		->setFrom($fromEmail)
		->setTo($toEmail);
	
		if (!empty($htmlBody)) {
			$message->setBody($htmlBody, 'text/html')
			->addPart($textBody, 'text/plain');
		} else {
			$message->setBody($textBody);
		}
	
		$this->_mailer->send($message);
	}
}