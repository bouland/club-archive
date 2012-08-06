<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
/**
 * Aueio\ClubBundle\Entity\Game
 * @ORM\Table()
 * @ORM\Entity()
 */
class Game
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
     * @ORM\ManyToOne(targetEntity="Team")
     */
    private $win;
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     */
    private $lost;
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     */
    private $visitor;
    /**
     * @ORM\ManyToOne(targetEntity="Team")
     */
    private $local;
    /**
     * @ORM\ManyToOne(targetEntity="Action")
     */
    private $visitor_score;
    /**
     * @ORM\ManyToOne(targetEntity="Action")
     */
    private $local_score;
    
    /**
     * @ORM\OneToMany(targetEntity="Action", mappedBy="game")
     */
    private $actions;
    
    public function __construct()
    {
        $this->actions = new \Doctrine\Common\Collections\ArrayCollection();
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

    /**
     * Set win
     *
     * @param Aueio\ClubBundle\Entity\Team $win
     */
    public function setWin(\Aueio\ClubBundle\Entity\Team $win)
    {
        $this->win = $win;
    }

    /**
     * Get win
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getWin()
    {
        return $this->win;
    }

    /**
     * Set lost
     *
     * @param Aueio\ClubBundle\Entity\Team $lost
     */
    public function setLost(\Aueio\ClubBundle\Entity\Team $lost)
    {
        $this->lost = $lost;
    }

    /**
     * Get lost
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getLost()
    {
        return $this->lost;
    }

    /**
     * Set visitor
     *
     * @param Aueio\ClubBundle\Entity\Team $visitor
     */
    public function setVisitor(\Aueio\ClubBundle\Entity\Team $visitor)
    {
        $this->visitor = $visitor;
    }

    /**
     * Get visitor
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getVisitor()
    {
        return $this->visitor;
    }

    /**
     * Set local
     *
     * @param Aueio\ClubBundle\Entity\Team $local
     */
    public function setLocal(\Aueio\ClubBundle\Entity\Team $local)
    {
        $this->local = $local;
    }

    /**
     * Get local
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getLocal()
    {
        return $this->local;
    }

    /**
     * Set visitor_score
     *
     * @param Aueio\ClubBundle\Entity\Action $visitorScore
     */
    public function setVisitorScore(\Aueio\ClubBundle\Entity\Action $visitorScore)
    {
        $this->visitor_score = $visitorScore;
    }

    /**
     * Get visitor_score
     *
     * @return Aueio\ClubBundle\Entity\Action 
     */
    public function getVisitorScore()
    {
        return $this->visitor_score;
    }

    /**
     * Set local_score
     *
     * @param Aueio\ClubBundle\Entity\Action $localScore
     */
    public function setLocalScore(\Aueio\ClubBundle\Entity\Action $localScore)
    {
        $this->local_score = $localScore;
    }

    /**
     * Get local_score
     *
     * @return Aueio\ClubBundle\Entity\Action 
     */
    public function getLocalScore()
    {
        return $this->local_score;
    }

    /**
     * Add actions
     *
     * @param Aueio\ClubBundle\Entity\Action $actions
     */
    public function addAction(\Aueio\ClubBundle\Entity\Action $actions)
    {
        $this->actions[] = $actions;
    }

    /**
     * Get actions
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getActions()
    {
        return $this->actions;
    }
}