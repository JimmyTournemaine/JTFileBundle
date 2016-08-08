<?php
namespace CalendarBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Event extends Constraint
{
	public $atLeastOneUserMessage = 'calendar.event.constraint.atLeastOneUserMessage';
	public $invalidDateMessage = 'calendar.event.constraint.invalidDateMessage';

	public function getTargets()
	{
		return Constraint::CLASS_CONSTRAINT;
	}
}