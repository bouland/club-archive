<?php 
// src/Auieo/ClubBundle/Form/Handler/GameHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Game;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GameHandler extends FormHandler
{
	public function process($param)
	{
		if( $this->request->getMethod() == 'POST' )
		{
			$this->form->bindRequest($this->request);
	
			if( $this->form->isValid() )
			{
				return $this->onSuccess($this->form->getData(),$param);
			}
		}
	
		return false;
	}
	public function onSuccess(Game $game, $create = true ){
		if($create) {
			$date = $game->getDate();
			$season = $this->em->getRepository('AueioClubBundle:Season')->findByDate($date);
			if (!$season) {
				throw new NotFoundHttpException('No season found for date '. $date->format("Y-m-d"));
			}
			$game->setSeason($season);
			$roles = $game->getRoles();
			$team = $this->get('context.team');
			foreach($roles as $role){
				if($team != $role->getTeam()){
					$virtuals = $em->getRepository('AueioClubBundle:Player')->findVirtualsByTeam($team);
					foreach($virtuals as $virtual){
						if($virtual->getFirstname() == 'boy'){
							$max = 7;
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
			
		}else{
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
		}
		$this->em->persist($game);
		$this->em->flush();
		return true;
	}

}
