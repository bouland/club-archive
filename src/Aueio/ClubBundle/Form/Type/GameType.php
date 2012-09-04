<?php
// src/Auieo/ClubBundle/Form/Type/GameType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Aueio\ClubBundle\Form\Type\RoleType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class GameType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		if($options['intention'] == 'create' || $options['intention'] == 'edit'){
			$builder->add('date', 'date', array(
					'input'  => 'datetime',
					'widget' => 'choice',
			));
			$builder->add('start_time', 'time', array(
			    'input'  => 'datetime',
			    'widget' => 'choice',
					'with_seconds' => false,
			));
			$builder->add('end_time', 'time', array(
					'input'  => 'datetime',
					'widget' => 'choice',
					'with_seconds' => false,
			));
		}else{
			$builder->add('comment', 'textarea', array('required' => false));
		}
		$builder->add('roles', 'collection', array( 'type' => new RoleType(),
													'options' => array('intention' => $options['intention']),
												  ));
	}

	public function getName()
	{
		return 'game';
	}
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'intention' => 'create',
				'data_class' => 'Aueio\ClubBundle\Entity\Game',
		));
	}
}