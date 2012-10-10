<?php
// src/Auieo/ClubBundle/Form/Type/RoleType.php
namespace Aueio\ClubBundle\Form\Type;

use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class RoleType extends FormType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$config = $this->em->getRepository('AueioClubBundle:Config')->find(1);
		if($config){
			$preferred = array($config->getTeamFocus());
		}else{
			$preferred = array();
		}
		if($options['intention'] == 'create'){
			$builder->add('team', 'entity', array(
					'class' 		=> 'AueioClubBundle:Team',
					'query_builder' => function(EntityRepository $er) {
											return $er->createQueryBuilder('t')
											->orderBy('t.name', 'ASC');
										},
					'preferred_choices' => $preferred,
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
