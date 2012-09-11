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
		$player = $this->container->get('security.context')->getToken()->getUser();
		if (is_object($player) && $player instanceof Player) {
			$team = $player->getTeam();
			if($team){
				return $this->viewAction($team);
			}
		}
		return $this->listAction();
	}
	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"})
	*/
	public function viewAction(Team $team)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$game_next = $em->getRepository('AueioClubBundle:Game')->findSeasonTeamNextGame($team, new \DateTime('now'), $season_id);
    	$trainning_next = $em->getRepository('AueioClubBundle:Game')->findNextTrainByTeam($team, time(), $season_id);
    	$contacts = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamContacts($team, $season_id);
    	$members = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamMembers($team, $season_id);
    	$stats = $em->getRepository('AueioClubBundle:Role')->getTeamStats($team);
    	
        return $this->render('AueioClubBundle:Team:view.html.twig', array('team' => $team, 'game_next' => $game_next, 'trainning_next' => $trainning_next, 'members' => $members, 'contacts' => $contacts, 'stats' => $stats));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$teams = $this->getDoctrine()->getRepository('AueioClubBundle:Team')->findBySeason($season_id);
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
    	$form = $this->createForm(new TeamType(), $team);
    
    	$em = $this->getDoctrine()->getEntityManager();
    	$formHandler = new TeamHandler($form, $request, $em);
    	
        if( $formHandler->process() )
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
    	$from = $this->container->get('security.context')->getToken()->getUser();
    	if (!is_object($from) || !$from instanceof Player) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	$form = $this->createForm(new ContactType(), array());
    
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	if( $request->getMethod() == 'POST' )
    	{
    		$form->bindRequest($request);
    		 
    		if( $form->isValid() )
    		{
    			$this->get('aueio_club.mailer')->sendContactEmailToTeam($team, $from, $form->getData());
    			$this->get('session')->setFlash('notice', $this->get('translator')->trans('message.email.ok'));
    			return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    		}
    	}
    
    	return $this->render('AueioClubBundle:Team:contact.html.twig', array('team' => $team, 'form' => $form->createView()));
    }
    /**
     * @Route("/call/{id}", requirements={"id" = "\d+"})
     */
    public function callAction(Team $team)
    {
    	$from = $this->container->get('security.context')->getToken()->getUser();
    	if (!is_object($from) || !$from instanceof Player) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	$this->get('aueio_club.mailer')->sendRecallEmailToTeam($team, $from);
    	$this->get('session')->setFlash('notice', $this->get('translator')->trans('message.email.ok'));
    	return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    }
}
