<?php
// src/Auieo/ClubBundle/Form/Type/TeamType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
		$builder->add('gym_address',  new AddressType());
		$builder->add('contacts', 'entity', array(
												'class' 		=> 'AueioClubBundle:Player',
												'expanded'		=> false,
												'multiple'		=> true,
										));
		$builder->add('seasons', 'entity', array(
				'class' 		=> 'AueioClubBundle:Season',
				'expanded'		=> false,
				'multiple'		=> true,
		));
	}
	
	public function getName()
	{
		return 'team';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Aueio\ClubBundle\Entity\Team',
		));
	}
}