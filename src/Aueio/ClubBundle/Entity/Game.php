<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Aueio\ClubBundle\Entity\Game
 * @ORM\Table()
 * @ORM\Entity()
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