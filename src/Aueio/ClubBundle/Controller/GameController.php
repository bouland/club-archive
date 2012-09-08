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

    	$form = $this->createForm(new GameType(), $game, array('intention' => 'result'));
    
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
    	
    	$teams = $this->getTeams($game, $team_index);
    	
    	$repository = $em->getRepository('AueioClubBundle:Player');
    	foreach( array('miss','shop', 'play', 'hurt', 'referee') as $action_type) {
    		$players[$action_type] = $repository->findActionByGame($game, $teams['focus'], $action_type);
    	}
    	$players['wait'] = $repository->findWithoutActionByGame($game, $teams['focus']);
    	
    	$positions =  array(array(),array(),array(),array(),array());
		if(!empty($players['play']))
    	{
    		foreach($players['play'] as $player)
    		{
    			switch($player->getPosition()){
    				case "BACK":
    					$positions[0][] = $player;
    					break;
    				case "WING":
    					$positions[1][] = $player;
    					break;
    				case "PIVOT":
    					$positions[2][] = $player;
    					break;
    				case "CENTER":
    					$positions[3][] = $player;
    					break;
    				case "GOAL":
    					$positions[4][] = $player;
    					break;
    				default:
    					$positions[0][] = $player;
    			}	
    		}
	    	
    	}
    	$players['positions'] = $positions;
    	return $this->render('AueioClubBundle:Game:selection.html.twig', array('game' => $game, 'players' => $players));
    }
    /**
     * @Route("/score/{id}/{id_goal}", requirements={"id" = "\d+", "id_goal" = "\d+"} , defaults={"id_goal" = "0"})
     **/
    public function scoreAction(Game $game, $id_goal, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
       	
    	$players = array();
    	$opponents = array();
    	$repository = $em->getRepository('AueioClubBundle:Action');
    	
    	$teams = $this->getTeams($game);
    	foreach ($game->getPlayers($teams['focus']) as $player){
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
    		$attributs['isReferee'] = $repository->findByGameByType($player->getId(), $game->getId(), 'referee', true);
    		$attributs['action'] = $repository->findByGameByType($player->getId(), $game->getId(), $type, true);
    		$attributs['type'] = $type;
    		$attributs['object'] = $player;
    		$players[] = $attributs;
    	}
    	foreach ($em->getRepository('AueioClubBundle:Player')->findVirtualsByTeam($teams['opponent']) as $player){
    		if($player->getFirstname() == 'goal'){
    			$type = 'save';
    		}else{
    			$type = 'score';
    		}
    		
    		$attributs = array();
    		$attributs['number'] = $repository->findByGameByType($player->getId(), $game->getId(), 'play', true);
    		$attributs['action'] = $repository->findByGameByType($player->getId(), $game->getId(), $type, true);
    		$attributs['type'] = $type;
    		$attributs['object'] = $player;
    		$opponents[] = $attributs;
    	}
    	
    	$score_focus = $em->getRepository('AueioClubBundle:Action')->getScores($game->getId(), $teams['focus']);
    	$score_opponent = $em->getRepository('AueioClubBundle:Action')->getScores($game->getId(), $teams['opponent']);

    	return $this->render('AueioClubBundle:Game:score.html.twig', array(	'game_id' => $game->getId(),
    																		'id_goal' => $id_goal ,
    																		'teams' => $teams,
    																		'score_focus' => $score_focus,
    																		'score_opponent' => $score_opponent,
    																		'players' => $players,
    																		'opponents' => $opponents));
    }
    
    function getTeams($game, $team_index = 0){
    	$user_team = $this->get('security.context')->getToken()->getUser()->getTeam();
    	if (!$user_team) {
    		throw $this->createNotFoundException('No team found');
    	}
    	$game_teams = $game->getTeams()->toArray();
    	foreach( $game_teams as $team)
    	{
	    	if ($team == $user_team)
	    	{
	    		$teams['focus'] = $team;
	    	}
	    	else{
	    		$teams['opponent'] = $team;
	    	}
	    	
    	}
    	if($teams['focus'] == $teams['opponent']){
    		$teams['focus'] = $game_teams[$team_index];
    	}
    	return $teams;
    }
}
