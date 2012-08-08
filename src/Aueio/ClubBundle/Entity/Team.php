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
     * @var string $contact
     *
     * @ORM\Column(name="contact", type="string", length=255)
     */
    private $contact;
    
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
        $this->players = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Set contact
     *
     * @param string $contact
     */
    public function setContact($contact)
    {
        $this->contact = $contact;
    }

    /**
     * Get contact
     *
     * @return string 
     */
    public function getContact()
    {
        return $this->contact;
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
     * Add players
     *
     * @param Aueio\ClubBundle\Entity\Player $players
     */
    public function addPlayer(\Aueio\ClubBundle\Entity\Player $players)
    {
        $this->players[] = $players;
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
     * Get roles
     *
     * @return Aueio\ClubBundle\Entity\Role 
     */
    public function getRoles()
    {
        return $this->roles;
    }

    /**
     * Add roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function addRole(\Aueio\ClubBundle\Entity\Role $roles)
    {
        $this->roles[] = $roles;
    }
}