<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Doctrine\ORM\EntityManager;

class TeamPlayerType extends AbstractType
{
	private $em;
	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}
	
	public function buildForm(FormBuilder $builder, array $options)
	{
		
		$builder->add('player', new PlayerType());
		$choices = array();
		$teams = $this->em->getRepository('AueioClubBundle:Team')->findAll();
		foreach($teams as $team){
			$choices[$team->getId()] = $team->getName();
		}
		$builder->add('team_id', 'choice', array('choices' => $choices));
	}

	public function getName()
	{
		return 'data';
	}
}
