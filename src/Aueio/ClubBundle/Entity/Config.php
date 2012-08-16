<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\ConfigRepository")
 * @ORM\Table(name="configs")
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
     * @var string $secret
     *
     * @ORM\Column(name="secret", type="string", length="255" )
     */

    private $secret;
    
    
    /**
     * Assert\True()
     */
    public function isSecrets()
    {
    	$secret = $this->getSecrets();
    	return is_array($secret);
    }
    
	/**
     * Get secret
     *
     * @return array
     */
    public function getSecrets()
    {
    	
        return explode(",", $this->secret);
    }
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

    /**
     * Set secret
     *
     * @param string $secret
     */
    public function setSecret($secret)
    {
        $this->secret = $secret;
    }

    /**
     * Get secret
     *
     * @return string 
     */
    public function getSecret()
    {
        return $this->secret;
    }
}