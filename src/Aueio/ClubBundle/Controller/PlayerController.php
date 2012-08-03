<?php

namespace Aueio\ClubBundle\Controller;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use Aueio\ClubBundle\Entity\Player;
use Aueio\ClubBundle\Form\Type\PlayerType;
use Aueio\ClubBundle\Form\Handler\PlayerHandler;
/**
* @Route("/player")
*/

class PlayerController extends Controller
{

	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"})
	*/
	public function viewAction($id)
    {
    	$player = $this->getDoctrine()
				    	->getRepository('AueioClubBundle:Player')
				    	->find($id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$id);
    	}
        return $this->render('AueioClubBundle:Player:view.html.twig', array('player' => $player));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$players = $this->getDoctrine()->getRepository('AueioClubBundle:Player')->findAll();
    	return $this->render('AueioClubBundle:Player:list.html.twig', array('players' => $players));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$player = $em->getRepository('AueioClubBundle:Player')->find($id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$id);
    	}
    	 
    	$em->remove($player);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('aueio_club_player_list'));
    }
    /**
     * @Route("/new")
     **/
    public function newAction(Request $request)
    {
    	$em = $this->get('doctrine')->getEntityManager();
    	$player = new Player();
    	$player->setDateRegister(new \DateTime('now'));
    	$data = array('player' => $player);
    	
    	$builder = $this->createFormBuilder($data);
    	$builder->add('player', new PlayerType());
		$choices = array();
    	$teams = $em->getRepository('AueioClubBundle:Team')->findAll();
    	foreach($teams as $team){
    		$choices[$team->getId()] = $team->getName();
    	}
    	$builder->add('team_id', 'choice', array('choices' => $choices));
		
    	$form = $builder->getForm();
    	
    	$formHandler = new PlayerHandler($form, $request, $em);

        // On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process() )
        {
            return $this->redirect($this->generateUrl('aueio_club_player_view', array('id' => $player->getId())));
        }
    
    	return $this->render('AueioClubBundle:Player:new.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$player = $em->getRepository('AueioClubBundle:Player')->find($id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$id);
    	}
    	$form = $this->createForm(new PlayerType(), $player);
    
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		 
    		if ($form->isValid()) {
    			$em = $this->getDoctrine()->getEntityManager();
    			$em->persist($job);
    			$em->flush();
    
    			return $this->redirect($this->generateUrl('aueio_club_player_view', array('id' => $player->getId())));
    		}
    	}
    
    	return $this->render('AueioClubBundle:Player:new.html.twig', array('id' => $player->getId(), 'form' => $form->createView()));
    }
}
