<?php
namespace Aueio\ClubBundle\Filter;

use Aueio\ClubBundle\Entity\Season;

Interface SeasonAwareInterface{
	public function getSeason();
	public function setSeason(Season $season = null);
}
