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
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=true)
    */
    private $team_focus;

    /**
     * @ORM\OneToOne(targetEntity="Season")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id", nullable=true)
     */
    private $season_current;
    
    /**
     * @var string $secret
     *
     * @ORM\Column(name="secret", type="string", length=255 )
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
     * Set team_focus
     *
     * @param Aueio\ClubBundle\Entity\Team $teamDefault
     */
    public function setTeamFocus(\Aueio\ClubBundle\Entity\Team $teamDefault)
    {
        $this->team_focus = $teamDefault;
    }
	public function removeTeamFocus(){
		$this->team_focus = null;
	}
    /**
     * Get team_focus
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getTeamFocus()
    {
        return $this->team_focus;
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

    /**
     * Set season_current
     *
     * @param Aueio\ClubBundle\Entity\Season $seasonCurrent
     * @return Config
     */
    public function setSeasonCurrent(\Aueio\ClubBundle\Entity\Season $seasonCurrent = null)
    {
        $this->season_current = $seasonCurrent;
    
        return $this;
    }

    /**
     * Get season_current
     *
     * @return Aueio\ClubBundle\Entity\Season 
     */
    public function getSeasonCurrent()
    {
        return $this->season_current;
    }
}