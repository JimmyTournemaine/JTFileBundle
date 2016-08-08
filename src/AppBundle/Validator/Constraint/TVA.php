<?php
namespace AppBundle\Validator\Constraint;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class TVA extends Constraint
{
	public $message = 'validation.constraint.tva';
	
	public function getTargets()
	{
		return Constraint::CLASS_CONSTRAINT;
	}
}