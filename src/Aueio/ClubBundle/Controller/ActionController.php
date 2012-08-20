<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aueio\ClubBundle\Entity\Action;

/**
 * @Route("/action")
 */

class ActionController extends Controller
{
	/**
     * @Route("/add/{type}/{id_game}/{id_player}/{value}", requirements={"id_game" = "\d+", "id_player" = "\d+", "type"="play|miss|shop|referee|score|save"} , defaults={"value"="empty"})
     **/
    public function addAction(Request $request, $type, $id_game, $id_player, $value)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id_game);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id_game);
    	}
    	 
    	$player = $em->getRepository('AueioClubBundle:Player')->find($id_player);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$id_player);
    	}
    	
    	$repository = $em->getRepository('AueioClubBundle:Action');
    	$query = $repository->createQueryBuilder('a')
    	->join('a.game', 'g')
    	->join('a.player', 'p')
    	->Where('a.type = :type')
    	->andWhere('a.value = :value')
    	->andWhere('p.id = :id_player')
    	->andWhere('g.id = :id_game')
    	->setParameters(array(
    			'id_player' => $id_player,
    			'id_game' => $id_game,
    	    	'type' => $type,
    	    	'value' => $value,
    	))
    	->setMaxResults(1)
    	->getQuery();

    	try {
    		$action = $query->getSingleResult();
    	} catch (\Doctrine\Orm\NoResultException $e) {
    		$action = null;
    	}
    	
    	if (!$action) {
    		$action = new Action();
	    	$action->setGame($game);
	    	$action->setPlayer($player);
	    	$action->setType($type);
    		$action->setValue($value);
	    	$em->persist($action);
	    	$em->flush();
    	}
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
	/**
     * @Route("/delete/{type}/{id_game}/{id_player}/{value}", requirements={"id_game" = "\d+", "id_player" = "\d+", "action"="play|miss|shop"} , defaults={"value"="empty"})
     **/
    public function deleteAction(Request $request, $type, $id_game, $id_player, $value)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id_game);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id_game);
    	}
    	 
    	$player = $em->getRepository('AueioClubBundle:Player')->find($id_player);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$id_player);
    	}
    	
    	$repository = $em->getRepository('AueioClubBundle:Action');
    	$query = $repository->createQueryBuilder('a')
    	->join('a.game', 'g')
    	->join('a.player', 'p')
    	->Where('a.type = :type')
    	->andWhere('a.value = :value')
    	->andWhere('p.id = :id_player')
    	->andWhere('g.id = :id_game')
    	->setParameters(array(
    			'id_player' => $id_player,
    			'id_game' => $id_game,
    	    	'type' => $type,
    	    	'value' => $value,
    	))
    	->setMaxResults(1)
    	->getQuery();

    	try {
    		$action = $query->getSingleResult();
    	} catch (\Doctrine\Orm\NoResultException $e) {
    		$action = null;
    	}
    	
    	if ($action) {
    		$em->remove($action);
    		$em->flush();
    	}
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
    /**
     * @Route("/update/{id}/{value}", requirements={"id" = "\d+", "value" = "\d+"})
     **/
    public function updateAction($id, $value, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	 
    	$action = $em->getRepository('AueioClubBundle:Action')->find($id);
    	if (!$action) {
    		throw $this->createNotFoundException('No action found for id '.$id);
    	}
    	$action->setValue($value);
    	$em->persist($action);
    	$em->flush();
    	return $this->redirect($this->get('request')->headers->get('referer'));
    }
}