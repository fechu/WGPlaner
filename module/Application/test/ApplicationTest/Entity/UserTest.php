<?php
/**
 * @file UserText.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace ApplicationTest\Entity;

use Application\Entity\User;

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
		
		$this->user->setEmailAdress($email);
		
		$this->assertEquals($email, $this->user->getEmailAddress(), 'Email should be set');
	}
	
	public function testSetNonStringEmailThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		$invalidEmail = array();
		
		$this->user->setEmailAdress($invalidEmail); // Throws exception
	}
	
	public function testSetPassword()
	{
		$password = "super_secret_password";
		
		$this->user->setPassword($password);
		
		$this->assertEquals($password, $this->user->getPassword(), 'Passwort should be set.');
	}
	
	public function testSetNonStringPasswordThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		$invalidPassword = array();
		
		$this->user->setPassword($invalidPassword); // throws excpeption
	}
}