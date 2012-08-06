<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aueio\ClubBundle\Entity\Entity;
use Aueio\ClubBundle\Entity\Game;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Entity\RelationRepository")
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
     * @ORM\ManyToOne(targetEntity="Entity")
    */
    private $entity;

    /**
     * @ORM\ManyToOne(targetEntity="Game")
    */
    private $game;
    
    /**
     * @var string $number
     *
     * @ORM\Column(name="number", type="integer")
     */
    private $number;
    
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
     * Set number
     *
     * @param integer $number
     */
    public function setNumber($number)
    {
        $this->number = $number;
    }

    /**
     * Get number
     *
     * @return integer 
     */
    public function getNumber()
    {
        return $this->number;
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
     * Set entity
     *
     * @param Aueio\ClubBundle\Entity\Entity $entity
     */
    public function setEntity(\Aueio\ClubBundle\Entity\Entity $entity)
    {
        $this->entity = $entity;
    }

    /**
     * Get entity
     *
     * @return Aueio\ClubBundle\Entity\Entity 
     */
    public function getEntity()
    {
        return $this->entity;
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
}