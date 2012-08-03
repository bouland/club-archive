<?php
// src/Auieo/ClubBundle/Form/Type/TeamType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilder;

class TeamType extends AbstractType
{
	public function buildForm(FormBuilder $builder, array $options)
	{
		$builder->add('name', 'text');
		$builder->add('contact', 'text');
		$builder->add('email', 'email');
		$builder->add('phone', 'text');
		$builder->add('adress', 'textarea');
	}

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