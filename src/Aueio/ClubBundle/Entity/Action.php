<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aueio\ClubBundle\Entity\Entity;
use Aueio\ClubBundle\Entity\Game;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Entity
 * @ORM\Table(name="actions")
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Entity\ActionRepository")
 */
class Action
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
     * @ORM\Column(name="type", type="string", length="5" )
     */
    private $type;
    
    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length="10", nullable="true")
     */
    private $value;
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    
    public function __construct(array $options = null) {
    	$this->date = new \DateTime('now');
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
     * Set date
     *
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }

    /**
     * Get date
     *
     * @return date 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set game
     *
     * @param Aueio\ClubBundle\Entity\Game $game
     */
    public function setGame(\Aueio\ClubBundle\Entity\Game $game)
    {
        $this->game = $game;
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
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }
    public function isPlay(){
    	return $this->type == 'play';
    }
}