<?php

namespace Aueio\ClubBundle\Controller;

use Aueio\ClubBundle\Entity\Relation;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/relation")
 */

class RelationController extends Controller
{
	/**
	 * @Route("/{id_player}/score/{id_game}/{value}", requirements={"id_player" = "\d+","id_game" = "\d+"}, defaults={"value" = "1"})
	 */
	public function scoreAction($id_player, $id_game, $value)
	{
		$em = $this->getDoctrine()->getEntityManager();
		
		$player = $em->getRepository('AueioClubBundle:Player')->find($id_player);
		if (!$player) {
			throw $this->createNotFoundException('No player found for id '.$id);
		}
		
		$game = $em->getRepository('AueioClubBundle:Game')->find($id_game);
		if (!$game) {
			throw $this->createNotFoundException('No game found for id '.$id);
		}
		
		$relation = new Relation();
		
		
		$relation->setIdOne($player);
		$relation->setIdTwo($game);
		
		$relation->setName('score');
		$relation->setValue(value);
		
		
		$em->persist($relation);
		$em->flush();
		
		return $this->redirect($this->generateUrl('_welcome'));
	}
	
}