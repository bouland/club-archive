<?php
namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Entity
 *  @ORM\InheritanceType("JOINED")
 *  @ORM\DiscriminatorColumn(name="type", type="string")
 *  @ORM\DiscriminatorMap({"game" = "Game", "player" = "Player", "team" = "Team"}) 
 */
abstract class Object
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
	 * @ORM\OneToMany(targetEntity="Relation", mappedBy="source", cascade={"persist", "remove"})
	 */
	protected $sources;
	
	/**
	 * @ORM\OneToMany(targetEntity="Relation", mappedBy="target", cascade={"persist", "remove"})
	 */
	protected $targets;

    public function __construct()
    {
        $this->sources = new \Doctrine\Common\Collections\ArrayCollection();
    	$this->targets = new \Doctrine\Common\Collections\ArrayCollection();
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
     * Get sources
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSources()
    {
        return $this->sources;
    }

    /**
     * Get targets
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTargets()
    {
        return $this->targets;
    }
}