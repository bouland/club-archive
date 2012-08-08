<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Aueio\ClubBundle\Entity\Game
 * 
 * @ORM\Entity
 * @ORM\Table(name="games")
 */
class Game
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
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\OneToMany(targetEntity="Role", mappedBy="game", cascade={"persist", "remove"})
     */
    private $teams;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="game")
     */
    private $actions;
 
    public function __construct()
    {
        $this->teams = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add teams
     *
     * @param Aueio\ClubBundle\Entity\Role $teams
     */
    public function addRole(\Aueio\ClubBundle\Entity\Role $team)
    {
    	$team->setGame($this);
        $this->teams[] = $team;
    }

    /**
     * Get teams
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTeams()
    {
        return $this->teams;
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

    /** 
     * Get actions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }
    
    public function getPlayers(Team $team){
    	$players = new \Doctrine\Common\Collections\ArrayCollection();
    	foreach($this->actions as $action){
    		if($action->isPlay()){
    			$player = $action->getPlayer();
    			if($player->getTeam() == $team){
    				$players[] = $player;
    			}
    		}
    	}
    	
    	return $players;
    }
}