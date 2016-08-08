<?php
namespace CalendarBundle\EventListener;

use Doctrine\ORM\EntityManager;
use ADesigns\CalendarBundle\Event\CalendarEvent;
use Symfony\Bundle\FrameworkBundle\Routing\Router;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use CalendarBundle\Entity\Rendezvous;

class CalendarEventListener
{
	private $em;
	private $token;
	private $router;

	public function __construct(EntityManager $manager, TokenStorage $token, Router $router)
	{
		$this->em = $manager;
		$this->token = $token;
		$this->router = $router;
	}

	public function loadEvents(CalendarEvent $event)
	{
	    $user = $this->token->getToken()->getUser();
		$start = $event->getStartDatetime();
		$end = $event->getEndDatetime();

		$userEvents = array_merge(
		    $this->em->getRepository("CalendarBundle:Meeting")->findByUserBetween($user, $start, $end),
		    $this->em->getRepository("CalendarBundle:Rendezvous")->findByUserBetween($user, $start, $end)
		);

		foreach ($userEvents as $rdv)
		{
			$entity = $rdv->toADesignsEventEntity();
			//$entity->setUrl($this->router->generate('event_show', array('slug' => $rdv->getSlug())));

			$event->addEvent($entity);
		}
	}
}