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
		if($options['type'] == 'edit' && $options['season_id'] > 0 && $options['team_id'] > 0){
			$builder->add('leaders_add', 'entity', array(
					'property_path' => false,
					'required'		=> false,
					'expanded'		=> false,
					'multiple'		=> true,
					'class' 		=> 'AueioClubBundle:Player',
					'query_builder' => function(EntityRepository $er) use ($options){
											return $er->createQueryBuilder('p')
												->leftJoin('p.seasons', 's')
												->join('p.team', 't')
												->where('t.id = :id_team')
												->andWhere('s.id = :id_season')
												->andWhere("p.firstname != 'girl'")
												->andWhere("p.firstname != 'goal'")
												->andWhere("p.firstname != 'boy'")
												->orderBy('p.firstname')
												->setParameters(array(
														'id_season' => $options['season_id'],
														'id_team' => $options['team_id'],
											));
										}
			));
			$builder->add('leaders_del', 'entity', array(
					'property_path' => false,
					'required'		=> false,
					'expanded'		=> false,
					'multiple'		=> true,
					'class' 		=> 'AueioClubBundle:Player',
					'query_builder' => function(EntityRepository $er) use ($options){
											return $er->createQueryBuilder('p')
												->join('p.team', 't')
												->leftJoin('p.seasons', 's')
												->where('t.id = :id_team')
												->andWhere('s.id = :id_season')
												->andWhere("p.roles LIKE '%ROLE_LEADER%'")
												->orderBy('p.firstname')
												->setParameters(array(
														'id_season' => $options['season_id'],
														'id_team' => $options['team_id'],
											));
										}
			));
		}
		$builder->add('seasons', 'entity', array(
				'class' 		=> 'AueioClubBundle:Season',
				'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('s')
				->orderBy('s.start_date', 'DESC');
		},
		'expanded'		=> false,
		'multiple'		=> true,
		));
		
		$builder->add('cash', 'money', array('precision' => 2));
	}
	
	public function getName()
	{
		return 'team';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Aueio\ClubBundle\Entity\Team',
				'type' => "new",
				'team_id' => 0,
				'season_id' => 0
		));
	}
}