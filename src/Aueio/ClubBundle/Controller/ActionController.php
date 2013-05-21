<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;

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
     * @Route("/register")
     **/
    public function registerAction(Request $request){
        
        $action = $request->query->get('action');
        $type = $request->query->get('type');
        $idPlayer = $request->query->get('idPlayer');
        $idGame = $request->query->get('idGame');
        
        if(empty($action)){
            return new JsonResponse(array('error' => 'empty action'), 400);
        }
        if(empty($type)){
            return new JsonResponse(array('error' => 'empty type'), 400);
        }
        if(empty($idPlayer)){
            return new JsonResponse(array('error' => 'empty idPlayer'), 400);
        }
        if(empty($idGame)){
            return new JsonResponse(array('error' => 'empty idGame'), 400);
        }
        
        $em = $this->getDoctrine()->getEntityManager();
        $game = $em->getRepository('AueioClubBundle:Game')->find($idGame);
        if(empty($game)){
            return new JsonResponse(array('error' => 'no Game found with id ' . $idGame), 400);
        }
        $player = $em->getRepository('AueioClubBundle:Player')->find($idPlayer);
        if(empty($player)){
            return new JsonResponse(array('error' => 'no player found with id ' . $idPlayer), 400);
        }
        
        $listTypeAllowed = array("play","miss","shop","referee","goal","score","save","hurt");
        if( ! in_array($type, $listTypeAllowed) ){
            return new JsonResponse(array('error' => 'invalid action ' . $type), 400);
        }
        
        if($player->getGender() == 'F'){
            $score = 2;
        }else{
            $score = 1;
        }
        
        if( $action == 'add'){
            $value = 1;
            $em->getRepository('AueioClubBundle:Action')->add($player, $game, $type);
        }elseif( $action == 'del' ){
            $score *= -1;
            $value = -1;
            $em->getRepository('AueioClubBundle:Action')->delete($player, $game, $type);
        } else {
            return new JsonResponse(array('error' => 'invalid action ' . $action), 400);
        }
        
        return  new JsonResponse(array('value' => $value, 'score' => $score), 200);
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