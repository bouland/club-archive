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
     * @Route("/add/{type}/{game_id}/{player_id}", requirements={"game_id" = "\d+", "player_id" = "\d+", "type"="play|miss|shop|referee|score|save|hurt"})
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
	    	$repository = $em->getRepository('AueioClubBundle:Action');
	    	$query = $repository->createQueryBuilder('a')
	    	->join('a.game', 'g')
	    	->join('a.player', 'p')
	    	->Where('a.type = :type')
	    	->andWhere('p.id = :player_id')
	    	->andWhere('g.id = :game_id')
	    	->setParameters(array(
	    			'player_id' => $player->getId(),
	    			'game_id' => $game->getId(),
	    	    	'type' => $type,
	    	))
	    	->setMaxResults(1)
	    	->getQuery();
	
	    	try {
	    		$action = $query->getSingleResult();
	    	} catch (\Doctrine\Orm\NoResultException $e) {
	    		$action = null;
	    	}
    	}else{
    		$action = null;
    	}
    	
    	if (!$action) {
    		$action = new Action();
	    	$action->setGame($game);
	    	$action->setPlayer($player);
	    	$action->setType($type);
	    	$em->persist($action);
	    	$em->flush();
    	}
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
	/**
     * @Route("/delete/{type}/{game_id}/{player_id}", requirements={"game_id" = "\d+", "player_id" = "\d+", "action"="play|miss|shop"})
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
    	
    	$repository = $em->getRepository('AueioClubBundle:Action');
    	$actions = $repository->createQueryBuilder('a')
    	->join('a.game', 'g')
    	->join('a.player', 'p')
    	->Where('a.type = :type')
    	->andWhere('p.id = :player_id')
    	->andWhere('g.id = :game_id')
    	->setParameters(array(
    			'player_id' => $player_id,
    			'game_id' => $game_id,
    	    	'type' => $type,
    	))
    	->getQuery()->getResult();

    	
    	
    	if (count($actions) > 0) {
    		$em->remove($actions[count($actions)-1]);
    		$em->flush();
    	}
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
}