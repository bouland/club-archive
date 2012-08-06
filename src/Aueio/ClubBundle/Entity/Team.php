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
 * @ORM\Table()
 * @ORM\Entity()
 */
class Team extends Entity
{
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
     * @ORM\OneToMany(targetEntity="Player", mappedBy="team")
     */
    private $players;
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="win")
     */
    private $wins;
    
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="lost")
     */
    private $losts;
    
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="visitor")
     */
    private $visitors;
    
    /**
     * @ORM\OneToMany(targetEntity="Game", mappedBy="lost")
     */
    private $locals;
    
    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="entity")
     */
    private $goals;
    
    
    public function __toString(){
    	return $this->name;
    }
    
    
    public function __construct()
    {
        $this->wins = new \Doctrine\Common\Collections\ArrayCollection();
    $this->losts = new \Doctrine\Common\Collections\ArrayCollection();
    $this->visitors = new \Doctrine\Common\Collections\ArrayCollection();
    $this->locals = new \Doctrine\Common\Collections\ArrayCollection();
    $this->goals = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Add wins
     *
     * @param Aueio\ClubBundle\Entity\Game $wins
     */
    public function addGame(\Aueio\ClubBundle\Entity\Game $wins)
    {
        $this->wins[] = $wins;
    }

    /**
     * Get wins
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getWins()
    {
        return $this->wins;
    }

    /**
     * Get losts
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLosts()
    {
        return $this->losts;
    }

    /**
     * Get visitors
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getVisitors()
    {
        return $this->visitors;
    }

    /**
     * Get locals
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLocals()
    {
        return $this->locals;
    }

    /**
     * Add goals
     *
     * @param Aueio\ClubBundle\Entity\Action $goals
     */
    public function addAction(\Aueio\ClubBundle\Entity\Action $goals)
    {
        $this->goals[] = $goals;
    }

    /**
     * Get goals
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getGoals()
    {
        return $this->goals;
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
}