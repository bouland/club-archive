<?php
namespace Aueio\ClubBundle\Subscriber;

use Aueio\ClubBundle\Entity\Season;

use Symfony\Component\HttpKernel\Event\FilterControllerEvent,
	Symfony\Component\EventDispatcher\EventSubscriberInterface,
	Symfony\Component\HttpKernel\HttpKernelInterface,
	Symfony\Component\HttpKernel\KernelEvents;

class SeasonSubscriber implements EventSubscriberInterface
{
    private $season;
    
    public function __construct(Season $season = null){
        $this->season = $season;
    }
    
	public function onKernelController(FilterControllerEvent $event)
	{
		if (HttpKernelInterface::MASTER_REQUEST !== $event->getRequestType()) {
			return;
		}
		if (!is_array($controller = $event->getController())) {
			return;
		}
		//  filter controllers that have not EntityManager. (i.e Assetic, Render, etc ..)
		$class = get_class($controller[0]);
		if (mb_strpos($class, 'Aueio\ClubBundle\Controller') === false){
			return;
		}
		$em = $controller[0]->getDoctrine()->getEntityManager();
		if($controller[1] == 'deleteAction' || $controller[1] == 'showAction'){
			$em->getFilters()->disable('season');
			return;
		}
		$em->getFilters()->enable('season')->setParameter('season_id', $this->season->getId());
	}

	public static function getSubscribedEvents()
	{
		return array(KernelEvents::CONTROLLER => array('onKernelController', 1024));
	}	
}
