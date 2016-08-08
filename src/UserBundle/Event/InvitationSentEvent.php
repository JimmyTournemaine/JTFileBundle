<?php
namespace UserBundle\Event;

use UserBundle\Entity\Invitation;
use Symfony\Component\EventDispatcher\Event;

class InvitationSentEvent extends Event
{
	const NAME = 'invitation.sent';
	
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