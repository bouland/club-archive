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
use Aueio\ClubBundle\Entity\Config;
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
    	
    	foreach ($game->getActions() AS $action) {
    		$em->remove($action);
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
    	$date = new \DateTime('now');
    	$game->setDate($date);
    	$game->setStartTime(\DateTime::createFromFormat("Ymd\THis\Z",$date->format("Ymd"). "T200000Z"));
    	$game->setEndTime(\DateTime::createFromFormat("Ymd\THis\Z",$date->format("Ymd"). "T223000Z"));
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
    
    	return $this->render('AueioClubBundle:Game:edit.html.twig', array('game' => $game, 'form' => $form->createView()));
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
    
    	return $this->render('AueioClubBundle:Game:result.html.twig', array('game' => $game, 'form' => $form->createView()));
    }
    /**
     * @Route("/selection/{id}", requirements={"id" = "\d+"})
     **/
    public function selectionAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = $em->getRepository('AueioClubBundle:Config')->find(1);
    	if (!$config) {
    		throw $this->createNotFoundException('No config found for id '.$id);
    	}
    	$game = $em->getRepository('AueioClubBundle:Game')->find($id);
    	if (!$game) {
    		throw $this->createNotFoundException('No game found for id '.$id);
    	}
    	$play = array();
    	$miss = array();
    	$shop = array();
    	
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
    	$result = $query->getResult();
    	if(is_array($result)){
    		$play = $result;
    	}

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
    	$result = $query->getResult();
    	if(is_array($result)){
    		$miss = $result;
    	}
    	
    	$query = $repository->createQueryBuilder('p')
    	->leftJoin('p.actions', 'a')
    	->join('a.game', 'g')
    	->where('a.type = :type')
    	->andWhere('g.id = :id')
    	->setParameters(array(
    			'type' => 'shop',
    			'id' => $id,
    	))
    	->getQuery();
    	$result = $query->getResult();
    	if(is_array($result)){
    		$shop = $result;
    	}
    	$query = $repository->createQueryBuilder('p')
    	//->addSelect('count(p)')
    	->join('p.team', 't')
    	->leftJoin('t.roles', 'r')
    	->join('r.game', 'g')
    	//->leftJoin('p.actions', 'a')
    	//->join('a.game', 'g2')
    	->where('g.id = :id_game')
    	->andWhere('t.id = :id_team')
    	->setParameters(array(
    			'id_game' => $id,
    			'id_team' => $config->getTeamDefault()->getId(),
    	))
    	->getQuery();
    	$result = $query->getResult();
    	if(is_array($result)){
    		$wait = $result;
    	}

    	/*
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
    	
    	
    	*/
    	
    	return $this->render('AueioClubBundle:Game:selection.html.twig', array('game' => $game, 'wait' => $wait, 'miss' => $miss,'shop' => $shop, 'play' => $play));
    }
}
