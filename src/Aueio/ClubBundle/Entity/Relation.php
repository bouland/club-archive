<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Entity\RelationRepository")
 */
class Relation
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
    * @ORM\OneToOne(targetEntity="Object")
    * @ORM\JoinColumn(name="id_one", referencedColumnName="id")
    */
    private $id_one;

    /**
    * @ORM\OneToOne(targetEntity="Object")
    * @ORM\JoinColumn(name="id_two", referencedColumnName="id")
    */
    private $id_two;
    
    /**
    * @var string $name
    *
    * @ORM\Column(name="name", type="string", length=255)
    */
    private $name;
    
    /**
     * @var string $value
     *
     * @ORM\Column(name="value", type="string", length=255, nullable="true")
     */
    private $value;
    
    /**
     * @var datetime $date
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;
    
    public function __construct(array $options = null) {
    	$this->date = new \DateTime('now');
    	if (is_array($options)) {
			$this->setOptions($options);
		}
    }
    public function setOptions(array $options)
    {
    	$methods = get_class_methods($this);
    	foreach ($options as $key => $value) {
    		$method = 'set' . ucfirst($key);
    		if (in_array($method, $methods)) {
    			$this->$method($value);
    		}
    	}
    	return $this;
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
     * Set value
     *
     * @param string $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * Get value
     *
     * @return string 
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set id_one
     *
     * @param Aueio\ClubBundle\Entity\Object $idOne
     */
    public function setIdOne(\Aueio\ClubBundle\Entity\Object $idOne)
    {
        $this->id_one = $idOne;
    }

    /**
     * Get id_one
     *
     * @return Aueio\ClubBundle\Entity\Object 
     */
    public function getIdOne()
    {
        return $this->id_one;
    }

    /**
     * Set id_two
     *
     * @param Aueio\ClubBundle\Entity\Object $idTwo
     */
    public function setIdTwo(\Aueio\ClubBundle\Entity\Object $idTwo)
    {
        $this->id_two = $idTwo;
    }

    /**
     * Get id_two
     *
     * @return Aueio\ClubBundle\Entity\Object 
     */
    public function getIdTwo()
    {
        return $this->id_two;
    }

    /**
     * Get date
     *
     * @return date 
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * Set date
     *
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
    }
}