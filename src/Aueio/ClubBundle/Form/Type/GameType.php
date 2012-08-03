<?php
// src/Auieo/ClubBundle/Form/Type/GameType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class GameType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('date', 'date');
		$builder->add('comment', 'textarea');
	}

	public function getName()
	{
		return 'game';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Aueio\ClubBundle\Entity\Game',
		);
	}
}