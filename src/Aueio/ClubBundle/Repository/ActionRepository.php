<?php

namespace Aueio\ClubBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * ActionRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class ActionRepository extends EntityRepository
{
	public function getStats($id)
	{
		$stats = array();
		foreach ( array("play", "miss", "shop", "referee") as $type ){
			$res = $this->findByType($id, $type);
			if(is_array($res)){
				$stats[$type] = count($res);
			}
		}
		foreach ( array("score", "save") as $type ){
			$actions = $this->findByType($id, $type);
			$total = 0;
			foreach($actions as $action){
				 $total += $action->getValue();
			}
			$stats[$type] = $total;
		}
		foreach ( array("win", "lost", "nul") as $result ){
			$res = $this->findPlayByResult($id, $result);
			if(is_array($res)){
				$stats[$result] = count($res);
			}
		}
		
		return $stats;
	}
	public function findByType($id, $type){
		
		return $this->createQueryBuilder('a')
		->join('a.player', 'p')
		->where('p.id = :id_player')
		->andWhere('a.type = :type')
		->setParameters(array(
				'id_player' => $id,
				'type' => $type
		))
		->getQuery()->getResult();
	}
	public function findPlayByResult($id, $result){
		return $this->createQueryBuilder('a')
		->join('a.player', 'p')
	 	->join('a.game', 'g')
	 	->leftJoin('g.roles', 'r')
	 	->where('p.id = :id_player')
		->andWhere("p.team = r.team")
		->andWhere("a.type = 'play'")
		->andWhere('r.result = :type')
		->setParameters(array(
				'id_player' => $id,
				'type' => $result
		))
		->getQuery()->getResult();
	}
	public function getScores($id_game, $id_team){
		return $this->createQueryBuilder('a')
		->join('a.game', 'g')
		->join('a.player', 'p')
	 	->join('p.team', 't')
	 	->where('g.id = :id_game')
		->andWhere("t.id = :id_team")
		->andWhere("a.type = 'score'")
		->setParameters(array(
				'id_game' => $id_game,
				'id_team' => $id_team
		))
		->getQuery()->getResult();
	}
	
}