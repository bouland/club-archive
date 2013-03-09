<?php

namespace Aueio\ClubBundle\Controller;

use JMS\SecurityExtraBundle\Security\Util\String;

use Doctrine\ORM\EntityManager;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aueio\ClubBundle\Entity\Action,
	Aueio\ClubBundle\Entity\Game,
	Aueio\ClubBundle\Entity\Player;

/**
 * @Route("/action")
 */

class ActionController extends Controller
{
	/**
     * @Route("/add/{type}/{game_id}/{player_id}", requirements={"game_id" = "\d+", "player_id" = "\d+", "type"="play|miss|shop|referee|goal|score|save|hurt"})
     **/
    public function addAction(Request $request, $type, $game_id, $player_id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$game = $em->getRepository('AueioClubBundle:Game')->find($game_id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$player_id);
    	}
    	
    	$player = $em->getRepository('AueioClubBundle:Player')->find($player_id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$player_id);
    	}
    	
    	$em->getRepository('AueioClubBundle:Action')->add($player, $game, $type);
    	
	    return $this->redirect($this->get('request')->headers->get('referer'));
    }
    
	/**
     * @Route("/delete/{type}/{game_id}/{player_id}", requirements={"game_id" = "\d+", "player_id" = "\d+", "action"="play|miss|shop|referee|score|save|hurt"})
     **/
    public function deleteAction(Request $request, $type, $game_id, $player_id)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$game = $em->getRepository('AueioClubBundle:Game')->find($game_id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$game_id);
    	}
    	 
    	$player = $em->getRepository('AueioClubBundle:Player')->find($player_id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$player_id);
    	}
    	
    	$em->getRepository('AueioClubBundle:Action')->delete($player, $game, $type);
    	
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
}