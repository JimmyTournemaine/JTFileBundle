<?php
namespace CalendarBundle\Validator\Constraint;

use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;
use CalendarBundle\Model\EventFormData;

class EventValidator extends ConstraintValidator
{
    /**
     * The title, the description and the owner can be validated easily
     * However, the most of the fields are complicated to validate, we have to validate them here.
     *
     * {@inheritDoc}
     * @see \Symfony\Component\Validator\ConstraintValidatorInterface::validate()
     */
	public function validate($event, Constraint $constraint)
	{
	    $now = new \DateTime();

	    /* Event dates validation */
	    if ($event->isAllDay()) {
	        if (!($event->getDate() instanceof \DateTime) || $event->getDate() < $now){
	            $this->context
	               ->buildViolation($constraint->invalidDateMessage)
	               ->atPath('date')
	               ->addViolation()
	           ;
	        }
	    } else {
	        if (!($event->getStart() instanceof \DateTime) || $event->getStart() < $now) {
	            $this->context
	               ->buildViolation($constraint->invalidDateMessage)
	               ->atPath('start')
	               ->addViolation()
	            ;
	        } elseif (!($event->getEnd() instanceof \DateTime) || $event->getEnd() < $now || $event->getEnd() < $event->getStart()) {
	            $this->context
    	            ->buildViolation($constraint->invalidDateMessage)
    	            ->atPath('end')
    	            ->addViolation()
	            ;
	        }
	    }

		/* Event type validation */
	    if($event->getType() == EventFormData::MEETING){
	       if (sizeof($event->getUsers()) < 1) {
	           $this->context
    	           ->buildViolation($constraint->atLeastOneUserMessage)
    	           ->atPath('end')
    	           ->addViolation()
	           ;
	       }
	    }

	}

	public function getTargets()
	{
		return self::CLASS_CONSTRAINT;
	}
}