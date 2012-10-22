<?php

namespace Aueio\ClubBundle\Controller;



use Symfony\Component\HttpFoundation\Request,
	 Symfony\Component\HttpFoundation\Response,
	 Symfony\Bundle\FrameworkBundle\Controller\Controller,
	 Symfony\Component\Security\Core\Exception\AccessDeniedException,
	 Sensio\Bundle\FrameworkExtraBundle\Configuration\Route,
	 Sensio\Bundle\FrameworkExtraBundle\Configuration\Template,
	 Symfony\Component\Validator\Constraints\DateTime,
	 Aueio\ClubBundle\Entity\Game,
	 Aueio\ClubBundle\Form\Type\GameType,
	 Aueio\ClubBundle\Form\Handler\GameHandler,
	 Aueio\ClubBundle\Entity\Role,
	 Aueio\ClubBundle\Entity\Action,
	 Aueio\ClubBundle\Entity\Config,
	 Aueio\ClubBundle\Entity\Player,
	 Doctrine\Common\Collections\ArrayCollection,
	 Doctrine\ORM\Query\Expr;
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
    	$em = $this->getDoctrine()->getEntityManager();
    	$games = $em->getRepository('AueioClubBundle:Game')->findBy(array(), array('date' => 'asc'));
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
    	
    	$form = $this->createForm(new GameType($em), $game);
    	 
    	if($request->getMethod() == 'POST' )
		{
			$form->bindRequest($request);
	
			if( $form->isValid() )
			{
				$game = $form->getData();
				$date = $game->getDate();
				$season = $em->getRepository('AueioClubBundle:Season')->findByDate($date);
				if (!$season) {
					throw new NotFoundHttpException('No season found for date '. $date->format("Y-m-d"));
				}
				$game->setSeason($season);
				$session = $this->container->get('session');
				$session->set('context.season_id', $season->getId());
				$session->set('context.season_color', $season->getId());
				
				$team_context = $this->get('context.team');
				foreach($game->getRoles() as $role){
					$team_role = $role->getTeam();
					if($team_context != $team_role){
						$virtuals = $em->getRepository('AueioClubBundle:Player')->findVirtualsByTeam($team_role);
						foreach($virtuals as $virtual){
							if(!$em->getRepository('AueioClubBundle:Action')->getTypeByPlayerByGame($virtual, $game, 'play', false)){
								if($virtual->getFirstname() == 'boy'){
									$max = 5;
								}else{
									$max = 1;
								}
								for($i = 0 ; $i < $max; $i++){
									$action = new Action();
									$action->setType('play');
									$action->setPlayer($virtual);
									$action->setSeason($season);
									$game->addAction($action);
								}
							}
							
						}
							
					}
				}
				
				$em->persist($game);
				$em->flush();
				return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
			}
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

    	$form = $this->createForm(new GameType($em), $game);
    
    	if( $request->getMethod() == 'POST' )
		{
			$form->bindRequest($request);
	
			if( $form->isValid() )
			{
				$game = $form->getData();
				$date = $game->getDate();
				$season = $em->getRepository('AueioClubBundle:Season')->findByDate($date);
				if (!$season) {
					throw new NotFoundHttpException('No season found for date '. $date->format("Y-m-d"));
				}
				$game->setSeason($season);
				$session = $this->container->get('session');
				$session->set('context.season_id', $season->getId());
				$session->set('context.season_color', $season->getColor());
				
				$team_context = $this->get('context.team');
				foreach($game->getRoles() as $role){
					$team_role = $role->getTeam();
					if($team_context != $team_role){
						$virtuals = $em->getRepository('AueioClubBundle:Player')->findVirtualsByTeam($team_role);
						foreach($virtuals as $virtual){
							if(!$em->getRepository('AueioClubBundle:Action')->getTypeByPlayerByGame($virtual, $game, 'play', false)){
								if($virtual->getFirstname() == 'boy'){
									$max = 5;
								}else{
									$max = 1;
								}
								for($i = 0 ; $i < $max; $i++){
									$action = new Action();
									$action->setType('play');
									$action->setPlayer($virtual);
									$action->setSeason($season);
									$game->addAction($action);
								}
							}
							
						}
							
					}
				}
				
				$em->persist($game);
				$em->flush();
				return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
			}
		}
    
    	return $this->render('AueioClubBundle:Game:edit.html.twig', array('game' => $game, 'form' => $form->createView()));
    }
    /**
     * @Route("/sheet/{id}", requirements={"id" = "\d+"})
     **/
    public function sheetAction(Game $game, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	//update game roles with scores from page score
    	$game_teams = array();
    	foreach($game->getRoles() as $role)
    	{
    		$team = $role->getTeam();
    		$game_teams[] = $team;
    		$score = $em->getRepository('AueioClubBundle:Action')->getScores($game, $team);
    		$role->setScore($score);
    	}
    	$player = $this->get('context.player');
    	if (!is_object($player) || !$player instanceof Player) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	if( (!in_array($player->getTeam(), $game_teams) | !$this->get('security.context')->isGranted('ROLE_LEADER'))
    			& !$this->get('security.context')->isGranted('ROLE_ADMIN') )
    	{
    		return $this->redirect($request->headers->get('referer'));
    	}
    	$volunteers = $em->getRepository('AueioClubBundle:Player')->findActionByGame($game, $game_teams[0], 'shop');
    	if(count($volunteers) == 1){
    		$volunteer = $volunteers[0];
    	}else{
    		$volunteer = null;
    	}
    	$builder = $this->createFormBuilder(array('game' => $game, 'volunteer' => $volunteer))
    			->add('game', new GameType($em), array('intention' => 'update'));
    	if ($volunteer){
    		$builder->add('cost', 'money', array('precision' => 2));
    	}
    	$form = $builder->getForm();
    	
    	if ($request->getMethod() == 'POST') {
    		$form->bindRequest($request);
    		
    		if( $form->isValid() )
    		{
    			$data = $form->getData();
    			$game = $data['game'];
	    		$roles = $game->getRoles();
	    		$score_team_0 = $roles[0]->getScore();
	    		$score_team_1 = $roles[1]->getScore();
	    		if($score_team_0 > $score_team_1){
	    			$roles[0]->setResult('WIN');
	    			$roles[1]->setResult('LOST');
	    		}elseif($score_team_1 > $score_team_0){
	    			$roles[1]->setResult('WIN');
	    			$roles[0]->setResult('LOST');
	    		}else{
	    			$roles[1]->setResult('NUL');
	    			$roles[0]->setResult('NUL');
	    		}
	    		if($volunteer){
	    			$volunteer->setCredit($data['cost']);
	    			$game_teams[0]->setCash($game_teams[0]->getCash() - $data['cost']);
	    			$em->persist($volunteer);
	    		}
	    		
	    		$em->persist($game);
	    		$em->flush();
    			return $this->redirect($this->generateUrl('aueio_club_game_view', array('id' => $game->getId())));
    		}
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
    	if($game->isLocal($teams['focus'])){
    		$action_types = array('miss', 'play', 'hurt', 'referee', 'shop');
    		$players['noshop'] = false;
    	}else{
    		$action_types = array('miss', 'play', 'hurt', 'referee');
    		$players['noshop'] = true;
    	}
    	
    	foreach( $action_types as $action_type) {
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
     * @Route("/score/{id}/{browser}/{id_goal}", requirements={"id" = "\d+", "id_goal" = "\d+", "browser" = "default|mobile"} , defaults={"id_goal" = "0", "browser" = "default"})
     **/
    public function scoreAction(Game $game, $id_goal, Request $request, $browser)
    {
    	if(new \DateTime('now') < $game->getDate() ){
    		$this->get('session')->getFlashBag()->add('notice', 'Tu es en avance cette page ne fonctionnera pas correctement !!');
    	}
    	$em = $this->getDoctrine()->getEntityManager();
       	$repository = $em->getRepository('AueioClubBundle:Action');
    	
       	$teams = $this->getTeams($game);
    	
    	$actions_focus = array();
    	$score_focus = 0;
    	$actions = $repository->findScoreActionByTeamByGame($teams['focus'], $game);
    	foreach( $actions as $action)
    	{
    		$player_id = $action->getPlayer()->getId();
    		$action_type = $action->getType();
    		if(!isset($actions_focus[$player_id])){
    			$actions_focus[$player_id] = array();
    		}
    		if(!isset($actions_focus[$player_id][$action_type])){
    			$actions_focus[$player_id][$action_type] = 0;
    		}
    		switch($action_type)
    		{
    		    case 'score':
    		    	$score_focus++;
    		    	if($action->getPlayer()->getGender() == 'F'){
    		    		$score_focus++;
    		    	}
    		    case 'save':
    		    	$actions_focus[$player_id][$action_type]++;
    				break;
    			case 'referee':
    			case 'goal':
    				$actions_focus[$player_id][$action_type] = 1;
    				break;
    			case 'play':
    				$player = $action->getPlayer();
    				if($player_id == $id_goal){
    					$actions_focus[$player_id]['goal_current'] = 1;
    					
    					$goal = $em->getRepository('AueioClubBundle:Action')->findBy(array(
    							'player'=>	$player,
    							'game'	=>	$game,
    							'type'	=>	'goal'),
    							null,
    							1);
    					if(!is_array($goal) || !count($goal) == 1){
    						$a = new Action();
	    					$a->setGame($game);
	    					$a->setPlayer($player);
	    					$a->setType('goal');
	    					$em->persist($a);
	    					$em->flush();
    					}
    				}
    				$actions_focus[$player_id]['player'] = $player;
    				break;
    			default:
    				break;
    		}
    	}
    	$actions_opponent = array();
    	$score_opponent = 0;
    	foreach($repository->findScoreActionByTeamByGame($teams['opponent'], $game) as $action)
    	{
    		$player_id = $action->getPlayer()->getId();
    		$action_type = $action->getType();
    		if(!isset($actions_opponent[$player_id])){
    			$actions_opponent[$player_id] = array();
    		}
    		if(!isset($actions_opponent[$player_id][$action_type])){
    			$actions_opponent[$player_id][$action_type] = 0;
    		}
    		switch($action_type)
    		{
    			case 'score':
    				$score_opponent++;
    				if($action->getPlayer()->getGender() == 'F'){
    					$score_opponent++;
    				}
    			case 'save':
    				$actions_opponent[$player_id][$action_type]++;
    				break;
    			case 'play':
    				if($action->getPlayer()->getFirstname() == 'goal'){
    					$actions_opponent[$player_id]['type'] = 'save';
    				}else{
    					$actions_opponent[$player_id]['type'] = 'score';
    				}
    				$actions_opponent[$player_id][$action_type]++;
    				$actions_opponent[$player_id]['player'] = $action->getPlayer();
    				break;
    			default:
    				break;
    		}
    	}

    	return $this->render("AueioClubBundle:Game:score.{$browser}.html.twig", array(	'game' => $game,
    																		'browser' => $browser,
    																		'id_goal' => $id_goal ,
    																		'teams' => $teams,
    																		'score_focus' => $score_focus,
    																		'score_opponent' => $score_opponent,
    																		'actions_focus' => $actions_focus,
    																		'actions_opponent' => $actions_opponent));
    }
    /**
     * @Route("/removeGoal/{id}/{browser}/{goal_id}", requirements={"id" = "\d+", "goal_id" = "\d+", "browser" = "default|mobile"} , defaults={"goal_id" = "0", "browser" = "default"})
     **/
    public function removeGoalAction(Game $game, $browser, $goal_id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$player = $em->getRepository('AueioClubBundle:Player')->find($goal_id);
    	if (!$player) {
    		throw $this->createNotFoundException('No player found for id '.$goal_id);
    	}
    	$actions = $em->getRepository('AueioClubBundle:Action')->findBy(array(
    			'player'=>	$player,
    			'game'	=>	$game,
    			'type'	=>	'goal'));
    	if (count($actions) > 0) {
    		$em->remove($actions[count($actions)-1]);
    		$em->flush();
    	}
    	return $this->redirect($this->generateUrl('aueio_club_game_score', array('id' => $game->getId(), 'browser' => $browser)));
    }
    
    function getTeams($game, $team_index = 0){
    	$user_team = $this->get('context.team');
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
