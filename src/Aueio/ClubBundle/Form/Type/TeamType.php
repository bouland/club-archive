<?php
// src/Auieo/ClubBundle/Form/Type/TeamType.php
namespace Aueio\ClubBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class TeamType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text');
		$builder->add('colors', 'collection',array(
							'type'   => 'text',
							'allow_add' => true,
							'allow_delete' => true,
							'prototype' => true,
    						'options'  => array(
							        'required'  => false,
    						),));
		$builder->add('slot_days', 'choice', array( 'choices' => array(	'monday' => 'lundi',
																		'tuesday' => 'mardi',
																		'wednesday' => 'mercredi',
																		'thursday' => 'jeudi',
																		'friday' => 'vendredi',),
													'multiple'  => true,
													//'expanded'  => true,
												 ));
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
												'query_builder' => function(EntityRepository $er) {
																		return $er->createQueryBuilder('p')
																		->where("p.firstname != 'girl'")
																		->andWhere("p.firstname != 'goal'")
																		->andWhere("p.firstname != 'boy'")
																		->orderBy('p.lastname', 'ASC');
																	},
												'expanded'		=> false,
												'multiple'		=> true,
										));
		$builder->add('seasons', 'entity', array(
				'class' 		=> 'AueioClubBundle:Season',
				'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('s')
					->orderBy('s.start_date', 'DESC');
				},
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