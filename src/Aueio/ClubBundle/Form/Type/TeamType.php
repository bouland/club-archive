<?php
// src/Auieo/ClubBundle/Form/Type/TeamType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text');
		$builder->add('colors', 'text');
		$builder->add('slot_days', 'text');
		$builder->add('slot_start_time', 'time', array(
				'input'  => 'datetime',
				'widget' => 'choice',
				'with_seconds' => false,
		));
		$builder->add('slot_end_time', 'time', array(
				'input'  => 'datetime',
				'widget' => 'choice',
				'with_seconds' => false,
		));
		$builder->add('gym_name', 'text');
		$builder->add('gym_phone', 'text');
		$builder->add('adress',  new AdressType());
		$builder->add('contacts', 'entity', array(
												'class' 		=> 'AueioClubBundle:Player',
												'expanded'		=> true,
												'multiple'		=> true,
										));	}

	public function getName()
	{
		return 'team';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Aueio\ClubBundle\Entity\Team',
		);
	}
}