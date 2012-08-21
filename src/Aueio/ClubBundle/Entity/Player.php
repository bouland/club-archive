<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User;

/**
 * Aueio\ClubBundle\Entity\Player
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\PlayerRepository")
 * @ORM\Table(name="players")
 * @ORM\HasLifecycleCallbacks()
 */
class Player extends User
{
	/**
	 * @var integer $id
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;
	
    /**
     * @var string $displayname
     *
     * @ORM\Column(name="displayname", type="string", length=50)
     */
    private $displayname;
    
    /**
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;
    
    /**
    * @var string $phone
    *
    * @ORM\Column(name="phone", type="string", unique=true, length=10)
    */
    private $phone;
    
    /**
    * @var string $adress
    *
    * @ORM\Column(name="adress", type="text")
    */
    private $adress;
    
    
    /**
    * @var boolean $car
    *
    * @ORM\Column(name="car", type="boolean")
    */
    private $car;
    
    /**
    * @var string $position
    *
    * @ORM\Column(name="position", type="string", length=6)
    */
    private $position;
    
    /**
    * @var string $hand;
    *
    * @ORM\Column(name="hand", type="string", length=5)
    */
    private $hand;
    
    /**
     * @var datetime $created
     * 
     * @ORM\Column(name="created", type="date")
     */
    private $created;
    
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable="TRUE")
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="player")
     */
    private $actions;
    

    /**
     * @ORM\PrePersist
     *
     */
    public function setCreated()
    {
        $this->created = new \DateTime('now');
    }

    public function __construct()
    {
        parent::__construct();
        $this->games = new \Doctrine\Common\Collections\ArrayCollection();
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString(){
    	return "Player " . $this->getId() . " " . $this->getFirstname();
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
     * Set firstname
     *
     * @param string $firstname
     */
    public function setDisplayname($displayname)
    {
        $this->displayname = $displayname;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getDisplayname()
    {
        return $this->displayname;
    }

    /**
     * Set gender
     *
     * @param string $gender
     */
    public function setGender($gender)
    {
        $this->gender = $gender;
    }

    /**
     * Get gender
     *
     * @return string 
     */
    public function getGender()
    {
        return $this->gender;
    }

    /**
     * Set phone
     *
     * @param string $phone
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set adress
     *
     * @param text $adress
     */
    public function setAdress($adress)
    {
        $this->adress = $adress;
    }

    /**
     * Get adress
     *
     * @return text 
     */
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set car
     *
     * @param boolean $car
     */
    public function setCar($car)
    {
        $this->car = $car;
    }

    /**
     * Get car
     *
     * @return boolean 
     */
    public function getCar()
    {
        return $this->car;
    }

    /**
     * Set position
     *
     * @param string $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Get position
     *
     * @return string 
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set hand
     *
     * @param string $hand
     */
    public function setHand($hand)
    {
        $this->hand = $hand;
    }

    /**
     * Get hand
     *
     * @return string 
     */
    public function getHand()
    {
        return $this->hand;
    }

    /**
     * Get date_register
     *
     * @return date 
     */
    public function getDateRegister()
    {
        return $this->date_register;
    }

    /**
     * Set team
     *
     * @param Aueio\ClubBundle\Entity\Team $team
     */
    public function setTeam(\Aueio\ClubBundle\Entity\Team $team)
    {
    	$team->addPlayer($this);
        $this->team = $team;
    }
    public function removeTeam()
    {
    	$this->team = null;
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
     * Add games
     *
     * @param Aueio\ClubBundle\Entity\Game $games
     */
    public function addGame(\Aueio\ClubBundle\Entity\Game $games)
    {
        $this->games[] = $games;
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

    /**
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        return $this->created;
    }
}