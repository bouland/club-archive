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
	public function viewAction($id)
    {
    	$config = $this->getDoctrine()
				    	->getRepository('AueioClubBundle:Config')
				    	->find($id);
    	if (!$config) {
    		throw $this->createNotFoundException('No config found for id '.$id);
    	}
    	
    	return $this->render('AueioClubBundle:Config:view.html.twig', array('config' => $config));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$config = $em->getRepository('AueioClubBundle:Config')->find($id);
    	if (!$config) {
    		throw $this->createNotFoundException('No config found for id '.$id);
    	}
    	 
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
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$config = $em->getRepository('AueioClubBundle:Config')->find($id);
    	if (!$config) {
    		throw $this->createNotFoundException('No config found for id '.$id);
    	}
    	
    	$form = $this->createForm(new ConfigType, $config);
    	 
    	$formHandler = new ConfigHandler($form, $request, $em);
    	 
    	// On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
    	if( $formHandler->process() )
    	{
    		return $this->redirect($this->generateUrl('aueio_club_config_view', array('id' => $config->getId())));
    	}
    
    
    	return $this->render('AueioClubBundle:Config:edit.html.twig', array('config' => $config, 'form' => $form->createView()));
    }
    
}