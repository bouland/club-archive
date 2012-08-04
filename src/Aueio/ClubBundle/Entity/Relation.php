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
     * @ORM\ManyToOne(targetEntity="Object")
    */
    private $source;

    /**
     * @ORM\ManyToOne(targetEntity="Object")
    */
    private $target;
    
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
     * Set date
     *
     * @param date $date
     */
    public function setDate($date)
    {
        $this->date = $date;
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
     * Set origin
     *
     * @param Aueio\ClubBundle\Entity\Object $origin
     */
    public function setOrigin(\Aueio\ClubBundle\Entity\Object $origin)
    {
        $this->origin = $origin;
    }

    /**
     * Get origin
     *
     * @return Aueio\ClubBundle\Entity\Object 
     */
    public function getOrigin()
    {
        return $this->origin;
    }

    /**
     * Set target
     *
     * @param Aueio\ClubBundle\Entity\Object $target
     */
    public function setTarget(\Aueio\ClubBundle\Entity\Object $target)
    {
        $this->target = $target;
    }

    /**
     * Get target
     *
     * @return Aueio\ClubBundle\Entity\Object 
     */
    public function getTarget()
    {
        return $this->target;
    }

    /**
     * Set source
     *
     * @param Aueio\ClubBundle\Entity\Object $source
     */
    public function setSource(\Aueio\ClubBundle\Entity\Object $source)
    {
        $this->source = $source;
    }

    /**
     * Get source
     *
     * @return Aueio\ClubBundle\Entity\Object 
     */
    public function getSource()
    {
        return $this->source;
    }
}