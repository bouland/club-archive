<?php
// src/Auieo/ClubBundle/Form/Type/ConfigType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AdressType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'textarea');
		$builder->add('code', 'integer');
		$builder->add('city', 'text');
	}

	public function getName()
	{
		return 'adress';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Aueio\ClubBundle\Entity\Adress',
		);
	}
}