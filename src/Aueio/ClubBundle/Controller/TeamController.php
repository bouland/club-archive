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
	public function viewAction($id)
    {
    	$team = $this->getDoctrine()
				    	->getRepository('AueioClubBundle:Team')
				    	->find($id);
    	if (!$team) {
    		throw $this->createNotFoundException('No team found for id '.$id);
    	}
        return $this->render('AueioClubBundle:Team:view.html.twig', array('team' => $team));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$teams = $this->getDoctrine()->getRepository('AueioClubBundle:Team')->findAll();
    	return $this->render('AueioClubBundle:Team:list.html.twig', array('teams' => $teams));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$team = $em->getRepository('AueioClubBundle:Team')->find($id);
    	if (!$team) {
    		throw $this->createNotFoundException('No team found for id '.$id);
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
    	// just setup a fresh $task object (remove the dummy data)
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
    public function editAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$team = $em->getRepository('AueioClubBundle:Team')->find($id);
    	if (!$team) {
    		throw $this->createNotFoundException('No team found for id '.$id);
    	}
    	$form = $this->createForm(new TeamType(), $team);
    
    	$formHandler = new TeamHandler($form, $request, $em);
    	
    	// On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process() )
        {
    		return $this->redirect($this->generateUrl('aueio_club_team_view', array('id' => $team->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Team:edit.html.twig', array('team' => $team, 'form' => $form->createView()));
    }
}
