<?php
namespace JT\MailBundle\Event;

class MailSentEvent extends MailEvent
{
	private $sent;

	public function __construct(\Swift_Message $message, $sent)
	{
		parent::__construct($message);
		$this->sent = $sent;
	}

	public function setMessage(\Swift_Message $message)
	{
		throw new \LogicException("You can not edit the message after it was sent.");
	}

	public function isSent()
	{
		return $this->sent;
	}
}


