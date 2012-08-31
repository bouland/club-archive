<?php
namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aueio\ClubBundle\Filter\SeasonAwareInterface;
/**
 * Aueio\ClubBundle\Entity\Role
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\RoleRepository")
 * @ORM\Table(name="roles")
 */
class Role  implements SeasonAwareInterface
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
	 * @var string $type
	 *
	 * @ORM\Column(name="type", type="string", length=7)
	 */
	private $type;
	
	/**
	 * @var int $score
	 *
	 * @ORM\Column(name="score", type="integer", nullable=true)
	 */
	private $score;
	
	/**
	 * @var string $result
	 *
	 * @ORM\Column(name="result", type="string", length=5, nullable=true)
	 */
	private $result;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Game", cascade={"persist", "remove"})
	 * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
	 */
	private $game;

	/**
	 * @ORM\ManyToOne(targetEntity="Team")
	 * @ORM\JoinColumn(name="team_id", referencedColumnName="id")
	 */
	private $team;
	
	/**
	 * @ORM\ManyToOne(targetEntity="Season", inversedBy="roles")
	 * @ORM\JoinColumn(name="season_id", referencedColumnName="id", nullable=false)
	 */
	private $season;
	
	public function __toString(){
		return "Role " . $this->id . " " . $this->type;
	}

	
	public function isLocal(){
		return ($this->type == 'LOCAL');
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
     * Set score
     *
     * @param integer $score
     */
    public function setScore($score)
    {
        $this->score = $score;
    }

    /**
     * Get score
     *
     * @return integer 
     */
    public function getScore()
    {
        return $this->score;
    }

    /**
     * Set result
     *
     * @param string $result
     */
    public function setResult($result)
    {
        $this->result = $result;
    }

    /**
     * Get result
     *
     * @return string 
     */
    public function getResult()
    {
        return $this->result;
    }

    /**
     * Set game
     *
     * @param Aueio\ClubBundle\Entity\Game $game
     */
    public function setGame(\Aueio\ClubBundle\Entity\Game $game)
    {
        $this->game = $game;
        $this->season = $game->getSeason();
    }

    /**
     * Get game
     *
     * @return Aueio\ClubBundle\Entity\Game 
     */
    public function getGame()
    {
        return $this->game;
    }

    /**
     * Set team
     *
     * @param Aueio\ClubBundle\Entity\Team $team
     */
    public function setTeam(\Aueio\ClubBundle\Entity\Team $team)
    {
        $this->team = $team;
    }

    /**
     * Get team
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getTeam()
    {
        return $this->team;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set season
     *
     * @param Aueio\ClubBundle\Entity\Season $season
     * @return Role
     */
    public function setSeason(\Aueio\ClubBundle\Entity\Season $season = null)
    {
        $this->season = $season;
    
        return $this;
    }

    /**
     * Get season
     *
     * @return Aueio\ClubBundle\Entity\Season 
     */
    public function getSeason()
    {
        return $this->season;
    }
}