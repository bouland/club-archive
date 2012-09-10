<?php

namespace Aueio\ClubBundle\Repository;

use Doctrine\ORM\EntityRepository,
	Doctrine\ORM\Query\Expr,
	Aueio\ClubBundle\Entity\Team,
	Aueio\ClubBundle\Entity\Game;

/**
 * PlayerRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class PlayerRepository extends EntityRepository
{
	public function findActionByGame(Game $game, Team $team, $action_type){
		return $this->createQueryBuilder('p')
			->join('p.team', 't')
			->leftJoin('p.actions', 'a')
			->join('a.game', 'g')
			->where('a.type = :type')
			->andWhere('g.id = :id_game')
			->andWhere('t.id = :id_team')
			->setParameters(array(
					'id_game' => $game->getId(),
					'id_team' => $team->getId(),
					'type' => $action_type,
			))
			->getQuery()->getResult();
	}
	public function findWithoutActionByGame(Game $game, Team $team)
	{
		$em = $this->getEntityManager();
		$em->getFilters()->disable('season');
		$query = $em->createQuery("SELECT p
FROM Aueio\ClubBundle\Entity\Player p
INNER JOIN Aueio\ClubBundle\Entity\Team t WHERE t = p.team 
LEFT JOIN Aueio\ClubBundle\Entity\Role r WHERE r.team = t
INNER JOIN Aueio\ClubBundle\Entity\Game g WHERE g = r.game
LEFT JOIN Aueio\ClubBundle\Entity\Action a WITH (a.player = p AND a.game = g)
WHERE (g.season MEMBER OF p.seasons
	AND g.id = {$game->getId()}
	AND t.id = {$team->getId()}
	AND p.firstname != 'girl'
	AND p.firstname != 'goal'
	AND p.firstname != 'boy'
	AND a.id IS NULL)");
		return $query->getResult();
/*		return $this->getEntityManager()->getConnection()->fetchAll("SELECT p.id, p.firstname, p.lastname FROM players p
LEFT JOIN seasons_players s 
ON p.id = s.player_id
INNER JOIN teams t
ON p.team_id = t.id
LEFT JOIN roles r
ON r.team_id = t.id
INNER JOIN games g
ON r.game_id = g.id
LEFT JOIN actions a
ON (a.player_id = p.id AND a.game_id = g.id)
WHERE (s.season_id = g.season_id AND g.id = {$game->getId()} AND t.id = {$team->getId()} AND a.id IS NULL
	AND p.firstname != 'girl'
	AND p.firstname != 'goal'
	AND p.firstname != 'boy');");
		*/
	}
	public function findTeamNextGameEmails(Team $team, Game $game)
	{
		$em = $this->getEntityManager();
		$em->getFilters()->disable('season');
		$query = $em->createQuery("SELECT p.firstname, p.lastname, p.email
				FROM Aueio\ClubBundle\Entity\Player p
				INNER JOIN Aueio\ClubBundle\Entity\Team t WHERE t = p.team
				LEFT JOIN Aueio\ClubBundle\Entity\Role r WHERE r.team = t
				INNER JOIN Aueio\ClubBundle\Entity\Game g WHERE g = r.game
				LEFT JOIN Aueio\ClubBundle\Entity\Action a WITH (a.player = p AND a.game = g)
				WHERE (g.season MEMBER OF p.seasons
				AND g.id = {$game->getId()}
				AND t.id = {$team->getId()}
				AND p.firstname != 'girl'
				AND p.firstname != 'goal'
				AND p.firstname != 'boy'
				AND a.id IS NULL)");
		return $query->getResult();
		/*
		return $this->getEntityManager()->getConnection()->fetchAll("SELECT p.email FROM players p
				LEFT JOIN seasons_players s
				ON p.id = s.player_id
				INNER JOIN teams t
				ON p.team_id = t.id
				LEFT JOIN roles r
				ON r.team_id = t.id
				INNER JOIN games g
				ON r.game_id = g.id
				LEFT JOIN actions a
				ON (a.player_id = p.id AND a.game_id = g.id)
				WHERE (s.season_id = g.season_id AND g.id = {$game->getId()} AND t.id = {$team->getId()} AND a.id IS NULL
				AND p.firstname != 'girl'
				AND p.firstname != 'goal'
			AND p.firstname != 'boy');");
			*/
	}
	public function findSeasonTeamContacts(Team $team, $season_id){
		return $this->createQueryBuilder('p')
		->leftJoin('p.seasons', 's')
		->leftJoin('p.leads', 't')
		->where('s.id = :id_season')
		->andWhere('t.id = :id_team')
		->setParameters(array(
				'id_season' => $season_id,
				'id_team' => $team->getId(),
		))
		->getQuery()->getResult();
	}
	public function findSeasonTeamEmails(Team $team, $season_id){
		return $this->createQueryBuilder('p')
		->select('p.email')
		->leftJoin('p.seasons', 's')
		->leftJoin('p.leads', 't')
		->where('s.id = :id_season')
		->andWhere('t.id = :id_team')
		->setParameters(array(
				'id_season' => $season_id,
				'id_team' => $team->getId(),
		))
		->getQuery()->getResult();
	}
	public function findSeasonTeamMembers(Team $team, $season_id){
		return $this->createQueryBuilder('p')
		->leftJoin('p.seasons', 's')
		->join('p.team', 't')
		->where('t.id = :id_team')
		->andWhere('s.id = :id_season')
		->andWhere("p.firstname != 'girl'")
		->andWhere("p.firstname != 'goal'")
		->andWhere("p.firstname != 'boy'")
		->orderBy('p.firstname')
		->setParameters(array(
				'id_season' => $season_id,
				'id_team' => $team->getId(),
		))
		->getQuery()->getResult();
	}
	public function findVirtualsByTeam($team){
		return $this->createQueryBuilder('p')
		->join('p.team', 't')
		->where('t.id = :id_team')
		->andWhere("p.firstname = 'girl' OR p.firstname = 'goal' OR p.firstname = 'boy'")
		->orderBy('p.firstname')
		->setParameters(array(
				'id_team' => $team->getId(),
		))
		->getQuery()->getResult();
	}
	public function findBySeason($season_id){
		return $this->createQueryBuilder('p')
		->leftJoin('p.seasons', 's')
		->where('s.id = :id_season')
		->orderBy('p.firstname')
		->setParameters(array(
				'id_season' => $season_id,
		))
		->getQuery()->getResult();
	}

}