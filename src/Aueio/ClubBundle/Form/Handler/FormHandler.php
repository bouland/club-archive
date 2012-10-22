<?php 
// src/Auieo/ClubBundle/Form/Handler/FormHandler.php

namespace Aueio\ClubBundle\Form\Handler;


use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManager;


class FormHandler
{
	protected $form;
	protected $request;
	protected $em;

	public function __construct(Form $form, Request $request, EntityManager $em)
	{
		$this->form    = $form;
		$this->request = $request;
		$this->em      = $em;
	}

	public function process($create = true)
	{
		if( $this->request->getMethod() == 'POST' )
		{
			$this->form->bindRequest($this->request);

			if( $this->form->isValid() )
			{
				return $this->onSuccess($this->form->getData(), $create);
			}
		}

		return false;
	}


}
