<?php
// src/Auieo/ClubBundle/Form/Type/SeasonType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class SeasonType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('startDate', 'date', array(
				'input'  => 'datetime',
				'widget' => 'choice',
		));
		$builder->add('endDate', 'date', array(
				'input'  => 'datetime',
				'widget' => 'choice',
		));
	}

	public function getName()
	{
		return 'season';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Aueio\ClubBundle\Entity\Season',
		);
	}
}