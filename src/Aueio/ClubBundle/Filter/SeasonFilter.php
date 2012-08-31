<?php
namespace Aueio\ClubBundle\Filter;

use Doctrine\ORM\Mapping\ClassMetaData,
	Doctrine\ORM\Query\Filter\SQLFilter,
	Doctrine\ORM\EntityManager,
	Doctrine\DBAL\Types\Type as DBALType;
	
class SeasonFilter extends SQLFilter
{
/*
	final public function __construct(EntityManager $em)
	{
		parent::__construct($em);
		
		$config = $em->getRepository('AueioClubBundle:Config')->find(1);
		if($config){
			$season = $config->getSeasonCurrent();
		}else{
			$season = $em->getRepository('AueioClubBundle:Season')->findCurrent();
		}
		
		if($season){
			$season_id = $season->getId();
		}else{
			$season_id = 1;
		}
	
		$this->setParameter('season_id',$season_id, DBALType::INTEGER);
		
		//$this->setParameter('season_id',1, DBALType::INTEGER);
	}
*/
	public function addFilterConstraint(ClassMetadata $targetEntity, $targetTableAlias)
	{
		// Check if the entity implements the LocalAware interface
		$i = $targetEntity->reflClass->getInterfaceNames();
		if (!in_array("Aueio\ClubBundle\Filter\SeasonAwareInterface", $i)) {
			return "";
		}

		return $targetTableAlias . '.season_id = ' . $this->getParameter('season_id'); // getParameter applies quoting automatically
	}
}