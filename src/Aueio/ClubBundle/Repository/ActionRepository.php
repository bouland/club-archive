<?php

namespace Aueio\ClubBundle\Repository;

use Doctrine\ORM\EntityRepository,
	Aueio\ClubBundle\Entity\Game,
	Aueio\ClubBundle\Entity\Player,
	Aueio\ClubBundle\Entity\Team;

/**
 * ActionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActionRepository extends EntityRepository
{
	
	public function getPlayerStats(Player $player)
	{
		$stats = array();
		foreach ( array("play", "miss", "shop", "referee","score", "save") as $type ){
			$stats[$type] = $this->findTypeByPlayer($player, $type, true);
		}
		foreach ( array("win", "lost", "nul") as $result ){
			$stats[$result] = $this->findPlayResultByPlayer($player, $result, true);
		}
		return $stats;
	}
	public function findTypeByPlayer(Player $player, $type, $count = false){
		$builder = $this->createQueryBuilder('a')
		->join('a.player', 'p')
		->join('a.game', 'g')
		->where('p.id = :id_player')
		->andWhere('a.type = :type')
		->andWhere('g.date <= :now')
		->setParameters(array(
				'id_player' => $player->getId(),
				'type' => $type,
				'now' => new \Datetime('now')
		));
		if($count){
			$builder->select('count(a.id)')->setMaxResults(1);
			return $builder->getQuery()->getSingleScalarResult();
		}else{
			return $builder->getQuery()->getResult();
		}
	}
	public function findTypeByPlayerByGame(Player $player, Game $game,  $type, $count = false){
		$builder = $this->createQueryBuilder('a')
		->join('a.player', 'p')
		->join('a.game', 'g')
		->where('p.id = :id_player')
		->andWhere('g.date <= :now')
		->andWhere('g.id = :id_game')
		->andWhere('a.type = :type')
		->setParameters(array(
				'id_player' => $player->getId(),
				'id_game' => $game->getId(),
				'type' => $type,
				'now' => new \Datetime('now')
		));
		if($count){
			$builder->select('count(a.id)')->setMaxResults(1);
			return $builder->getQuery()->getSingleScalarResult();
		}else{
			return $builder->getQuery()->getResult();
		}
	}
	public function getTypeByPlayerByGame(Player $player, Game $game,  $type, $count = false){
		$builder = $this->createQueryBuilder('a')
		->join('a.player', 'p')
		->join('a.game', 'g')
		->where('p.id = :id_player')
		->andWhere('g.id = :id_game')
		->andWhere('a.type = :type')
		->setParameters(array(
				'id_player' => $player->getId(),
				'id_game' => $game->getId(),
				'type' => $type,
		));
		if($count){
			$builder->select('count(a.id)')->setMaxResults(1);
			return $builder->getQuery()->getSingleScalarResult();
		}else{
			return $builder->getQuery()->getResult();
		}
	}
	public function findPlayResultByPlayer(Player $player, $result, $count = false){
		$builder = $this->createQueryBuilder('a')
		->join('a.player', 'p')
	 	->join('a.game', 'g')
	 	->leftJoin('g.roles', 'r')
	 	->where('p.id = :id_player')
		->andWhere('g.date <= :now')
		->andWhere("p.team = r.team")
		->andWhere("a.type = 'play'")
		->andWhere('r.result = :type')
		->setParameters(array(
				'id_player' => $player->getId(),
				'type' => $result,
				'now' => new \Datetime('now')		
		));
		if($count){
			$builder->select('count(a.id)')->setMaxResults(1);
			return $builder->getQuery()->getSingleScalarResult();
		}else{
			return $builder->getQuery()->getResult();
		}
	}
	public function getScores(Game $game, Team $team){
		 $boy = $this->createQueryBuilder('a')
					->select('count(a.id)')
					->join('a.game', 'g')
					->join('a.player', 'p')
					->join('p.team', 't')
					->where('g.id = :game_id')
					->andWhere("a.type = 'score'")
					->andWhere("t.id = :team_id")
					->andWhere("p.gender = 'M'")
					->setMaxResults(1)
					->setParameters(array(
							'game_id' => $game->getId(),
							'team_id' => $team->getId()
					))->getQuery()->getSingleScalarResult();
		 
		 $girl = $this->createQueryBuilder('a')
					 ->select('count(a.id)')
					 ->join('a.game', 'g')
					 ->join('a.player', 'p')
					 ->join('p.team', 't')
					 ->where('g.id = :game_id')
					 ->andWhere("a.type = 'score'")
					 ->andWhere("t.id = :team_id")
					 ->andWhere("p.gender = 'F'")
					 ->setMaxResults(1)
					 ->setParameters(array(
					 		'game_id' => $game->getId(),
					 		'team_id' => $team->getId()
					 ))->getQuery()->getSingleScalarResult();
		 return $boy + $girl * 2;
	}
	
}