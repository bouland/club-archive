<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Aueio\ClubBundle\Entity\MappedSuperclassObject;
/**
 * Aueio\ClubBundle\Entity\Game
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Entity\GameRepository")
 */
class Game extends Object
{
    /**
    * @var datetime $date
    *
    * @ORM\Column(name="date", type="date")
    */
    private $date;
    
    /**
     * @ORM\Column(type="text")
     */
    private $comment;

    /**
     * Set date
     *
     * @param date $date
     */
    public function setDate(\DateTime $date = null)
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
     * Set comment
     *
     * @param text $comment
     */
    public function setComment($comment)
    {
        $this->comment = $comment;
    }

    /**
     * Get comment
     *
     * @return text 
     */
    public function getComment()
    {
        return $this->comment;
    }
}