<?php
// src/Auieo/ClubBundle/Form/Type/TeamType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class TeamType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('name', 'text');
		$builder->add('adress', 'textarea');
		$builder->add('contacts', 'entity', array(
												'class' 		=> 'AueioClubBundle:Player',
												'property'     	=> 'displayname',
												'expanded'		=> true,
												'multiple'		=> true,
										));	}

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