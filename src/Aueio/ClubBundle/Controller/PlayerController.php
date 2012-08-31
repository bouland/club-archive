<?php

namespace Aueio\ClubBundle\Controller;



use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Validator\Constraints\DateTime;
use Aueio\ClubBundle\Entity\Player;
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
    	$config = $this->getDoctrine()->getEntityManager()->getRepository('AueioClubBundle:Config')->find(1);
    	if (!$config) {
    		throw $this->createNotFoundException('No config found');
    	}
    	$team_focus = $config->getTeamFocus();
    	if (!$team_focus) {
    		throw $this->createNotFoundException('No focus team  found in config');
    	}
    	
    	$players = $this->getDoctrine()->getRepository('AueioClubBundle:Player')->findSeasonAll($team_focus, $season_id);
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
    	$em = $this->getDoctrine()->getEntityManager();
    	if($player->getTeam()){
    		$stats = $em->getRepository('AueioClubBundle:Action')->getStats($player->getId());
    		$stats['total'] = count($player->getTeam()->getRoles());
    	}else{
    		$stats = array('play' => 0,'total' => 0);
    	}
    	if($stats['total'] > ($stats['play']+$stats['miss']))
    	{
    		$stats['error'] = $em->getRepository('AueioClubBundle:Game')->findWithoutActionByPlayer($player->getId());
    	}else{
    		$stats['error'] = 0;
    	}
    	return $this->render('AueioClubBundle:Player:stats.html.twig', array('stats' =>$stats));
    }
    
}
