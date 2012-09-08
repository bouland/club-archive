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
    	return $this->render('AueioClubBundle:Player:view.html.twig', array('player' => $player));
    }
    
    public function showAction()
    {
    	$player = $this->container->get('security.context')->getToken()->getUser();
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
    	$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$player = $this->container->get('security.context')->getToken()->getUser();
    	if (is_object($player) || $player instanceof Player) {
    		$players = $this->getDoctrine()->getRepository('AueioClubBundle:Player')->findSeasonTeamMembers($player->getTeam(), $season_id);
    	}else{
    		$players = $this->getDoctrine()->getRepository('AueioClubBundle:Player')->findBySeason($season_id);
    	}
    	return $this->render('AueioClubBundle:Player:list.html.twig', array('players' => $players));
    }
    /**
     * @Route("/delete/{id}", requirements={"id" = "\d+"})
     **/
    public function deleteAction(Player $player){
    	$em = $this->getDoctrine()->getEntityManager();
    	if ( $player == $this->get('security.context')->getToken()->getUser()) {
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
    public function statsAction(Player $player)
    {
    	$season_id = $this->container->get('request')->getSession()->get('season_id');
    	$em = $this->getDoctrine()->getEntityManager();
    	$season = $em->getRepository('AueioClubBundle:Season')->find($season_id);
    	if($player->getSeasons()->contains($season)){
	    	if($player->getTeam()){
	    		$stats = $em->getRepository('AueioClubBundle:Action')->getStats($player->getId());
	    		$stats['total'] = count($player->getTeam()->getRoles());
	    	}else{
	    		$stats = array('play' => 0,'total' => 0);
	    	}
	    	if($stats['total'] > ($stats['play']+$stats['miss']))
	    	{
	    		$stats['error'] = $em->getRepository('AueioClubBundle:Game')->findWithoutActionByPlayer($player->getId(), $season_id);
	    	}else{
	    		$stats['error'] = FALSE;
	    	}
	    	$stats['available'] = TRUE;
    	}else{
    		$stats['available'] = FALSE;
    	}
    	return $this->render('AueioClubBundle:Player:stats.html.twig', array('stats' =>$stats));
    		
    }
    /**
     * @Route("/contact/{id}", requirements={"id" = "\d+"})
     */
    public function contactAction(Player $player, Request $request)
    {
    	$from = $this->container->get('security.context')->getToken()->getUser();
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
    			$this->get('session')->setFlash('notice', $this->get('translator')->trans('message.email.ok'));
    			
    			return $this->redirect($this->generateUrl('aueio_club_player_view', array('id' => $player->getId())));
    		}
    	}

    	return $this->render('AueioClubBundle:Player:contact.form.html.twig', array('player' => $player, 'form' => $form->createView()));
    }
}
