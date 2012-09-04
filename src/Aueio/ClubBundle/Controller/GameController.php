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
	public function viewAction(Game $game)
    {
        return $this->render('AueioClubBundle:Game:view.html.twig', array('game' => $game));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	//$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$em = $this->getDoctrine()->getEntityManager();
    	//$em->getFilters()->enable('season')->setParameter('season_id', $season_id);
    	$games = $em->getRepository('AueioClubBundle:Game')->findAll();
    	return $this->render('AueioClubBundle:Game:list.html.twig', array('games' => $games));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction(Game $game){
    	$em = $this->getDoctrine()->getEntityManager();
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
    	
    	$form = $this->createForm(new GameType(), $game);
    	 
    	$formHandler = new GameHandler($form, $request, $em);
    	if( $formHandler->process() )
        {
        	$this->container->get('request')->getSession()->set('season_id', $game->getSeason()->getId());
    		return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Game:new.html.twig', array(
    			'form' => $form->createView(),
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction(Game $game, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$form = $this->createForm(new GameType(), $game, array('intention' => 'edit'));
    
    	$formHandler = new GameHandler($form, $request, $em);
    	if( $formHandler->process() )
        {
    		return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Game:edit.html.twig', array('game' => $game, 'form' => $form->createView()));
    }
    /**
     * @Route("/sheet/{id}", requirements={"id" = "\d+"})
     **/
    public function sheetAction(Game $game, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();

    	$form = $this->createForm(new GameType(), $game, array('form' => 'result'));
    
    	$formHandler = new GameHandler($form, $request, $em);
    	if( $formHandler->process() )
    	{
    		return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    	}
    
    	return $this->render('AueioClubBundle:Game:sheet.html.twig', array('game' => $game, 'form' => $form->createView()));
    }
    /**
     * @Route("/selection/{id}/{team_index}", requirements={"id" = "\d+"}, defaults={"team_index" = 0})
     **/
    public function selectionAction(Game $game, $team_index, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	$team_id = $this->getTeamFocus($game, $team_index);
    	
    	$repository = $em->getRepository('AueioClubBundle:Player');
    	foreach( array('miss','shop', 'play') as $action_type) {
    		$players[$action_type] = $repository->findActionByGame($game, $team_id, $action_type);
    	}
    	$players['wait'] = $repository->findWithoutActionByGame($game, $team_id);
    	
    	return $this->render('AueioClubBundle:Game:selection.html.twig', array('game' => $game, 'players' => $players));
    }
    /**
     * @Route("/score/{id}/{id_goal}", requirements={"id" = "\d+", "id_goal" = "\d+"} , defaults={"id_goal" = "0"})
     **/
    public function scoreAction(Game $game, $id_goal, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
       	
    	$players = array();
    	$repository = $em->getRepository('AueioClubBundle:Action');
    	
    	$id_team = $this->getTeamFocus($game);
    	$game_players = $game->getPlayers($id_team);
    	foreach ($game_players as $player){
    		if($id_goal == 0){
    			if($player->getPosition() == 'GOAL'){
    				$id_goal = $player->getId();
    				$type = 'save';
    			}else{
    				$type = 'score';
    			}
    		}else{
	    		if($player->getId() == $id_goal){
	    			$type = 'save';
	    		}else{
	    			$type = 'score';
	    		}
    		}
    		$attributs = array();
    		$attributs['isReferee'] = $repository->findByType($player->getId(),'referee');
    		$attributs['action'] = $repository->findByType($player->getId(),$type);
    		$attributs['object'] = $player;
    		$players[] = $attributs;
    	}
    	$scores = array();
    	foreach($game->getTeams() as $team){
    		$actions = $em->getRepository('AueioClubBundle:Action')->getScores($game->getId(), $team->getId());
    		$total = 0;
    		foreach ($actions as $action){
    			$total += $action->getValue();
    		}
    		$scores[] = $total;
    	}
    	
    	return $this->render('AueioClubBundle:Game:score.html.twig', array('game' => array('id' => $game->getId(), 'roles' => $game->getRoles(), 'scores' => $scores), 'id_goal' => $id_goal , 'players' => $players));
    }
    
    function getTeamFocus($game, $team_index = 0){
    	$game_teams = $game->getTeams()->toArray();
    	
    	$team = $this->get('security.context')->getToken()->getUser()->getTeam();
    	if (!$team) {
    		throw $this->createNotFoundException('No team found');
    	}
    	if (in_array($team, $game_teams)){
    		return $team->getId();
    	} else{
    		return $game_teams[$team_index]->getId();
    	}
    }
}
