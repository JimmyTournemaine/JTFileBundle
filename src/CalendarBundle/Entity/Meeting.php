<?php
namespace CalendarBundle\Entity;

use CalendarBundle\Model\Event;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="event_meeting")
 * @ORM\Entity(repositoryClass="CalendarBundle\Repository\MeetingRepository")
 */
class Meeting extends Event
{
	/**
	 * @ORM\ManyToMany(targetEntity="UserBundle\Entity\User")
	 * @ORM\JoinTable(name="event_meeting_user")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $users;

	public function toADesignsEventEntity()
	{
		$entity = parent::toADesignsEventEntity();
		$entity->addField('users', $this->users);
		return $entity;
	}

	public function getUsers() {
		return $this->users;
	}

	public function addUser(\UserBundle\Entity\User $user)
	{
		$this->users[] = $user;
		return $this;
	}

	public function removeUser(\UserBundle\Entity\User $user)
	{
		$this->users->remove($user);
		return $this;
	}

	public function setUsers($users)
	{
	    foreach ($users as $user)
	        $this->addUser($user);
	    return $this;
	}


}