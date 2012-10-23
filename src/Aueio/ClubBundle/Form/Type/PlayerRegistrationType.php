<?php
// src/Auieo/ClubBundle/Form/Type/PlayerType.php
namespace Aueio\ClubBundle\Form\Type;

use Aueio\ClubBundle\Entity\Config;

use Doctrine\ORM\EntityManager,
	Symfony\Component\Form\FormError,
 	Symfony\Component\Form\FormInterface,
	Symfony\Component\Form\FormBuilderInterface,
	Symfony\Component\Form\CallbackValidator,
	FOS\UserBundle\Form\Type\RegistrationFormType,
	Aueio\ClubBundle\Form\Type\AdressType;

class PlayerRegistrationType extends RegistrationFormType
{
	private $em;
	
	public function __construct($class, EntityManager $em){
		parent::__construct($class);
		$this->em = $em;
	}
	
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
            ->add('username', null)
            ->add('email', 'email')
            ->add('plainPassword', 'repeated', array(
                'type' => 'password',
            ))
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
		$config = $this->em->getRepository('AueioClubBundle:Config')->find(1);
		if($config){
			$preferred = array($config->getTeamFocus());
		}else{
			$preferred = array();
		}
		$builder->add('team', 'entity', array(
												'class' 		=> 'AueioClubBundle:Team',
												'property'     	=> 'name',
												'expanded'	=> false,
												'preferred_choices' => $preferred,
										));
		if($config && $config instanceof Config){
			$answers = $config->getSecretAnswers();
			$question = $config->getSecretQuestion();
			$clue = $config->getSecretClue();
		}else{
			$answers = array("GILLES", "CYRIL");
			$question = "Donnez le prénom d'un de no gardiens.";
			$clue = "Indice : C**** ou G*****";
		}
		
		$builder->add('secret', 'text', array(
				'property_path' => false,
				'label' => $question
		));
		
		
		
		
		$builder->addValidator(new CallbackValidator(function(FormInterface $form) use ($answers, $clue)
		{
			//$config = $this->em->getRepository('AueioClubBundle:Config')->find(1);
			$secret = $form["secret"];
			//$answers= $config->getSecret();
			if (!in_array(strtoupper($secret->getData()), $answers))
			{
				$secret->addError(new FormError('Essaye encore avec cet indice : '. $clue));
			}
		})
		);
	}

	public function getName()
	{
		return 'aueio_club_registration';
	}

}
