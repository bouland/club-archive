<?php
// src/Auieo/ClubBundle/Form/Type/GameType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;
use Aueio\ClubBundle\Form\Type\RoleType;

class GameType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		if($options['form'] == 'new' || $options['form'] == 'edit'){
			$builder->add('date', 'date');
		}else{
			$builder->add('comment', 'textarea', array('required' => false));
		}
		$builder->add('teams', 'collection', array( 'type' => new RoleType(),
													'options' => array('form' => $options['form']),
												  ));
	}

	public function getName()
	{
		return 'game';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'form' => 'new',
				'data_class' => 'Aueio\ClubBundle\Entity\Game',
		);
	}
}