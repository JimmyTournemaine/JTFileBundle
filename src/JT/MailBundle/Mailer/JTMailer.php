<?php
namespace JT\MailBundle\Mailer;

use JT\MailBundle\Mailer\JTMailerInterface;
use JT\MailBundle\Exception\NoMessageToSendException;

/**
 * Mailer to easily send HTML and Plain text mail
 * @author Jimmy Tournemaine <jimmy.tournemaine@yaho.Fr>
 */
class JTMailer implements JTMailerInterface
{
	/**
	 * @var The templating service
	 */
	private $templating;

	/**
	 * @var The header of the HTML Message
	 */
	private $header;

	/**
	 * @var string The HTML content
	 */
	private $htmlBody;

	/**
	 * @var string The plain text content
	 */
	private $textBody;

	/**
	 * @var string The footer of the HTML Message
	 */
	private $footer;

	/**
	 * @var array An array of filenames to attach to the mail
	 */
	private $attachments;

	public function __construct($templating)
	{
		$this->templating = $templating;
	}
	
	public function sendMessage($subject, $from, $to)
	{
		if($this->htmlBody === null && $this->textBody === null) {
			throw new NoMessageToSendException();
		}

		$message = \Swift_Message::newInstance()
			->setSubject($subject)
			->setFrom($from)
			->setTo($to)
		;

		if($this->htmlBody === null) {
			$message->setBody($this->textBody, 'text/plain');
		} else {
			$message->setBody($this->header . $this->htmlBody . $this->footer, 'text/html');
			if($this->textBody !== null) {
				$this->addPart($this->textBody, 'text/plain');
			}
		}

		foreach($this->attachments as $attachment){
			$message->attach(\Swift_Attachment::fromPath($attachment));
		}

		return $this->mailer->send($message);
	}

	public function setHeaderTemplate($template, array $parameters = array())
	{
		$this->header = $this->templating->renderView($template, $parameters);
		return $this; 
	}

	public function setHtmlBodyTemplate($template, array $parameters = array())
	{
		$this->htmlBody = $this->templating->renderView($template, $parameters);
		return $this; 
	}

	public function setTextBodyTemplate($template, array $parameters = array())
	{
		$this->textBody = $this->templating->renderView($template, $parameters);
		return $this; 
	}

	public function setFooterTemplate($template, array $parameters = array())
	{
		$this->footer = $this->templating->renderView($template, $parameters);
		return $this; 
	}

	public function setHeader($content)
	{
		$this->header = $content;
		return $this;
	}

	public function setHtmlBody($content)
	{
		$this->htmlBody = $content;
		return $this;
	}

	public function setTextBody($content)
	{
		$this->textBody = $content;
		return $this;
	}

	public function setFooter($content)
	{
		$this->footer = $content;
		return $this;
	}

	public function attach($filename)
	{
		$this->attachments[] = $filename;
		return $this;
	}

	public function setAttachments(array $filenames)
	{
		$this->attachments = $filenames;
		return his;
	}
}
