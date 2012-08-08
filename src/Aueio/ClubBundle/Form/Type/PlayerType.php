<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class PlayerType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('firstname', 'text');
		$builder->add('lastname', 'text');
		$builder->add('surname', 'text');
		$builder->add('email', 'email');
		$builder->add('phone', 'text');
		$builder->add('adress', 'textarea');
		$builder->add('gender', 'choice', array(
				'choices'   => array('M' => 'Homme', 'F' => 'Femme'),
				'required'  => true,
		));
		$builder->add('car', 'checkbox', array('required'  => false));
		$builder->add('position', 'choice', array(
				'choices'   => array(	'GOAL' => 'Gardien',
										'PIVOT' => 'Pivot',
										'CENTER' => 'Demi',
										'BACK' => 'Arrière',
										'WING' => 'Ailier')
		));
		$builder->add('hand', 'choice', array(
				'choices'   => array('RIGHT' => 'Droitier', 'LEFT' => 'Gaucher')
		));
		
		$builder->add('enable', 'checkbox', array('required'  => false));
		
		$builder->add('team', 'entity', array(
												'class' 		=> 'AueioClubBundle:Team',
												'property'     	=> 'name',
												'expanded'	=> true,
										));
	}

	public function getName()
	{
		return 'player';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Aueio\ClubBundle\Entity\Player',
		);
	}
}
