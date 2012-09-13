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
    	
    	$form = $this->createForm(new GameType(), $game);
    	 
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

    	$form = $this->createForm(new GameType(), $game);
    
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
    			->add('game', new GameType(), array('intention' => 'update'));
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
     * @Route("/score/{id}/{id_goal}", requirements={"id" = "\d+", "id_goal" = "\d+"} , defaults={"id_goal" = "0"})
     **/
    public function scoreAction(Game $game, $id_goal, Request $request)
    {
    	if(new \DateTime('now') < $game->getDate() ){
    		$this->get('session')->getFlashBag()->add('notice', 'Tu es en avance cette page ne fonctionnera pas correctement !!');
    	}
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
    		$attributs['isReferee'] = $repository->findTypeByPlayerByGame($player, $game, 'referee', true);
    		$attributs['action'] = $repository->findTypeByPlayerByGame($player, $game, $type, true);
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
    		$attributs['number'] = $repository->findTypeByPlayerByGame($player, $game, 'play', true);
    		$attributs['action'] = $repository->findTypeByPlayerByGame($player, $game, $type, true);
    		$attributs['type'] = $type;
    		$attributs['object'] = $player;
    		$opponents[] = $attributs;
    	}
    	
    	$score_focus = $em->getRepository('AueioClubBundle:Action')->getScores($game, $teams['focus']);
    	$score_opponent = $em->getRepository('AueioClubBundle:Action')->getScores($game, $teams['opponent']);

    	return $this->render('AueioClubBundle:Game:score.html.twig', array(	'game_id' => $game->getId(),
    																		'id_goal' => $id_goal ,
    																		'teams' => $teams,
    																		'score_focus' => $score_focus,
    																		'score_opponent' => $score_opponent,
    																		'players' => $players,
    																		'opponents' => $opponents));
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
