<?php
namespace CalendarBundle\Model;

use CalendarBundle\Entity\Meeting;
use CalendarBundle\Entity\Rendezvous;
use Symfony\Component\Asset\Exception\InvalidArgumentException;
use Symfony\Component\Validator\Constraints as Assert;
use CalendarBundle\Validator\Constraint\Event as AssertEvent;

/**
 * Represents a general event which can be converted depending of its values
 *
 * @author Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr>
 *
 * @AssertEvent()
 */
class EventFormData
{
    const MEETING = 'toMeeting';
    const RENDEZVOUS = 'toRendezvous';

    private static $TYPES = array('Meeting' => self::MEETING, 'Rendezvous'=> self::RENDEZVOUS);

    /**
     * @Assert\NotBlank()
     * @Assert\Length(min=3, max="255")
     */
    private $title;

    /**
     * @Assert\Length(min=10)
     */
    private $description;

    private $allDay;

    private $date;

    private $start;

    private $end;

    private $type;

    /**
     * @Assert\NotNull()
     */
    private $owner;

    private $users;

    private $contact;

    public function __construct()
    {
        $this->date = new \DateTime();
        $this->allDay = false;
    }

    private function setCommons(Event $event)
    {
        $event
            ->setTitle($this->title)
            ->setDescription($this->description)
            ->setAllDay($this->allDay)
        ;
        if ($this->allDay) {
            $event->setStart($this->date);
        } else {
            $event->setStart($this->start)->setEnd($this->end);
        }

        return $event;
    }

    private function toMeeting()
    {
        $m = new Meeting();
        return $this->setCommons($m)->setUsers($this->users);
    }

    private function toRendezvous()
    {
        $r = new Rendezvous();
        return $this->setCommons($r)->setUser($this->owner)->setContact($this->contact);
    }

    /**
     * Get a specific event with reflexivity
     */
    public function getEventEntity()
    {
        $converter = $this->type;
        $event = $this->$converter();
        return $this->setCommons($event);
    }

    static public function types(){
        return self::$TYPES;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    public function isAllDay()
    {
        return $this->allDay;
    }

    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
        return $this;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function setDate($date)
    {
        $this->date = $date;
        return $this;
    }

    public function getStart()
    {
        return $this->start;
    }

    public function setStart($start)
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd($end)
    {
        $this->end = $end;
        return $this;
    }

    public function getOwner()
    {
        return $this->owner;
    }

    public function setOwner($owner)
    {
        $this->owner = $owner;
        return $this;
    }

    public function getUsers()
    {
        return $this->users;
    }

    public function setUsers($users)
    {
        $this->users = $users;
        return $this;
    }

    public function getContact()
    {
        return $this->contact;
    }

    public function setContact($contact)
    {
        $this->contact = $contact;
        return $this;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        if (!in_array($type, self::$TYPES))
            throw new InvalidArgumentException();

        $this->type = $type;
        return $this;
    }
}