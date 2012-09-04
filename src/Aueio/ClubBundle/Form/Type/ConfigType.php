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
		$builder->add('secret', 'text');
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