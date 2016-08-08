<?php
namespace UserBundle\Event;

use UserBundle\Entity\Invitation;
use Symfony\Component\EventDispatcher\Event;

class InvitationAcceptedEvent extends Event
{
	const NAME = 'invitation.accepted';
	
	protected $invitation;
	
	public function __construct(Invitation $invitation)
	{
		$this->invitation = $invitation;
	}
	
	public function getInvitation() 
	{
		return $this->invitation;
	}
	
	
	
}