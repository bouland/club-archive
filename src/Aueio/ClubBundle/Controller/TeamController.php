<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use Aueio\ClubBundle\Entity\Team;
use Aueio\ClubBundle\Form\Type\TeamType;
use Aueio\ClubBundle\Form\Handler\TeamHandler;
/**
* @Route("/team")
*/

class TeamController extends Controller
{

	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"})
	*/
	public function viewAction(Team $team)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$contacts = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamContacts($team, $season_id);
    	$members = $em->getRepository('AueioClubBundle:Player')->findSeasonTeamMembers($team, $season_id);
    	$stats = $em->getRepository('AueioClubBundle:Role')->getStats($team);
    	$stats['total'] = count($team->getRoles());
    	
        return $this->render('AueioClubBundle:Team:view.html.twig', array('team' => $team, 'members' => $members, 'contacts' => $contacts, 'stats' => $stats));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$teams = $this->getDoctrine()->getRepository('AueioClubBundle:Team')->findSeasonAll($season_id);
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
    	
    	$form = $this->createForm(new TeamType(), new Team());
    
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
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$form = $this->createForm(new TeamType(), $team);
    
    	$formHandler = new TeamHandler($form, $request, $em);
    	
        if( $formHandler->process() )
        {
    		return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Team:edit.html.twig', array('team' => $team, 'form' => $form->createView()));
    }
}
