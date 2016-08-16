<?php
namespace JT\MailBundle\EventListener;

use JT\MailBundle\Event\MailSentEvent;
use JT\MailBundle\Mailer\PreMailer;

class MailPreSendingListener
{
	private $preMailer;

	public function __construct(PreMailer $preMailer)
	{
		$this->preMailer = $preMailer;
	}

	/**
	 * Extract CSS and move it inline
	 */
	public function onPreSending(MailSentEvent $event, array $options)
	{
		$this->preMailer->setMessage($event->getMessage());
		$event->setMessage($this->preMailer->getMessage());
	}
}
