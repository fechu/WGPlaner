<?php
/**
 * @file UserText.php
 * @date Sep 22, 2013
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\User;
use Application\Entity\Bill;

class UserTest extends \PHPUnit_Framework_TestCase
{
	protected $user;

	public function setUp()
	{
		$this->user = new User();
	}

	public function testUserIsCreatedSuccessfully()
	{
		$this->assertNotNull($this->user, 'User is not created!');
	}

	public function testSetUsername()
	{
		$username = "MyUsername";

		$this->user->setUsername($username);
		$this->assertEquals($username, $this->user->getUsername(), 'Username should be set');
	}


	public function testSetNonStringUsernameThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$invalidUsername = array();

		$this->user->setUsername($invalidUsername);	// Should throw exception
	}

	public function testSetFullName()
	{
		$fullName = "Charlie Sheen";

		$this->user->setFullname($fullName);

		$this->assertEquals($fullName, $this->user->getFullname(), 'Fullname should be set');
	}

	public function testSetNonStringFullnameThrowsExcpetion()
	{
		$this->setExpectedException('InvalidArgumentException');

		$invalidFullName = array();

		$this->user->setFullname($invalidFullName); // Throws exception

	}

	public function testSetEmailAddress()
	{
		$email = "me@myself.me";

		$this->user->setEmailAddress($email);

		$this->assertEquals($email, $this->user->getEmailAddress(), 'Email should be set');
	}

	public function testSetNonStringEmailThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$invalidEmail = array();

		$this->user->setEmailAddress($invalidEmail); // Throws exception
	}

	public function testSetPassword()
	{
		$password = "super_secret_password";

		$this->user->setPassword($password);

		$this->assertTrue($this->user->isCorrectPassword($password));
	}

	public function testSetNonStringPasswordThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$invalidPassword = array();

		$this->user->setPassword($invalidPassword); // throws excpeption
	}

	public function testApiKeyIsGeneratedByDefault()
	{
		$this->user->generateAPIKey();
		$this->assertNotNull($this->user->getAPIKey(), 'API Key should never be null.');
	}

	public function testApiKeyChanges()
	{
		$this->user->generateAPIKey();
		$oldKey = $this->user->getAPIKey();
		$this->user->generateAPIKey();
		$this->assertNotEquals($oldKey, $this->user->getAPIKey(), 'API Key should change when a new one is generated.');
	}

}