<?php
namespace UserBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UserBundle\Event\TeamDeletedEvent;
use Symfony\Component\Translation\DataCollectorTranslator;
use AppBundle\Service\Archive;
use AppBundle\Mailer\Mailer;

class TeamSubscriber implements EventSubscriberInterface
{
	private $archive;
	private $mailer;
	private $translator;
	
	public function __construct(Archive $archive, Mailer $mailer, DataCollectorTranslator $trans)
	{
		$this->archive = $archive;
		$this->mailer = $mailer;
		$this->translator = $trans;
	}
	
	public static function getSubscribedEvents()
	{
		return array(
				TeamDeletedEvent::NAME => array(
						array('onTeamDeleted', 0),
				),
		);
	}
	
	public function onTeamDeleted(TeamDeletedEvent $event)
	{
		$team = $event->getTeam();
		$members = array_merge($team->getMembers()->toArray(), $team->getAdmins()->toArray());
		// Archive data
		$id = $this->archive->generate($team);
		
		// Send retrieving url to team members
		$subject = $this->translator->trans('team.delete.mail.subject');
		$this->mailer
			->setSubject($subject)
			->setBody('team/deleted_email.txt.twig', 'team/deleted_email.html.twig', array(
					'team' => $team,
					'id' => $id,
			))
			->setTo(array_map(function($member){ return $member->getEmail(); }, $members))
			->send()
		;
	}
}