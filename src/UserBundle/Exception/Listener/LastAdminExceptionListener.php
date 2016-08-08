<?php
namespace UserBundle\Exception\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use UserBundle\Exception\LastAdminException;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\HttpFoundation\Response;

class LastAdminExceptionListener 
{
	private $templating;
	
	public function __construct(TwigEngine $twig)
	{
		$this->templating = $twig;
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$exception = $event->getException();
		if ($exception instanceof LastAdminException) {
			$event->setResponse(new Response($this->templating->render('exception/last_admin.html.twig'), 202));
		}
	}
}