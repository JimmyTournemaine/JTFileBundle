<?php
namespace CalendarBundle\Model;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Gedmo\Mapping\Annotation as Gedmo;
use CalendarBundle\Validator\Constraint\EventConstraint as AssertEvent;
use ADesigns\CalendarBundle\Entity\EventEntity;
/**
 * The superclass of database stored calendar events
 *
 * CalendarBundle\Model\Event is a part of The Simple CRM project
 *
 * updated : Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr> 2 mai 2016 18:24:44
 * @author Jimmy Tournemaine <jimmy.tournemaine@yahoo.fr>
 * @version 1.0
 *
 * @ORM\MappedSuperclass
 */
abstract class Event
{
	/**
	 * @var int
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="title", type="string", length=255)
     */
    protected $title;

    /**
     * @var string
     *
     * @ORM\Column(name="slug", type="string", length=126, unique=true)
     * @Gedmo\Slug(fields={"title"})
     */
    protected $slug;


    /**
     * @var string
     *
     * @ORM\Column(name="description", type="text", nullable=true)
     */
    protected $description;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="start", type="datetime")
     */
    protected $start;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="end", type="datetime", nullable=true)
     */
    protected $end;

    /**
     * @var bool
     *
     * @ORM\Column(name="all_day", type="boolean")
     */
    protected $allDay = false;

    /**
     * Convert the entity in ADesigns\CalendarBundle\Entity\EventEntity
     * @return ADesigns\CalendarBundle\Entity\EventEntity
     */
    public function toADesignsEventEntity()
    {
    	$entity = new EventEntity($this->getTitle(), $this->getStart(), $this->getEnd(), $this->getAllDay());
    	$entity->addField('slug', $this->slug);
    	$entity->addField('description', $this->description);

    	return $entity;
    }

    public function getId()
    {
        return $this->id;
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

    public function getSlug()
    {
        return $this->slug;
    }

    public function setSlug($slug)
    {
        $this->slug = $slug;
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

    public function getStart()
    {
        return $this->start;
    }

    public function setStart(\DateTime $start)
    {
        $this->start = $start;
        return $this;
    }

    public function getEnd()
    {
        return $this->end;
    }

    public function setEnd(\DateTime $end)
    {
        $this->end = $end;
        return $this;
    }

    public function getAllDay()
    {
        return $this->allDay;
    }

    public function setAllDay($allDay)
    {
        $this->allDay = $allDay;
        return $this;
    }
}

