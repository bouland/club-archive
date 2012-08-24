<?php

namespace Aueio\ClubBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Aueio\ClubBundle\Entity\Season;
use Aueio\ClubBundle\Form\Type\SeasonType;
use Aueio\ClubBundle\Form\Handler\SeasonHandler;
/**
 * @Route("/season")
 */

class SeasonController extends Controller
{
	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"}, defaults={"id"=1})
	*/
	public function viewAction($id)
    {
    	$season = $this->getDoctrine()
				    	->getRepository('AueioClubBundle:Season')
				    	->find($id);

    	return $this->render('AueioClubBundle:Season:view.html.twig', array('season' => $season));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$seasons = $this->getDoctrine()->getRepository('AueioClubBundle:Season')->findAllOrdered();
    	return $this->render('AueioClubBundle:Season:list.html.twig', array('seasons' => $seasons));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction($id){
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$season = $em->getRepository('AueioClubBundle:Season')->find($id);
    	if (!$season) {
    		throw $this->createNotFoundException('No season found for id '.$id);
    	}
    	 
    	$em->remove($season);
    	$em->flush();
    	 
    	return $this->redirect($this->generateUrl('aueio_club_season_new'));
    }
    /**
     * @Route("/new")
     **/
    public function newAction(Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    	$season = new Season();
    	
    	$date = new \DateTime('now');
    	$season->setStartDate(\DateTime::createFromFormat("Ymd",$date->format("Y") . "0901"));
    	$season->setEndDate(\DateTime::createFromFormat("Ymd", ($date->format("Y") + 1). "0630"));
    	
    	$form = $this->createForm(new SeasonType, $season);
    	
    	$formHandler = new SeasonHandler($form, $request, $em);

        // On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
        if( $formHandler->process() )
        {
            return $this->redirect($this->generateUrl('aueio_club_season_view', array('id' => $season->getId())));
        }
        return $this->render('AueioClubBundle:Season:new.html.twig', array(
    			'form' => $form->createView(),
    			'theme' => 'AueioClubBundle::form.theme.html.twig'
    	));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     **/
    public function editAction($id, Request $request)
    {
    	$em = $this->getDoctrine()->getEntityManager();
    
    	$season = $em->getRepository('AueioClubBundle:Season')->find($id);
    	if (!$season) {
    		throw $this->createNotFoundException('No season found for id '.$id);
    	}
    	
    	$form = $this->createForm(new SeasonType, $season);
    	 
    	$formHandler = new SeasonHandler($form, $request, $em);
    	 
    	// On exécute le traitement du formulaire. S'il retourne true, alors le formulaire a bien été traité
    	if( $formHandler->process() )
    	{
    		return $this->redirect($this->generateUrl('aueio_club_season_view', array('id' => $season->getId())));
    	}
    
    
    	return $this->render('AueioClubBundle:Season:edit.html.twig', array(
    			'season' => $season,
    			'form' => $form->createView(),
    			    			'theme' => 'AueioClubBundle::form.theme.html.twig'
    	));
    }
    
}