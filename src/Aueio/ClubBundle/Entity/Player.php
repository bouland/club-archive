<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User;
use Aueio\ClubBundle\Entity\Address;

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
     * @var string $firstname
     *
     * @ORM\Column(name="firstname", type="string", length=50)
     */
    private $firstname;
    
    /**
     * @var string $lastname
     *
     * @ORM\Column(name="lastname", type="string", length=50)
     */
    private $lastname;
   
    /**
     * @var string $gender
     *
     * @ORM\Column(name="credit", type="decimal", precision=5,scale=2)
     */
    private $credit;

    
    /**
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;
    
    /**
    * @var string $phone
    *
    * @ORM\Column(name="phone", type="string", unique=true, length=10, nullable=true)
    */
    private $phone;
    
	/**
    * @var Address $address
    *
    * @ORM\ManyToOne(targetEntity="Address")
    * @ORM\JoinColumn(name="address_id", referencedColumnName="id", nullable=true)
    */
    private $address;
    
    /**
    * @var boolean $car
    *
    * @ORM\Column(name="car", type="boolean", nullable=true)
    */
    private $car;
    
    /**
    * @var string $position
    *
    * @ORM\Column(name="position", type="string", length=6, nullable=true)
    */
    private $position;
    
    /**
    * @var string $hand;
    *
    * @ORM\Column(name="hand", type="string", length=5, nullable=true)
    */
    private $hand;
    
    /**
     * @var datetime $created
     * 
     * @ORM\Column(name="created", type="date")
     */
    private $created;
    
    /**
     * @ORM\ManyToOne(targetEntity="Team", inversedBy="players")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=true)
     */
    private $team;

    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="player", cascade={"persist", "remove"})
     */
    private $actions;
    
    /**
     * @ORM\ManyToMany(targetEntity="Season", inversedBy="players")
     * @ORM\JoinTable(name="seasons_players")
     */
    private $seasons;

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
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
        $this->seasons = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    public function __toString(){
    	return $this->getFirstname() . " " . $this->getLastname();
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
    	$this->team->removePlayer($this);
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
     * Add actions
     *
     * @param Aueio\ClubBundle\Entity\Action $actions
     */
    public function addAction(\Aueio\ClubBundle\Entity\Action $actions)
    {
    	$this->actions[] = $actions;
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
     * Add seasons
     *
     * @param Aueio\ClubBundle\Entity\Season $seasons
     * @return Player
     */
    public function addSeason(\Aueio\ClubBundle\Entity\Season $season)
    {
    	$season->addPlayer($this);
    	$this->seasons[] = $season;
    
    	return $this;
    }
    
    /**
     * Remove seasons
     *
     * @param Aueio\ClubBundle\Entity\Season $seasons
     */
    public function removeSeason(\Aueio\ClubBundle\Entity\Season $season)
    {
    	$season->removePlayer($this);
    	$this->seasons->removeElement($season);
    }
    /**
     * Remove leads
     */
    public function removeSeasons()
    {
    	foreach($this->seasons as $season){
    		$this->removeSeason($season);
    	}
    }
    /**
     * Get seasons
     *
     * @return Doctrine\Common\Collections\Collection
     */
    public function getSeasons()
    {
    	return $this->seasons;
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
     * Get created
     *
     * @return date 
     */
    public function getCreated()
    {
        return $this->created;
    }

    
    /**
     * Set firstname
     *
     * @param string $firstname
     * @return Player
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
    
        return $this;
    }

    /**
     * Get firstname
     *
     * @return string 
     */
    public function getFirstname()
    {
        return $this->firstname;
    }
	
    /**
     * Set lastname
     *
     * @param string $lastname
     * @return Player
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
    
        return $this;
    }

    /**
     * Get lastname
     *
     * @return string 
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set address
     *
     * @param Aueio\ClubBundle\Entity\Address $address
     * @return Player
     */
    public function setAddress(Address $address = null)
    {
        $this->address = $address;
    
        return $this;
    }

    /**
     * Get address
     *
     * @return Aueio\ClubBundle\Entity\Address 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set credit
     *
     * @param decimal $credit
     * @return Player
     */
    public function setCredit($credit)
    {
        $this->credit = $credit;
    
        return $this;
    }

    /**
     * Get credit
     *
     * @return decimal
     */
    public function getCredit()
    {
        return $this->credit;
    }
}