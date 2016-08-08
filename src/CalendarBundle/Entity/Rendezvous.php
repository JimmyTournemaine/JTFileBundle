<?php
namespace CalendarBundle\Entity;

use CalendarBundle\Model\Event;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Table(name="event_rendezvous")
 * @ORM\Entity(repositoryClass="CalendarBundle\Repository\RendezvousRepository")
 */
class Rendezvous extends Event
{
	/**
	 * @ORM\ManyToOne(targetEntity="UserBundle\Entity\User")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $user;

	/**
	 * @ORM\ManyToOne(targetEntity="AppBundle\Entity\Contact")
	 * @ORM\JoinColumn(nullable=false)
	 */
	private $contact;

	public function toADesignsEventEntity()
	{
		$entity = parent::toADesignsEventEntity();
		$entity->addField('user', $this->user);
		$entity->addField('contact', $this->contact);
		return $entity;
	}

	public function getUser() {
		return $this->user;
	}

	public function setUser(\UserBundle\Entity\User $user) {
		$this->user = $user;
		return $this;
	}

	public function getContact() {
		return $this->contact;
	}

	public function setContact(\AppBundle\Entity\Contact $contact) {
		$this->contact = $contact;
		return $this;
	}



}