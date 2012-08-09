<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Entity
 * @ORM\Table(name="configs")
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Entity\ConfigRepository")
 */
class Config
{
    /**
     * @var integer $id
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;
	
    /**
     * @ORM\OneToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
    */
    private $team_default;

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set team_default
     *
     * @param Aueio\ClubBundle\Entity\Team $teamDefault
     */
    public function setTeamDefault(\Aueio\ClubBundle\Entity\Team $teamDefault)
    {
        $this->team_default = $teamDefault;
    }

    /**
     * Get team_default
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getTeamDefault()
    {
        return $this->team_default;
    }
}