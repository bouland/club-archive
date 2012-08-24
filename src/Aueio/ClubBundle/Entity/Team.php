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
    
    /**
     * @ORM\ManyToMany(targetEntity="Season", mappedBy="teams")
     */
    private $seasons;

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

    /**
     * Remove contacts
     *
     * @param Aueio\ClubBundle\Entity\Player $contacts
     */
    public function removeContact(\Aueio\ClubBundle\Entity\Player $contacts)
    {
        $this->contacts->removeElement($contacts);
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
     * Remove roles
     *
     * @param Aueio\ClubBundle\Entity\Role $roles
     */
    public function removeRole(\Aueio\ClubBundle\Entity\Role $roles)
    {
        $this->roles->removeElement($roles);
    }

    /**
     * Add seasons
     *
     * @param Aueio\ClubBundle\Entity\Season $seasons
     * @return Team
     */
    public function addSeason(\Aueio\ClubBundle\Entity\Season $seasons)
    {
        $this->seasons[] = $seasons;
    
        return $this;
    }

    /**
     * Remove seasons
     *
     * @param Aueio\ClubBundle\Entity\Season $seasons
     */
    public function removeSeason(\Aueio\ClubBundle\Entity\Season $seasons)
    {
        $this->seasons->removeElement($seasons);
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
}