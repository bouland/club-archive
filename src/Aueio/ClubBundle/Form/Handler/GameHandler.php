<?php 
// src/Auieo/ClubBundle/Form/Handler/GameHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Aueio\ClubBundle\Entity\Game;

class GameHandler extends FormHandler
{
	public function onSuccess(Game $game){
		$teams = $game->getTeams();
		$score_team_0 = $teams[0]->getScore();
		$score_team_1 = $teams[1]->getScore();
		if($score_team_0 > $score_team_1){
			$teams[0]->setResult('WIN');
			$teams[1]->setResult('LOST');
		}elseif($score_team_1 > $score_team_0){
			$teams[1]->setResult('WIN');
			$teams[0]->setResult('LOST');
		}else{
			$teams[1]->setResult('NUL');
			$teams[0]->setResult('NUL');
		}
		$this->em->persist($game);
		$this->em->flush();
	}
}
