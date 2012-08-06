<?php
namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 *  @ORM\Entity
 *  @ORM\InheritanceType("JOINED")
 *  @ORM\DiscriminatorColumn(name="type", type="string")
 *  @ORM\DiscriminatorMap({"player" = "Player", "team" = "Team"}) 
 */
abstract class Entity
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }
}