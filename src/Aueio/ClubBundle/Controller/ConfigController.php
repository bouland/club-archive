<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aueio\ClubBundle\Entity\Config;
use Aueio\ClubBundle\Form\Type\ConfigType;
use Aueio\ClubBundle\Form\Handler\ConfigHandler;
/**
 * @Route("/config")
 */

class ConfigController extends Controller
{
	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"}, defaults={"id"=1})
	*/
	public function viewAction(Config $config)
    {
	   	return $this->render('AueioClubBundle:Config:view.html.twig', array('config' => $config));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction(Config $config){
    	$em = $this->getDoctrine()->getEntityManager();
		$em->remove($config);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('aueio_club_config_new'));
    }
    /**
     * @Route("/new")
     **/
    public function newAction(Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$config = new Config();
    	$form = $this->createForm(new ConfigType, $config);
    	
    	$formHandler = new ConfigHandler($form, $request, $em);

        // On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process() )
        {
            return $this->redirect($this->generateUrl('aueio_club_config_view', array('id' => $config->getId())));
        }
        return $this->render('AueioClubBundle:Config:new.html.twig', array(
    			'form' => $form->createView(),
    			'theme' => 'AueioClubBundle::form.theme.html.twig'
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction(Config $config, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$form = $this->createForm(new ConfigType, $config);
    	 
    	$formHandler = new ConfigHandler($form, $request, $em);

    	if( $formHandler->process() )
    	{
    		return $this->redirect($this->generateUrl('aueio_club_config_view', array('id' => $config->getId())));
    	}
    	
    	return $this->render('AueioClubBundle:Config:edit.html.twig', array(
    			'config' => $config,
    			'form' => $form->createView(),
    			    			'theme' => 'AueioClubBundle::form.theme.html.twig'
    	));
    }
    
}