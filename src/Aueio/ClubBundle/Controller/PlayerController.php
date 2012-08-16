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
    	$em = $this->getDoctrine()->getEntityManager();
    	$player = $em->getRepository('AueioClubBundle:Player')->find($id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$id);
    	}
		$stats = $em->getRepository('AueioClubBundle:Action')->getStats($id);
		$stats['total'] = count($player->getTeam()->getRoles());
		
    	return $this->render('AueioClubBundle:Player:view.html.twig', array('player' => $player, 'stats' =>$stats));
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
    	if ( ($player->getId() == $this->get('security.context')->getToken()->getUser()->getId())
    			|| $this->get('security.context')->isGranted('ROLE_ADMIN')) {
	    	$em->remove($player);
	    	$em->flush();
	    	 
	    	return $this->redirect($this->generateUrl('fos_user_security_logout'));
    	}else{
    		return $this->redirect($this->get('request')->headers->get('referer'));
    	}
    }
}
