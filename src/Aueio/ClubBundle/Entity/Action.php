<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aueio\ClubBundle\Entity\Entity;
use Aueio\ClubBundle\Entity\Game;
use Aueio\ClubBundle\Filter\SeasonAwareInterface;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\ActionRepository")
 * @ORM\Table(name="actions")
 */
class Action implements SeasonAwareInterface
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
     * @ORM\ManyToOne(targetEntity="Game", inversedBy="actions")
     * @ORM\JoinColumn(name="game_id", referencedColumnName="id")
    */
    private $game;
    
    /**
     * @ORM\ManyToOne(targetEntity="Player", inversedBy="actions")
     * @ORM\JoinColumn(name="player_id", referencedColumnName="id")
     */
    private $player;
    
    
    /**
     * @var string $type
     *
     * @ORM\Column(name="type", type="string", length=7)
     */
    private $type;
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(name="created", type="datetime")
     */
    private $created;
    
    public function __construct(array $options = null) {
    	$this->created = new \DateTime('now');
    }
    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="actions")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     */

    private $season;
    
    
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
    
    public function isPlay(){
    	return $this->type == 'play';
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
     * Set player
     *
     * @param Aueio\ClubBundle\Entity\Player $player
     */
    public function setPlayer(\Aueio\ClubBundle\Entity\Player $player)
    {
        $this->player = $player;
    }

    /**
     * Get player
     *
     * @return Aueio\ClubBundle\Entity\Player 
     */
    public function getPlayer()
    {
        return $this->player;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Action
     */
    public function setCreated($created)
    {
        $this->created = $created;
    
        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set season
     *
     * @param Aueio\ClubBundle\Entity\Season $season
     * @return Action
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