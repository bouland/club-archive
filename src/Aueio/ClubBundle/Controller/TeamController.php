<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use Aueio\ClubBundle\Entity\Player;
use Aueio\ClubBundle\Entity\Team;
use Aueio\ClubBundle\Form\Type\TeamType;
use Aueio\ClubBundle\Form\Handler\TeamHandler;
use Aueio\ClubBundle\Form\Type\ContactType;
/**
* @Route("/team")
*/

class TeamController extends Controller
{
	/**
	 * @Route("/welcome")
	 */
	public function welcomeAction()
	{
		if($team = $this->get('context.team')){
				return $this->viewAction($team);
		}
		return $this->listAction();
	}
	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"})
	*/
	public function viewAction(Team $team)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$season = $this->get('context.season');
    	$game_next = $em->getRepository('AueioClubBundle:Game')->findNextGameByTeam($team, time(), $season);
    	$trainning_next = $em->getRepository('AueioClubBundle:Game')->findNextTrainByTeam($team, time(), $season);
    	$contacts = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamLeaders($team, $season);
    	$members = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamMembers($team, $season);
    	$stats = array();
    	$stats['team'] = $em->getRepository('AueioClubBundle:Role')->getTeamStats($team);
    	foreach($members as $player){
    		$tmp = array();
    		$tmp['player'] = $player;
    		$tmp['stats'] = $em->getRepository('AueioClubBundle:Action')->getPlayerStats($player);
    		if(count($tmp['stats']) > 0){
    		    $stats['players'][] = $tmp;
    		}
    	}
        return $this->render('AueioClubBundle:Team:view.html.twig', array('team' => $team, 'game_next' => $game_next, 'trainning_next' => $trainning_next, 'contacts' => $contacts, 'stats' => $stats));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$season = $this->get('context.season');
    	$teams = $this->getDoctrine()->getRepository('AueioClubBundle:Team')->findBySeason($season);
    	return $this->render('AueioClubBundle:Team:list.html.twig', array('teams' => $teams));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction(Team $team){
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$config = $em->getRepository('AueioClubBundle:Config')->find(1);
    	if($config->getTeamFocus() == $team){
    		$config->removeTeamFocus();
    	}
    	$team->removePlayers();
    	foreach($team->getRoles() as $role){
    		$em->remove($role->getGame());
    	}
    	$em->remove($team);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('aueio_club_team_list'));
    }
    /**
     * @Route("/new")
     **/
    public function newAction(Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$team = new Team();
    	
    	$form = $this->createForm(new TeamType(), $team);
    
    	$formHandler = new TeamHandler($form, $request, $em);
    	if( $formHandler->process() )
    	{
    			return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Team:new.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction(Team $team, Request $request)
    {
    	$form = $this->createForm(new TeamType(), $team, array(
    			"type" => 'edit',
    			"season_id" => $this->get('context.season')->getId(),
    			"team_id" => $team->getId()
    	));
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$formHandler = new TeamHandler($form, $request, $em);
    	
        if( $formHandler->process(false) )
        {
    		return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Team:edit.html.twig', array('team' => $team, 'form' => $form->createView()));
    }
    /**
     * @Route("/contact/{id}", requirements={"id" = "\d+"})
     */
    public function contactAction(Team $team, Request $request)
    {
    	$from = $this->get('context.player');
    	if (!is_object($from) || !$from instanceof Player) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	$form = $this->createFormBuilder()
    					->add('subject', 'text')
    					->add('message', 'textarea')
    					->getForm();
    	
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	if( $request->getMethod() == 'POST' )
    	{
    		$form->bindRequest($request);
    		 
    		if( $form->isValid() )
    		{
    			if($team == $from->getTeam())
    			{
    				$this->get('aueio_club.mailer')->sendContactEmailToTeam($team, $from, $form->getData());
    			}else{
    				$this->get('aueio_club.mailer')->sendContactEmailToTeamLeaders($team, $from, $form->getData());
    			}
    			$this->get('session')->setFlash('notice', $this->get('translator')->trans('email.message.send',array(),'AueioClubBundle'));
    			return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    		}
    	}
    	$em = $this->getDoctrine()->getEntityManager();
    	$season = $this->get('context.season');
    	if($team == $from->getTeam())
    	{
    		$to = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamMembers($team, $season);
    	}else{
    		$to = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamLeaders($team, $season);
    	}
    	return $this->render('AueioClubBundle:Team:contact.html.twig', array('team' => $team, 'to' => $to, 'form' => $form->createView()));
    }
    /**
     * @Route("/call/{id}", requirements={"id" = "\d+"})
     */
    public function callAction(Team $team)
    {
    	$from = $this->get('context.player');
    	if (!is_object($from) || !$from instanceof Player) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	$this->get('aueio_club.mailer')->sendRecallEmailToTeam($team, $from);
    	$this->get('session')->setFlash('notice', $this->get('translator')->trans('email.message.send',array(),'AueioClubBundle'));
    	return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    }
}
