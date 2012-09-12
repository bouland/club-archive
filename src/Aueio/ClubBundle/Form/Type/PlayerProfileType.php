<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\HttpFoundation\Session\SessionInterface,
	FOS\UserBundle\Form\Type\ProfileFormType,
	Aueio\ClubBundle\Form\Type\AdressType;

class PlayerProfileType extends ProfileFormType
{
	private $_session;
	
	/**
	 * @param string $class The User class name
	 */
	public function __construct($class, SessionInterface $session)
	{
		parent::__construct($class);
		$this->_session = $session;
	
	}
	
	public function buildUserForm(FormBuilderInterface $builder, array $options)
	{
		parent::buildUserForm($builder, $options);
		
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
		if ($this->_session->get('context')->isGranted('ROLE_ADMIN')) {
			$builder->add('roles', 'choice', array(
					'choices'   => array('RIGHT' => 'Droitier', 'LEFT' => 'Gaucher')
			));
		}
		
	}

	public function getName()
	{
		return 'aueio_club_profile';
	}

}
