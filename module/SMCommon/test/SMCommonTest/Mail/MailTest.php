<?php
/**
 * @file MailTest.php
 * @date Dec 12, 2015
 * @author Sandro Meier
 */

namespace SMCommonTest\Mail;

use SMCommon\Mail\Mail;

class MailTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var Mail
	 */
	protected $mail;

	public function setUp()
	{
		$this->mail = new Mail();
	}

	public function testSetRecipints()
	{
		$rec = ['foo@bar.ch'];
		$this->mail->setRecipients($rec);

		$this->assertEquals($rec, $this->mail->getRecipients());
	}

	public function testSetSingleRecipient()
	{
		$rec = 'foo@bar.ch';
		$this->mail->setRecipients($rec);

		$this->assertEquals([$rec], $this->mail->getRecipients(), 'Should wrap into array.');
	}

	public function testGetJson()
	{
		$subject = 'foooo';
		$content = 'Heeeeey';
		$recipients = ['dude@bar.ch'];
		$this->mail->setSubject($subject);
		$this->mail->setContent($content);
		$this->mail->setRecipients($recipients);

		$json = $this->mail->getJson();

		$decoded = json_decode($json);
		$this->assertNotNull($decoded, 'Should return valid JSON.');

		$this->assertContains($subject, $json);
		$this->assertContains($content, $json);
		$this->assertContains($recipients[0], $json);
	}
}