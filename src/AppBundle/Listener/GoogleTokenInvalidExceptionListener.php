<?php
namespace AppBundle\Listener;

use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use AppBundle\Exception\GoogleTokenInvalidException;
use Symfony\Bridge\Twig\TwigEngine;
use Symfony\Component\HttpFoundation\Response;

class GoogleTokenInvalidExceptionListener 
{
	private $templating;
	
	public function __construct(TwigEngine $twig)
	{
		$this->templating = $twig;
	}
	
	public function onKernelException(GetResponseForExceptionEvent $event)
	{
		$exception = $event->getException();
		if ($exception instanceof GoogleTokenInvalidException) {
			$event->setResponse(new Response($this->templating->render('exception/google_token_invalid.html.twig')));
		}
	}
}