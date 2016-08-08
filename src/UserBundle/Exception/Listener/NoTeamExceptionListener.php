<?php
namespace UserBundle\Exception\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use UserBundle\Exception\NoTeamException;
use Symfony\Component\HttpFoundation\RedirectResponse;

class NoTeamExceptionListener 
{
	private $router;
	
	public function __construct(Router $router)
	{
		$this->router = $router;
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$exception = $event->getException();
		if ($exception instanceof NoTeamException) {
			$event->setResponse(new RedirectResponse($this->router->generate('noteam')));
		}
	}
}