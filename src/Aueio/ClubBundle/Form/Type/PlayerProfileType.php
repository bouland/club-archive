<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
	FOS\UserBundle\Form\Type\ProfileFormType;

class PlayerProfileType extends ProfileFormType
{
	public function buildUserForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildUserForm($builder, $options);
		
		$builder->add('displayname', 'text');
		$builder->add('phone', 'text');
		$builder->add('adress', 'textarea');
		$builder->add('gender', 'choice', array(
				'choices'   => array('M' => 'Homme', 'F' => 'Femme'),
				'required'  => true,
		));
		$builder->add('car', 'checkbox', array(
				'data' => true,
				'required' => false,
		));
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
		
		$builder->add('team', 'entity', array(
												'class' 		=> 'AueioClubBundle:Team',
												'property'     	=> 'name',
												'expanded'	=> true,
										));
		
	}

	public function getName()
	{
		return 'aueio_club_registration';
	}

}
