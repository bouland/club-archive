<?php
// src/Auieo/ClubBundle/Form/Type/RoleType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

class RoleType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		
		if($options['form'] == 'new' || $options['form'] == 'edit'){
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
	
	public function getDefaultOptions(array $options)
	{
		return array(
				'form' => 'new',
				'data_class' => 'Aueio\ClubBundle\Entity\Role',
		);
	}
}
