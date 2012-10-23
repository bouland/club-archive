<?php
// src/Auieo/ClubBundle/Form/Type/ConfigType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('team_focus', 'entity', array(
					'class' 		=> 'AueioClubBundle:Team',
					'property'     	=> 'name',
					'expanded'		=> false,
			));
		$builder->add('season_current', 'entity', array(
					'class' 		=> 'AueioClubBundle:Season',
					'expanded'		=> false,
			));
		$builder->add('secret_question', 'text');
		
		$builder->add('secret_clue', 'text');
		
		$builder->add('secret_answers', 'collection',array(
				'type'   => 'text',
				'allow_add' => true,
				'allow_delete' => true,
				'prototype' => true,
				));
	}

	public function getName()
	{
		return 'config';
	}
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'data_class' => 'Aueio\ClubBundle\Entity\Config',
		));
	}
	
}