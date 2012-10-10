<?php
// src/Auieo/ClubBundle/Form/Type/RoleType.php
namespace Aueio\ClubBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

abstract class FormType extends AbstractType
{
	protected  $em;
	
	public function __construct(EntityManager $em){
		$this->em = $em;
	}
}