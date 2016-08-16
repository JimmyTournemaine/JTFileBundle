<?php
namespace JT\MailBundle\Mailer;

/*
 * JTMailerInterface describe how to send mail easily
 */
interface JTMailerInterface	
{
	/**
	 * Send the message
	 * @parameter $subject The message subject
	 * @parameter $from The sender of the message
	 * @parameter $to 	The receiver of the message
	 * @return boolean If message has been send successfully
	 */
	public function sendMessage($subject, $from, $to);

	/**
	 * Set the header part (for html only)
	 * @parameter $template The template to render html
	 * @parameter $parameters An array of parameters to use to render the header view
	 */
	public function setHeaderTemplate($template, array $parameters = array());

	/**
	 * Set the body part for HTML message
	 * @parameter $template The template to render html
	 * @parameter $parameters An array of parameters to use to render the body view
	 */
	public function setHtmlBodyTemplate($template, array $parameters = array());

	/**
	 * Set the body part for text message
	 * @parameter $template The template to render text message
	 * @parameter $parameters An array of parameters to use to render the body view
	 */
	public function setTextBodyTemplate($template, array $parameters = array());

	/**
	 * Set the footer part (for html only)
	 * @parameter $template The template to render html
	 * @parameter $parameters An array of parameters to use to render the footer view
	 */
	public function setFooterTemplate($template, array $parameters = array());

	/**
	 * Set the header part (for html only)
	 * @parameter $content The header HTML content
	 */
	public function setHeader($content);

	/**
	 * Set the body part (for html only)
	 * @parameter $content The header HTML content
	 */
	public function setHtmlBody($content);

	/**
	 * Set the body part text
	 * @parameter $content The body text content
	 */
	public function setTextBody($content);

	/**
	 * Set the footer part (for html only)
	 * @parameter $content The footer HTML content
	 */
	public function setFooter($content);
	
}
