<?php
namespace Aueio\ClubBundle\Factory;

use Symfony\Component\HttpFoundation\Session\SessionInterface,
	Doctrine\ORM\EntityManager,
	Aueio\ClubBundle\Entity\Player;

class SeasonFactory
{
	private $player;
	private $session;
	private $entity_manager;

	public function __construct(Player $player = null, EntityManager $em, SessionInterface $session)
	{
		$this->player 			= $player;
		$this->session        	= $session;
		$this->entity_manager 	= $em;
	}

	public function get()
	{
		$season = null;

		if ($seasonId = $this->session->get('context.season_id')) {
			$season = $this->entity_manager
			->getRepository('AueioClubBundle:Season')
			->find($seasonId);
		}else{
	
			if (!$season && $this->player) {
				$seasons = $this->player->getSeasons();
				if(is_array($seasons) && count($seasons) > 0)
				{
					$season = $seasons[0];
				}
			}
	
			if (!$season) {
					$season = $this->entity_manager
				->getRepository('AueioClubBundle:Season')
				->findCurrent();
			}
			
			if ($season) {
				$this->session->set('context.season_id', $season->getId());
				$this->session->set('context.season_color', $season->getColor());
			}
		}
		return $season;
	}
}
