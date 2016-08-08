<?php
namespace UserBundle\Event;

use UserBundle\Entity\Invitation;
use UserBundle\Entity\User;
use UserBundle\Entity\Team;
use Symfony\Component\EventDispatcher\Event;

class InvitationToJoinEvent extends Event
{
	const NAME = 'invitation.to_join';
	
	protected $admin;
	protected $team;
	protected $email;
	
	public function __construct(User $admin, Team $team, $email)
	{
		$this->admin = $admin;
		$this->team = $team;
		$this->email = $email;
	}
	
	public function getAdmin() 
	{
		return $this->admin;
	}
	
	public function getTeam() 
	{
		return $this->team;
	}
	
	public function getEmail() 
	{
		return $this->email;
	}
	
	
	
	
	
	
}