<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM,
	Doctrine\Common\Collections\ArrayCollection,
	Aueio\ClubBundle\Entity\PLayer,
	Aueio\ClubBundle\Entity\Role,
	Aueio\ClubBundle\Entity\Season,
	Aueio\ClubBundle\Entity\Adress;

/**
 * Aueio\ClubBundle\Entity\Team
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\TeamRepository")
 * @ORM\Table(name="teams")
 */
class Team
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
     * @var string $name
     *
     * @ORM\Column(name="name", type="string", unique=true, length=255)
     */
    private $name;

    /**
     * @var string $colors
     *
     * @ORM\Column(name="colors", type="string", length=255)
     */
    private $colors;
    
    
    /**
    * @var integer $slot_day
    *
    * @ORM\Column(name="slot_day", type="string", length=255)
    */
    private $slot_days;
    /**
     * @var datetime $slot_start_time
     *
     * @ORM\Column(name="start_time", type="time")
     */
    private $slot_start_time;
    
    /**
     * @var datetime $slot_end_time
     *
     * @ORM\Column(name="end_time", type="time")
     */
    private $slot_end_time;
    
    /**
     * @var string $gym_name
     *
     * @ORM\Column(name="gym_name", type="string", length=255)
     */
    private $gym_name;
    
    /**
     * @var string $gym_phone
     *
     * @ORM\Column(name="gym_phone", type="string", length=10)
     */
    private $gym_phone;
    
		
	/**
    * @var string $adress
    *
    * @ORM\ManyToOne(targetEntity="Adress")
    */
    private $adress;

    /**
     * @ORM\ManyToMany(targetEntity="Player")
     * @ORM\JoinTable(name="teams_contacts"),
     */
    private $contacts;
    
    /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team")
     */
    private $players;
    
     /**
     * Bidirectional - One-To-Many (INVERSE SIDE)
     *
     * @ORM\OneToMany(targetEntity="Role", mappedBy="team")
     */
    private $roles;
    
    /**
     * @ORM\ManyToMany(targetEntity="Season", inversedBy="teams")
     * @ORM\JoinTable(name="seasons_teams")
     */
    private $seasons;

    /**
     * @param Player $from
     * @param text $subject
     * @param text $message
     * 
     */
    
    public function __toString(){
    	return $this->name;
    }
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->players = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->seasons = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set name
     *
     * @param string $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
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
     * Add contacts
     *
     * @param Aueio\ClubBundle\Entity\Player $contacts
     */
    public function addContact(Player $contact)
    {
        $this->contacts[] = $contact;
    }

    /**
     * Get contacts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getContacts()
    {
        return $this->contacts;
    }
    
    /**
     * Add players
     *
     * @param Aueio\ClubBundle\Entity\Player $player
     */
    public function addPlayer(Player $player)
    {
    	$this->players[] = $player;
    }
    public function removePlayers(){
    	foreach($this->players as $player){
    		$player->removeTeam();
    	}
    }
    /**
     * Get players
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    /* don't use it, without season filter capability
    public function getPlayers()
    {
        return $this->players;
    }
	*/
    /**
     * Add roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function addRole(Role $role)
    {
        $this->roles[] = $role;
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
     * Remove contacts
     *
     * @param Aueio\ClubBundle\Entity\Player $contacts
     */
    public function removeContact(Player $contacts)
    {
        $this->contacts->removeElement($contacts);
    }

    /**
     * Remove players
     *
     * @param Aueio\ClubBundle\Entity\Player $players
     */
    public function removePlayer(Player $players)
    {
        $this->players->removeElement($players);
    }

    /**
     * Remove roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function removeRole(Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add seasons
     *
     * @param Aueio\ClubBundle\Entity\Season $seasons
     * @return Team
     */
    public function addSeason(Season $season)
    {
    	$season->addTeam($this);
        $this->seasons[] = $season;
    
        return $this;
    }

    /**
     * Remove seasons
     *
     * @param Aueio\ClubBundle\Entity\Season $seasons
     */
    public function removeSeason(Season $season)
    {
    	$season->removeTeam($this);
        $this->seasons->removeElement($season);
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
     * Set colors
     *
     * @param string $colors
     * @return Team
     */
    public function setColors($colors)
    {
        $this->colors = $colors;
    
        return $this;
    }

    /**
     * Get colors
     *
     * @return string 
     */
    public function getColors()
    {
        return $this->colors;
    }

    /**
     * Set slot_day
     *
     * @param integer $slotDay
     * @return Team
     */
    public function setSlotDay($slotDay)
    {
        $this->slot_day = $slotDay;
    
        return $this;
    }

    /**
     * Get slot_day
     *
     * @return integer 
     */
    public function getSlotDay()
    {
        return $this->slot_day;
    }

    /**
     * Set slot_start_time
     *
     * @param \DateTime $slotStartTime
     * @return Team
     */
    public function setSlotStartTime($slotStartTime)
    {
        $this->slot_start_time = $slotStartTime;
    
        return $this;
    }

    /**
     * Get slot_start_time
     *
     * @return \DateTime 
     */
    public function getSlotStartTime()
    {
        return $this->slot_start_time;
    }

    /**
     * Set slot_end_time
     *
     * @param \DateTime $slotEndTime
     * @return Team
     */
    public function setSlotEndTime($slotEndTime)
    {
        $this->slot_end_time = $slotEndTime;
    
        return $this;
    }

    /**
     * Get slot_end_time
     *
     * @return \DateTime 
     */
    public function getSlotEndTime()
    {
        return $this->slot_end_time;
    }

    /**
     * Set gym_name
     *
     * @param string $gymName
     * @return Team
     */
    public function setGymName($gymName)
    {
        $this->gym_name = $gymName;
    
        return $this;
    }

    /**
     * Get gym_name
     *
     * @return string 
     */
    public function getGymName()
    {
        return $this->gym_name;
    }

    /**
     * Set gym_phone
     *
     * @param string $gymPhone
     * @return Team
     */
    public function setGymPhone($gymPhone)
    {
        $this->gym_phone = $gymPhone;
    
        return $this;
    }

    /**
     * Get gym_phone
     *
     * @return string 
     */
    public function getGymPhone()
    {
        return $this->gym_phone;
    }

    /**
     * Set slot_days
     *
     * @param string $slotDays
     * @return Team
     */
    public function setSlotDays($slotDays)
    {
        $this->slot_days = $slotDays;
    
        return $this;
    }

    /**
     * Get slot_days
     *
     * @return string 
     */
    public function getSlotDays()
    {
        return $this->slot_days;
    }
}