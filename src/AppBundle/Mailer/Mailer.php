<?php
namespace AppBundle\Mailer;

use Symfony\Bundle\TwigBundle\TwigEngine;

class Mailer
{
	private $mailer;
	private $templating;
	private $message;
	
	public function __construct(\Swift_Mailer $mailer, TwigEngine $twig, $fromEmail, $fromName)
	{
		$this->mailer = $mailer;
		$this->templating = $twig;
		$this->message = \Swift_Message::newInstance();
		$this->message->setFrom($fromEmail, $fromName);
	}
	
	public function setSubject($subject)
	{
		$this->message->setSubject($subject);
		
		return $this;
	}
		
	public function setTo($addresses, $name = null)
	{
		$this->message->setTo($addresses, $name);
		
		return $this;
	}
	
	public function setBody($txtTemplate, $htmlTemplate = null, array $vars = array())
	{
		$this->message->setBody($this->templating->render($txtTemplate, $vars));
		
		if ($htmlTemplate != null) {
			$header = $this->templating->render("email/header.html.twig");
			$body = $this->templating->render($htmlTemplate, $vars);
			$footer = $this->templating->render("email/footer.html.twig");
			
			$this->message->addPart($header.$body.$footer, 'text/html');
		}
		
		return $this;
	}
	
	public function send()
	{
		return $this->mailer->send($this->message);
	}
}