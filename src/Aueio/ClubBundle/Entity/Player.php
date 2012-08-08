<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Aueio\ClubBundle\Entity\Entity;
use Aueio\ClubBundle\Entity\Action;
use Aueio\ClubBundle\Entity\Game;
use Aueio\ClubBundle\Entity\Team;
/**
 * Aueio\ClubBundle\Entity\Player
 *
 * @ORM\Entity
 * @ORM\Table(name="players")
 * @ORM\HasLifecycleCallbacks()
 */
class Player
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
     * @var string $surname
     *
     * @ORM\Column(name="surname", type="string", length=50)
     */
    private $surname;
    
    /**
     * @var string $gender
     *
     * @ORM\Column(name="gender", type="string", length=1)
     */
    private $gender;
    
    /**
    * @var string $email
    *
    * @ORM\Column(name="email", type="string", unique=true, length=255)
    */
    private $email;

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
     * @var datetime $date_register
     * 
     * @ORM\Column(name="date_register", type="date")
     */
    private $date_register;
    
    /**
     * @var boolean $enable
     *
     * @ORM\Column(name="enable", type="boolean")
     */
    private $enable;
    
    /**
     * @ORM\ManyToOne(targetEntity="Team")
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
    public function setDateRegister()
    {
        $this->date_register = new \DateTime('now');
    }

    public function __construct()
    {
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
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;
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
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;
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
     * Set surname
     *
     * @param string $surname
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
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
     * Set email
     *
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
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
     * Set enable
     *
     * @param boolean $enable
     */
    public function setEnable($enable)
    {
        $this->enable = $enable;
    }

    /**
     * Get enable
     *
     * @return boolean 
     */
    public function getEnable()
    {
        return $this->enable;
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
}