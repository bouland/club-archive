<?php

namespace Aueio\ClubBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
/**
 * Aueio\ClubBundle\Entity\Relation
 *
 * @ORM\Entity(repositoryClass="Aueio\ClubBundle\Repository\ConfigRepository")
 * @ORM\Table(name="configs")
 */
class Config
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
     * @ORM\OneToOne(targetEntity="Team")
     * @ORM\JoinColumn(name="team_id", referencedColumnName="id", nullable=true)
    */
    private $team_focus;

    /**
     * @ORM\OneToOne(targetEntity="Season")
     * @ORM\JoinColumn(name="season_id", referencedColumnName="id", nullable=true)
     */
    private $season_current;
    
    /**
     * @var string $secret_question
     *
     * @ORM\Column(type="string", length=255 )
     */
    private $secret_question;
    
    /**
     * @var string $secret_clue
     *
     * @ORM\Column(type="string", length=255 )
     */
    private $secret_clue;
    
    /**
     * @var string $secret_answers
     *
     * @ORM\Column(type="array")
     */
    private $secret_answers;

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
     * Set team_focus
     *
     * @param Aueio\ClubBundle\Entity\Team $teamDefault
     */
    public function setTeamFocus(\Aueio\ClubBundle\Entity\Team $teamDefault)
    {
        $this->team_focus = $teamDefault;
    }
	public function removeTeamFocus(){
		$this->team_focus = null;
	}
    /**
     * Get team_focus
     *
     * @return Aueio\ClubBundle\Entity\Team 
     */
    public function getTeamFocus()
    {
        return $this->team_focus;
    }


    /**
     * Set season_current
     *
     * @param Aueio\ClubBundle\Entity\Season $seasonCurrent
     * @return Config
     */
    public function setSeasonCurrent(\Aueio\ClubBundle\Entity\Season $seasonCurrent = null)
    {
        $this->season_current = $seasonCurrent;
    
        return $this;
    }

    /**
     * Get season_current
     *
     * @return Aueio\ClubBundle\Entity\Season 
     */
    public function getSeasonCurrent()
    {
        return $this->season_current;
    }

    /**
     * Set secret_question
     *
     * @param string $secretQuestion
     * @return Config
     */
    public function setSecretQuestion($secretQuestion)
    {
        $this->secret_question = $secretQuestion;
    
        return $this;
    }

    /**
     * Get secret_question
     *
     * @return string 
     */
    public function getSecretQuestion()
    {
        return $this->secret_question;
    }

    /**
     * Set secret_answers
     *
     * @param array $secretAnswers
     * @return Config
     */
    public function setSecretAnswers($secretAnswers)
    {
        $this->secret_answers = $secretAnswers;
    
        return $this;
    }

    /**
     * Get secret_answers
     *
     * @return array 
     */
    public function getSecretAnswers()
    {
        return $this->secret_answers;
    }

    /**
     * Set secret_clue
     *
     * @param string $secretClue
     * @return Config
     */
    public function setSecretClue($secretClue)
    {
        $this->secret_clue = $secretClue;
    
        return $this;
    }

    /**
     * Get secret_clue
     *
     * @return string 
     */
    public function getSecretClue()
    {
        return $this->secret_clue;
    }
}