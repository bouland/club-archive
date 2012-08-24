<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aueio\ClubBundle\Filter\SeasonAwareInterface;
/**
 * Aueio\ClubBundle\Entity\Game
 * 
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\GameRepository")
 * @ORM\Table(name="games")
 */
class Game implements SeasonAwareInterface
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
    * @var datetime $date
    *
    * @ORM\Column(name="date", type="date")
    */
    private $date;
    
    /**
    * @var datetime $startTime
    *
    * @ORM\Column(name="startTime", type="time")
    */
    private $startTime;
    
    /**
     * @var datetime $endTime
     *
     * @ORM\Column(name="endTime", type="time")
     */
    private $endTime;
    
    /**
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="game", cascade={"persist", "remove"})
     */
    private $roles;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="game")
     */
    private $actions;

    /**
     * @ORM\ManyToOne(targetEntity="Season", inversedBy="games")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id")
     */
    private $season;
    
    public function __construct()
    {
        $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->actions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function __toString(){
    	return "Game " . $this->getId(); 
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
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text 
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * Add role
     *
     * @param Aueio\ClubBundle\Entity\Role $role
     */
    public function addRole(\Aueio\ClubBundle\Entity\Role $role)
    {
    	$role->setGame($this);
        $this->roles[] = $role;
    }

    /**
     * Get teams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add actions
     *
     * @param Aueio\ClubBundle\Entity\Action $actions
     */
    public function addAction(\Aueio\ClubBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;
    }
    public function getTeams(){
    	$teams = new \Doctrine\Common\Collections\ArrayCollection();
    	foreach($this->roles as $role){
			$teams[] = $role->getTeam();
    	}
    	return $teams;
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
    
    public function getPlayers($id_team){
    	$players = new \Doctrine\Common\Collections\ArrayCollection();
    	foreach($this->actions as $action){
    		if($action->isPlay()){
    			$player = $action->getPlayer();
    			if($player->getTeam()->getId() == $id_team){
    				$players[] = $player;
    			}
    		}
    	}
    	return $players;
    }
    
	/** 
     * Get adress
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getAdress(){
		foreach($this->roles as $role){
			if($role->isLocal()){
				return $role->getTeam()->getAdress();
			}
			
		}
	}
    /**
     * Set startTime
     *
     * @param date $startTime
     */
    public function setStartTime($startTime)
    {
        $this->startTime = $startTime;
    }

    /**
     * Get startTime
     *
     * @return date 
     */
    public function getStartTime()
    {
        return $this->startTime;
    }
    /**
     * Get startTime
     *
     * @return date
     */
    public function getStartTimeUTC()
    {
    	return $this->startTime->setTimezone(new \DateTimeZone('UTC'));
    }
    

    /**
     * Set endTime
     *
     * @param date $endTime
     */
    public function setEndTime($endTime)
    {
        $this->endTime = $endTime;
    }
    
    /**
     * Get endTime
     *
     * @return date 
     */
    public function getEndTime()
    {
        return $this->endTime;
    }
    /**
     * Get endTime
     *
     * @return date
     */
    public function getEndTimeUTC()
    {
    	return $this->endTime->setTimezone(new \DateTimeZone('UTC'));
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
     * Remove roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function removeRole(\Aueio\ClubBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
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
     * Set season
     *
     * @param Aueio\ClubBundle\Entity\Season $season
     * @return Game
     */
    public function setSeason(\Aueio\ClubBundle\Entity\Season $season = null)
    {
        $this->season = $season;
        foreach($this->roles as $role){
        	$role->setSeason($season);
        }
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