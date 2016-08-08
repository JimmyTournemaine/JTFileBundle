<?php
namespace UserBundle\EventListener;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use UserBundle\Controller\HavingTeamIsRequiredController;
use UserBundle\Exception\NoTeamException;

class CheckTeamListener
{
	private $tokenStorage;
	
	public function __construct(TokenStorage $tokenStorage)
	{
		$this->tokenStorage = $tokenStorage;
	}
	
	/**
	 * Denie access to users that has no team.
	 * A controller witch implements HavingTeamIsRequiredController needs to be protected
	 * 	with the firewall cause we need an authenticated user.
	 * 
	 * @param FilterControllerEvent $event
	 * @throws NoTeamException
	 */
	public function onKernelController(FilterControllerEvent $event)
	{
		$controller = $event->getController();
		
		if (!is_array($controller))
			return;
		
		if ($controller[0] instanceof HavingTeamIsRequiredController){
			if($this->tokenStorage->getToken()->getUser()->hasTeam() === false)
				throw new NoTeamException;
		}
	}
}