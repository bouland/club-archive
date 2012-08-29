<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\SeasonRepository")
 * @ORM\Table(name="seasons")
 */
class Season
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
     * @var string $color
     *
     * @ORM\Column(name="color", type="integer", length=2)
     */
    private $color;
	
    
    /**
     * @var datetime $start_date
     *
     * @ORM\Column(name="start_date", type="date")
     */
    private $start_date;

    /**
     * @var datetime $end_date
     *
     * @ORM\Column(name="end_date", type="date")
     */
    private $end_date;
    
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="season")
     */
    private $games;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="season")
     */
    private $actions;

    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="season")
     */
    private $roles;

    /**
     * @ORM\ManyToMany(targetEntity="Team", inversedBy="seasons")
     * @ORM\JoinTable(name="seasons_teams")
     */
    private $teams;

    /**
     * @ORM\ManyToMany(targetEntity="Player", inversedBy="seasons")
     * @ORM\JoinTable(name="seasons_players")
     */
    private $players;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString(){
    	return $this->start_date->format("Y") . " - " . $this->end_date->format("Y");
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
     * Set start_date
     *
     * @param \DateTime $start_date
     * @return Season
     */
    public function setStartDate($start_date)
    {
        $this->start_date = $start_date;
    
        return $this;
    }

    /**
     * Get start_date
     *
     * @return \DateTime 
     */
    public function getStartDate()
    {
        return $this->start_date;
    }

    /**
     * Set end_date
     *
     * @param \DateTime $end_date
     * @return Season
     */
    public function setEndDate($end_date)
    {
        $this->end_date = $end_date;
    
        return $this;
    }

    /**
     * Get end_date
     *
     * @return \DateTime 
     */
    public function getEndDate()
    {
        return $this->end_date;
    }

    /**
     * Add games
     *
     * @param Aueio\ClubBundle\Entity\Game $games
     * @return Season
     */
    public function addGame(\Aueio\ClubBundle\Entity\Game $games)
    {
        $this->games[] = $games;
    
        return $this;
    }

    /**
     * Remove games
     *
     * @param Aueio\ClubBundle\Entity\Game $games
     */
    public function removeGame(\Aueio\ClubBundle\Entity\Game $games)
    {
        $this->games->removeElement($games);
    }

    /**
     * Get games
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGames()
    {
        return $this->games;
    }

    /**
     * Add actions
     *
     * @param Aueio\ClubBundle\Entity\Action $actions
     * @return Season
     */
    public function addAction(\Aueio\ClubBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;
    
        return $this;
    }

    /**
     * Remove actions
     *
     * @param Aueio\ClubBundle\Entity\Action $actions
     */
    public function removeAction(\Aueio\ClubBundle\Entity\Action $actions)
    {
        $this->actions->removeElement($actions);
    }

    /**
     * Get actions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }

    /**
     * Add roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     * @return Season
     */
    public function addRole(\Aueio\ClubBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
    
        return $this;
    }

    /**
     * Remove roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function removeRole(\Aueio\ClubBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Get roles
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Set teams
     *
     * @param Aueio\ClubBundle\Entity\Team $teams
     * @return Season
     */
    public function setTeams(\Aueio\ClubBundle\Entity\Team $teams = null)
    {
        $this->teams = $teams;
    
        return $this;
    }

    /**
     * Get teams
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getTeams()
    {
        return $this->teams;
    }

    /**
     * Set players
     *
     * @param Aueio\ClubBundle\Entity\Team $players
     * @return Season
     */
    public function setPlayers(\Aueio\ClubBundle\Entity\Team $players = null)
    {
        $this->players = $players;
    
        return $this;
    }

    /**
     * Get players
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Add teams
     *
     * @param Aueio\ClubBundle\Entity\Team $teams
     * @return Season
     */
    public function addTeam(\Aueio\ClubBundle\Entity\Team $teams)
    {
        $this->teams[] = $teams;
    
        return $this;
    }

    /**
     * Remove teams
     *
     * @param Aueio\ClubBundle\Entity\Team $teams
     */
    public function removeTeam(\Aueio\ClubBundle\Entity\Team $teams)
    {
        $this->teams->removeElement($teams);
    }

    /**
     * Add players
     *
     * @param Aueio\ClubBundle\Entity\Player $players
     * @return Season
     */
    public function addPlayer(\Aueio\ClubBundle\Entity\Player $players)
    {
        $this->players[] = $players;
    
        return $this;
    }

    /**
     * Remove players
     *
     * @param Aueio\ClubBundle\Entity\Player $players
     */
    public function removePlayer(\Aueio\ClubBundle\Entity\Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Set color
     *
     * @param string $color
     * @return Season
     */
    public function setColor($color)
    {
        $this->color = $color;
    
        return $this;
    }

    /**
     * Get color
     *
     * @return string 
     */
    public function getColor()
    {
        return $this->color;
    }
}