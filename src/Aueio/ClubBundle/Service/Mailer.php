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
	private $_em;
	private $_session;
	private $_twig;
	
	public function __construct(\Swift_Mailer $mailer, EntityManager $em, SessionInterface $session, \Twig_Environment $twig)
	{
		$this->_mailer = $mailer;
		$this->_em = $em;
		$this->_session = $session;
		$this->_twig = $twig;
	}
	
	public function sendContactEmailToPlayer(Player $to, Player $from, Array $context)
	{
		array_merge($context, array('to' => $to, 'from'=> $from));
		$this->sendMessage('AueioClubBundle:Player:email.contact.html.twig', $context, $from->getEmail(), $to_emails);
	}
	
	public function sendContactEmailToTeam(Team $to, Player $from, Array $context)
	{
		$season_id = $this->_session->get('season_id');
		$to_emails = $this->_em->getRepository('AueioClubBundle:Player')->findSeasonTeamEmails($to, $season_id);
		array_merge($context, array('to' => $to, 'from'=> $from));
		$this->sendMessage('AueioClubBundle:Team:email.contact.html.twig', $context, $from->getEmail(), $to_emails);
	}
	
	public function sendRecallEmailToTeam(Team $to, Player $from)
	{
		$season_id = $this->_session->get('season_id');
		$next_game = $this->_em->getRepository('AueioClubBundle:Game')->findSeasonTeamNextGame($to, $season_id);
		//$selection_link = $this->router->generateUrl('aueio_club_game_selection', array('id' => $next_game->getId()));
		$to_players = $this->_em->getRepository('AueioClubBundle:Player')->findTeamNextGameEmails($to, $next_game);
		$to_emails = array();
		foreach ($to_players as $player)
		{
			$to_emails[$player['firstname'] . ' ' . $player['lastname']] = $player['email'];
		}
		$context = array(
				'game' => $next_game,
				'to' => $to,
				'from' => $from
		);
		$this->sendMessage('AueioClubBundle:Team:email.recall.html.twig', $context, $from->getEmail(), array_values($to_emails));
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