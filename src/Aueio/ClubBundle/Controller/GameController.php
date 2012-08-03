<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\Validator\Constraints\DateTime;

use Aueio\ClubBundle\Entity\Game;
use Aueio\ClubBundle\Form\Type\GameType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
* @Route("/game")
*/

class GameController extends Controller
{

	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"})
	*/
	public function viewAction($id)
    {
    	$game = $this->getDoctrine()
				    	->getRepository('AueioClubBundle:Game')
				    	->find($id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id);
    	}
        return $this->render('AueioClubBundle:Game:view.html.twig', array('game' => $game));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$games = $this->getDoctrine()->getRepository('AueioClubBundle:Game')->findAll();
    	return $this->render('AueioClubBundle:Game:list.html.twig', array('games' => $games));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id);
    	}
    	 
    	$em->remove($game);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('aueio_club_game_list'));
    }
    /**
     * @Route("/new")
     **/
    public function newAction(Request $request)
    {
    	// just setup a fresh $task object (remove the dummy data)
    	$game = new Game();
    	$game->setDate(new \DateTime('now'));
    	
    	$form = $this->createForm(new GameType(), $game);
    	 
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		 
    		if ($form->isValid()) {
    			$em = $this->getDoctrine()->getEntityManager();
    			$em->persist($game);
    			$em->flush();
    			return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    		}
    	}
    
    	return $this->render('AueioClubBundle:Game:new.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id);
    	}
    	$form = $this->createForm(new GameType(), $game);
    
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		 
    		if ($form->isValid()) {
    			$em = $this->getDoctrine()->getEntityManager();
    			$em->persist($job);
    			$em->flush();
    
    			return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    		}
    	}
    
    	return $this->render('AueioClubBundle:Game:new.html.twig', array('id' => $game->getId(), 'form' => $form->createView()));
    }
}
