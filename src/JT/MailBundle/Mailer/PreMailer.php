<?php
namespace JT\MailBundle\Mailer;

use Crossjoin\PreMailer\HtmlString;

class PreMailer extends HtmlString
{
	private $message;
	private $generateText;

	public function setMessage(\Swift_Message $message)
	{
		$this->message = $message;
		$this->setHtmlContent($message->getBody());
	}

	public function getMessage()
	{
		$this->message->setBody($this->getHtml(), 'text/html');
		if($this->generateText === true){
			$this->message->setBody($this->getText(), 'text/plain');
		}
		return $this->message;
	}

	public function __construct(array $options)
	{
		/* Set charset */
		$this->setCharset($options['charset']);

		/* Set HTML comments option */
		$this->setOption(
			self::OPTION_HTML_COMMENTS, 
			($options['remove_comments']) ? self::OPTION_HTML_COMMENTS_REMOVE : self::OPTION_HTML_COMMENTS_KEEP
		);

		/* Set style tag position option */
		$constName = 'OPTION_STYLE_TAG_' . strtoupper($options['style_tag']);
		$this->setOption(self::OPTION_STYLE_TAG, constant("self::$constName"));

		/* Set removing classes option */
		$this->setOption(
			self::OPTION_HTML_CLASSES,
			($options['remove_classes']) ? self::OPTION_HTML_CLASSES_REMOVE : self::OPTION_HTML_CLASSES_KEEP
		);

		/* Set line width */
		$this->setOption(self::OPTION_TEXT_LINE_WIDTH, $options['text_line_width']);

		/* Set CSS writer class */
		$this->setOption(self::OPTION_CSS_WRITER_CLASS, $options['css_writer_class']);

		/* Set if a text version must be generated */
		$this->generateText = $options['generate_text'];
	}
}


