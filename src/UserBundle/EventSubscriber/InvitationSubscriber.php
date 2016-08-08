<?php
namespace UserBundle\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use UserBundle\Event\InvitationSentEvent;
use UserBundle\Event\InvitationToJoinEvent;
use Symfony\Component\Translation\DataCollectorTranslator;
use AppBundle\Mailer\Mailer;

class InvitationSubscriber implements EventSubscriberInterface
{
	private $mailer;
	private $translator;
	
	public function __construct(Mailer $mailer, DataCollectorTranslator $trans)
	{
		$this->mailer = $mailer;
		$this->translator = $trans;
	}
	
	public static function getSubscribedEvents()
	{
		return array(
				InvitationSentEvent::NAME => array(
						array('onInvitationSent', 0)
				),
				InvitationToJoinEvent::NAME => array(
						array('onInvitationToJoin', 0)
				)
		);
	}
	
	public function onInvitationSent(InvitationSentEvent $event)
	{
		$invitation = $event->getInvitation();
		if ($invitation->isTeamRequest())
		{
			$subject = $this->translator->trans('invitation.by_team.mail.subject');
			$templateName = 'invitation/sent_by_team_email';
			$this->mailer
				->setSubject($subject)
				->setBody("$templateName.txt.twig", "$templateName.html.twig", array(
						'invitation' => $invitation,
				))
				->setTo($invitation->getUser()->getEmail())
				->send()
			;
		}
	}
	
	public function onInvitationToJoin(InvitationToJoinEvent $event)
	{
		$subject = $this->translator->trans('invitation.join.mail.subject');
		$templateName = 'invitation/join_email';
		$mail = $this->mailer
			->setSubject($subject)
			->setBody("$templateName.txt.twig", "$templateName.html.twig", array(
					'admin' => $event->getAdmin(),
					'team' => $event->getTeam(),
			))
			->setTo($event->getEmail())
			->send()
		;
	}
}