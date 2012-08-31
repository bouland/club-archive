<?php

namespace Aueio\ClubBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

/**
 * Initializes the locale based on the current request.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 */
class SessionListener implements EventSubscriberInterface
{

	public function onKernelRequest(GetResponseEvent $event)
	{
		$request = $event->getRequest();

		$request->setDefaultLocale($this->defaultLocale);

		if ($locale = $request->attributes->get('_locale')) {
			$request->setLocale($locale);
		}

		if (null !== $this->router) {
			$this->router->getContext()->setParameter('_locale', $request->getLocale());
		}
	}

	public static function getSubscribedEvents()
	{
		return array(
				// must be registered after the Router to have access to the _locale
				KernelEvents::REQUEST => array(array('onKernelRequest', 16)),
		);
	}
}
