<?php

namespace Aueio\ClubBundle\Controller;

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
    	
    	if($type != 'score' && $type != 'save' && $type != 'play')
    	{
	    	$actions = $em->getRepository('AueioClubBundle:Action')->findBy(array(	'player'=>	$player,
	    																				'game'	=>	$game,
	    																				'type'	=>	$type), null, 1);
	    	if(is_array($actions) && count($actions) == 1){
	    		return $this->redirect($this->get('request')->headers->get('referer'));
	    	}
    	}
    	$action = new Action();
	    $action->setGame($game);
	    $action->setPlayer($player);
	    $action->setType($type);
	    $em->persist($action);
	    $em->flush();
    	
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
    	$this->remove($player, $game, $type, $em);
    	switch($type){
    		case "play":
    			$this->remove($player, $game, 'referee', $em);
    			$this->remove($player, $game, 'shop', $em);
    			$this->remove($player, $game, 'goal', $em);
    			break;
    		case "miss":
    			$this->remove($player, $game, 'hurt', $em);
    			break;
    	}
    	
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
    public function remove($player, $game, $type, $em){
    	$actions = $em->getRepository('AueioClubBundle:Action')->findBy(array(
    							'player'=>	$player,
    							'game'	=>	$game,
    							'type'	=>	$type));
    	if (count($actions) > 0) {
    		$em->remove($actions[count($actions)-1]);
    		$em->flush();
    	}
    	
    }
}