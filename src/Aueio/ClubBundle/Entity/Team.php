<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use Aueio\ClubBundle\Entity\Entity;
use Aueio\ClubBundle\Entity\Game;
use Aueio\ClubBundle\Entity\Action;

/**
 * Aueio\ClubBundle\Entity\Team
 *
 * @ORM\Entity
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
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @ORM\ManyToMany(targetEntity="Player")
     * @ORM\JoinTable(name="teams_contacts",
     *      joinColumns={@ORM\JoinColumn(name="team_id", referencedColumnName="id")},
     *      inverseJoinColumns={@ORM\JoinColumn(name="player_id", referencedColumnName="id")}
     *      )
     */
    private $contacts;
    
    /**
    * @var string $adress
    *
    * @ORM\Column(name="adress", type="text")
    */
    private $adress;

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
    
    
    public function __toString(){
    	return $this->name;
    }
    public function __construct()
    {
        $this->contacts = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->players = new \Doctrine\Common\Collections\ArrayCollection();
	    $this->roles = new \Doctrine\Common\Collections\ArrayCollection();
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
    public function addContact(\Aueio\ClubBundle\Entity\Player $contact)
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
    public function addPlayer(\Aueio\ClubBundle\Entity\Player $player)
    {
    	$this->players[] = $player;
    }
    
    /**
     * Get players
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getPlayers()
    {
        return $this->players;
    }

    /**
     * Add roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function addRole(\Aueio\ClubBundle\Entity\Role $role)
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
}