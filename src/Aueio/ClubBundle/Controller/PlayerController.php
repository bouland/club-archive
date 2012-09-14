<?php

namespace Aueio\ClubBundle\Controller;



use FOS\UserBundle\Mailer\Mailer;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use Aueio\ClubBundle\Entity\Player;
use Aueio\ClubBundle\Form\Type\ContactType;
use Aueio\ClubBundle\Form\Type\PlayerType;
use Aueio\ClubBundle\Form\Handler\PlayerHandler;
/**
* @Route("/player")
*/

class PlayerController extends Controller
{

	/**
	* @Route("/view/{id}", requirements={"id" = "\d+"})
	*/
	public function viewAction(Player $player)
    {
    	$season = $this->get('context.season');
    	$em = $this->getDoctrine()->getEntityManager();
    	if($player->getSeasons()->contains($season)){
    		if($player->getTeam()){
    			$stats = $em->getRepository('AueioClubBundle:Action')->getPlayerStats($player);
    			$stats['total'] = $em->getRepository('AueioClubBundle:Role')->findPlayedGameByTeam($player->getTeam(), true);
    		}else{
    			$stats = array('play' => 0,'total' => 0);
    		}
    		if($stats['total'] > ($stats['play']+$stats['miss']))
    		{
    			$stats['error'] = $em->getRepository('AueioClubBundle:Game')->findWithoutActionByPlayer($player, $season);
    		}else{
    			$stats['error'] = FALSE;
    		}
    		$stats['available'] = TRUE;
    	}else{
    		$stats['available'] = FALSE;
    	}
    	return $this->render('AueioClubBundle:Player:view.html.twig', array('player' => $player, 'stats' =>$stats));
    }
    /**
     * @Route("/edit/{id}", requirements={"id" = "\d+"})
     */
    public function editAction(Player $player, Request $request)
    {
    	$builder = $this->createFormBuilder($player);
   		$builder->add('gender', 'choice', array(
				'choices'   => array('M' => 'Homme', 'F' => 'Femme'),
				'required'  => true,
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
   				'expanded'	=> false));
   		$builder->add('seasons', 'entity', array(
   				'class' 		=> 'AueioClubBundle:Season',
   				'expanded'		=> false,
   				'multiple'		=> true,
   		));
		if($this->get('security.context')->isGranted('ROLE_ADMIN'))
		{
   			$builder->add('roles', 'collection', array(
   					'type'   => 'choice',
   					'allow_add' => false,
   					'options'  => array(
   							'choices'  => array(
   									'ROLE_PLAYER' => 'Joueur',
   									'ROLE_LEADER' => 'Capitaine',
   									'ROLE_ADMIN' => 'Admin')
   							),
   					));
		}
   		$form =	$builder->getForm();
		
        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            if( $form->isValid() )
            {
				$player = $form->getData();
				$em = $this->getDoctrine()->getEntityManager();
    			$em->persist($player);
				$em->flush();
				return $this->redirect($this->generateUrl('aueio_club_player_view', array('id' => $player->getId())));
            }
    	}
    	return $this->render('AueioClubBundle:Player:edit.html.twig', array('form' => $form->createView()));
    }
    public function showAction()
    {
    	$player = $this->get('context.player');
        if (!is_object($player) || !$player instanceof Player) {
            throw new AccessDeniedException('This user does not have access to this section.');
        }
    	return $this->render('AueioClubBundle:Player:view.html.twig', array('player' => $player));
    }
    /**
     * @Route("/list")
     */
    public function listAction()
    {
    	$season = $this->get('context.season');
    	$player = $this->get('context.player');
    	if (is_object($player) || $player instanceof Player) {
    		$players = $this->getDoctrine()->getRepository('AueioClubBundle:Player')->findSeasonTeamMembers($player->getTeam(), $season);
    	}else{
    		$players = $this->getDoctrine()->getRepository('AueioClubBundle:Player')->findBySeason($season);
    	}
    	return $this->render('AueioClubBundle:Player:list.html.twig', array('players' => $players));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction(Player $player){
    	$em = $this->getDoctrine()->getEntityManager();
    	if ( $player == $this->get('context.player')) {
    		$em->remove($player);
	    	$em->flush();
	    	return $this->redirect($this->generateUrl('fos_user_security_logout'));
    	}elseif($this->get('security.context')->isGranted('ROLE_ADMIN')){
    		$em->remove($player);
    		$em->flush();
    		return $this->redirect($this->generateUrl('aueio_club_player_list'));
    	}else{
    		return $this->redirect($this->get('request')->headers->get('referer'));
    	}
    }
    
    /**
     * @Route("/contact/{id}", requirements={"id" = "\d+"})
     */
    public function contactAction(Player $player, Request $request)
    {
    	$from = $this->get('context.player');
    	if (!is_object($from) || !$from instanceof Player) {
    		throw new AccessDeniedException('This user does not have access to this section.');
    	}
    	$form = $this->createForm(new ContactType(), array('player' => $player));
    	 
    	$em = $this->getDoctrine()->getEntityManager();
    	
    	if( $request->getMethod() == 'POST' )
    	{
    		$form->bindRequest($request);
    	
    		if( $form->isValid() )
    		{
    			$this->get('aueio_club.mailer')->sendContactEmailToPlayer($player, $from, $form->getData());
    			$this->get('session')->setFlash('notice', $this->get('translator')->trans('email.message.send',array(),'AueioClubBundle'));
    			
    			return $this->redirect($this->generateUrl('aueio_club_player_view', array('id' => $player->getId())));
    		}
    	}

    	return $this->render('AueioClubBundle:Player:contact.form.html.twig', array('player' => $player, 'form' => $form->createView()));
    }
}
