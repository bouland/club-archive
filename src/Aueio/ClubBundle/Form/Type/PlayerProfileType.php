<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Doctrine\ORM\EntityRepository,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\Security\Core\SecurityContextInterface,
	Symfony\Component\Security\Core\Validator\Constraint\UserPassword,
	FOS\UserBundle\Form\Type\ProfileFormType,
	Aueio\ClubBundle\Form\Type\AdressType;

class PlayerProfileType extends ProfileFormType
{
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder->add('username', null, array('label' => 'form.username', 'translation_domain' => 'FOSUserBundle'))
				->add('email', 'email', array('label' => 'form.email', 'translation_domain' => 'FOSUserBundle'))
		;
		$builder->add('firstname', 'text');
		$builder->add('lastname', 'text');
		$builder->add('phone', 'text');
		$builder->add('address',  new AddressType());
		$builder->add('gender', 'choice', array(
				'choices'   => array('M' => 'Homme', 'F' => 'Femme'),
				'required'  => true,
		));
		$builder->add('car', 'checkbox', array(
				'data' => true,
				'required' => false,
		));
		$builder->add('position', 'choice', array(
				'choices'   => array(	'GOAL' => 'Gardien',
										'PIVOT' => 'Pivot',
										'CENTER' => 'Demi',
										'BACK' => 'Arrière',
										'WING' => 'Ailier')
		));
		$builder->add('hand', 'choice', array(
				'choices'   => array('RIGHT' => 'Droitier', 'LEFT' => 'Gaucher')
		));
		
		$builder->add('team', 'entity', array(
												'class' 		=> 'AueioClubBundle:Team',
												'property'     	=> 'name',
												'expanded'	=> false,
										));
		$builder->add('seasons', 'entity', array(
				'class' 		=> 'AueioClubBundle:Season',
				'query_builder' => function(EntityRepository $er) {
				return $er->createQueryBuilder('s')
				->orderBy('s.start_date', 'DESC');
				},
				'expanded'		=> false,
				'multiple'		=> true,
		));
		$builder->add('current_password', 'password', array(
				'mapped' => false,
				'constraints' => new UserPassword(),
		));
		
	}

	public function getName()
	{
		return 'aueio_club_profile';
	}

}
