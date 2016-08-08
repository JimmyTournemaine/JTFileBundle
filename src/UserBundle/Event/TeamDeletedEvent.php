<?php
namespace UserBundle\Event;

use UserBundle\Entity\Team;
use Symfony\Component\EventDispatcher\Event;

class TeamDeletedEvent extends Event
{
	const NAME = 'team.deleted';
	
	protected $team;
	
	public function __construct(Team $team)
	{
		$this->team = $team;
	}
	
	public function getTeam() 
	{
		return $this->team;
	}
}