<?php

namespace Aueio\ClubBundle\Controller;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use Aueio\ClubBundle\Entity\Game;
use Aueio\ClubBundle\Form\Type\GameType;
use Aueio\ClubBundle\Form\Handler\GameHandler;
use Aueio\ClubBundle\Entity\Role;
use Aueio\ClubBundle\Entity\Action;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Query\Expr;
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
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$game = new Game();
    	$game->setDate(new \DateTime('now'));
    	
    	$local = new Role();
    	$visitor = new Role();
    	$local->setType('LOCAL');
    	$visitor->setType('VISITOR');
    	$game->addRole($local);
    	$game->addRole($visitor);
    	
    	$form = $this->createForm(new GameType(), $game, array('form' => 'new'));
    	 
    	$formHandler = new GameHandler($form, $request, $em);
    	if( $formHandler->process() )
        {
    		return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
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
    	$form = $this->createForm(new GameType(), $game, array('form' => 'new'));
    
    	$formHandler = new GameHandler($form, $request, $em);
    	if( $formHandler->process() )
        {
    		return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Game:edit.html.twig', array('id' => $game->getId(), 'form' => $form->createView()));
    }
    /**
     * @Route("/result/{id}", requirements={"id" = "\d+"})
     **/
    public function resultAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id);
    	}
    	$form = $this->createForm(new GameType(), $game, array('form' => 'result'));
    
    	$formHandler = new GameHandler($form, $request, $em);
    	if( $formHandler->process() )
    	{
    		return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Game:result.html.twig', array('id' => $game->getId(), 'form' => $form->createView()));
    }
    /**
     * @Route("/action/{type}/{id_game}/{id_player}/value", requirements={"id_game" = "\d+", "id_player" = "\d+", "action"="play|miss|shop"} , defaults={"value"="empty"})
     **/
    public function actionAction(Request $request, $type, $id_game, $id_player, $value)
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
    	}else{
    		if($value == 'delete'){
    			$em->remove($action);
    			$em->flush();
    		}
    	}
    	return $this->redirect($this->generateUrl('aueio_club_game_selection', array('id' => $game->getId())));
    }
    /**
     * @Route("/selection/{id}", requirements={"id" = "\d+"})
     **/
    public function selectionAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id);
    	}
    	
    	$repository = $em->getRepository('AueioClubBundle:Player');
    	$query = $repository->createQueryBuilder('p')
    	->leftJoin('p.actions', 'a')
    	->join('a.game', 'g')
    	->where('a.type = :type')
    	->andWhere('g.id = :id')
    	->setParameters(array(
    			'type' => 'play',
    			'id' => $id,
    	))
    	->getQuery();
    	$play = $query->getResult();

    	$query = $repository->createQueryBuilder('p')
    	->leftJoin('p.actions', 'a')
    	->join('a.game', 'g')
    	->where('a.type = :type')
    	->andWhere('g.id = :id')
    	->setParameters(array(
    			'type' => 'miss',
    			'id' => $id,
    	))
    	->getQuery();
    	$miss = $query->getResult();
    	
    	$query = $repository->createQueryBuilder('p')
    	->join('p.team', 't')
    	->leftJoin('t.roles', 'r')
    	->join('r.game', 'g')
    	->leftJoin('p.actions', 'a')
    	->join('a.game', 'g2')
    	->where('g.id = :id AND g2.id != :id')
    	->setParameters(array(
    			'id' => $id,
    	))
    	->getQuery();
    	$wait1 = $query->getResult();
    	
    	$query = $repository->createQueryBuilder('p')
    	->join('p.team', 't')
    	->leftJoin('t.roles', 'r')
    	->join('r.game', 'g')
    	->leftJoin('p.actions', 'a')
    	->where('g.id = :id')
    	->andWhere('a.id IS NULL')
    	->setParameters(array(
    			'id' => $id,
    	))
    	->getQuery();
    	$wait2 = $query->getResult();
    	
    	$wait = new ArrayCollection(array_merge($wait1, $wait2));
    	return $this->render('AueioClubBundle:Game:selection.html.twig', array('game' => $game, 'wait' => $wait, 'miss' => $miss, 'play' => $play));
    }
}
