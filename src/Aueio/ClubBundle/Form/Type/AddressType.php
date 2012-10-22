<?php
// src/Auieo/ClubBundle/Form/Type/ConfigType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class AddressType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'textarea');
		$builder->add('code', 'integer');
		$builder->add('city', 'text');
		$builder->add('latitude', 'number', array('precision' => 10, 'required' => false));
		$builder->add('longitude', 'number',array('precision' => 10, 'required' => false));
	}

	public function getName()
	{
		return 'address';
	}
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'data_class' => 'Aueio\ClubBundle\Entity\Address',
		);
	}
}