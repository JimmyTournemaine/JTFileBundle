<?php
namespace JT\MailBundle\Event;

class MailEvent
{
	protected $message;

	public function __construct(\Swift_Message $message)
	{
		$this->message = $message;
	}

	public function getMessage()
	{
		return $this->message;
	}

	public function setMessage(\Swift_Message $message)
	{
		$this->message = $message;
	}
}