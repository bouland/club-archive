<?php
// src/Auieo/ClubBundle/Form/Type/RoleType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		if($options['intention'] == 'create' || $options['intention'] == 'edit'){
			$builder->add('team', 'entity', array(
					'class' 		=> 'AueioClubBundle:Team',
					'property'     	=> 'name',
					'expanded'		=> false,
			));
			
		}else{
			$builder->add('score', 'integer');
		}
	}

	public function getName()
	{
		return 'role';
	}
	
	public function setDefaultOptions(OptionsResolverInterface $resolver)
	{
		$resolver->setDefaults(array(
				'intention' => 'create',
				'data_class' => 'Aueio\ClubBundle\Entity\Role',
		));
	}
}
