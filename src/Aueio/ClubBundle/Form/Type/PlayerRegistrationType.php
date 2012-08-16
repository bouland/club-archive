<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Symfony\Component\Form\FormError,
 	Symfony\Component\Form\FormInterface,
	Symfony\Component\Form\FormBuilder,
	Symfony\Component\Form\CallbackValidator,
	FOS\UserBundle\Form\Type\RegistrationFormType;

class PlayerRegistrationType extends RegistrationFormType
{
	private $em;
	
	public function __construct($class, $em)
	{
		parent::__construct($class);
		$this->em = $em;
	}
	
	public function buildForm(FormBuilder $builder, array $options)
	{
		parent::buildForm($builder, $options);
		
		$builder->add('firstname', 'text');
		$builder->add('lastname', 'text');
		$builder->add('surname', 'text');
		$builder->add('phone', 'text');
		$builder->add('adress', 'textarea');
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
												'expanded'	=> true,
										));
		$builder->add('secret', 'text', array('property_path' => false));
		
		$builder->addValidator(new CallbackValidator(function(FormInterface $form)
		{
			//$config = $this->em->getRepository('AueioClubBundle:Config')->find(1);
			$secret = $form["secret"];
			//$answers= $config->getSecret();
			$answers = array("CYRILLE", "GILLES");
			if (!in_array(strtoupper($secret->getData()), $answers))
			{
				$secret->addError(new FormError('Wrong answer.'));
			}
		})
		);
	}

	public function getName()
	{
		return 'aueio_player_registration';
	}

}
