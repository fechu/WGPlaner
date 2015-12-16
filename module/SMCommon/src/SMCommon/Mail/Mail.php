<?php

namespace SMCommon\Mail;


class Mail {
	
	/**
	 * @var string The subject of the email.
	 */
	protected $subject;

	/**
	 * @var string The content of the email.
	 */
	protected $content;

	/**
	 * @var string[] Email addresses of the recipients.
	 */
	protected $recipients;

	/**
	 * @param $recipients string|string[] The recipients of the email.
	 */
	public function setRecipients($recipients)
	{
		if (!is_array($recipients)) {
			$this->recipients = [$recipients];
		}
		else {
			$this->recipients = $recipients;
		}
	}

	public function getRecipients()
	{
		return $this->recipients;
	}

	public function setContent($content)
	{
		$this->content = $content;
	}

	public function getContent()
	{
		return $this->content;
	}

	public function setSubject($subject)
	{
		$this->subject = $subject;
	}

	public function getSubject()
	{
		return $this->subject;
	}

	public function getJson()
	{
		$data = [
			'subject' => $this->getSubject(),
			'content' => $this->getContent(),
			'recipients' => $this->getRecipients(),
		];
		return json_encode($data);
	}
}
