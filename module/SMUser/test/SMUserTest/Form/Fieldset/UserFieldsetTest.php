<?php
/**
 * @file UserFieldsetTest.php
 * @date Oct 3, 2013 
 * @author Sandro Meier
 */
 
namespace SMUserTest\Form\Fieldset;

use SMUser\Form\Fieldset\UserFieldset;
class UserFieldsetTest extends \PHPUnit_Framework_TestCase
{
	protected $fieldset;
	
	public function setUp()
	{
		$this->fieldset = new UserFieldset();
	}
	
	public function testUserFieldsetWasCreated()
	{
		$this->assertNotNull($this->fieldset, 'Fieldset was not created!');
	}
	
	public function testHasUsernameField()	
	{
		$this->assertTrue($this->fieldset->has('username'), 'Has no username field');
	}
	
	public function testHasFullnameField()	
	{
		$this->assertTrue($this->fieldset->has('fullname'), 'Has no fullname field');
	}
	
	public function testHasEmailField()
	{
		$this->assertTrue($this->fieldset->has('emailAddress'), 'Has no email field');
	}
	
	public function testHasNoPasswordField()
	{
		$this->assertFalse($this->fieldset->has('password'), 'Should not have password field by default');
	}
	
	public function testHasNoVerifyPasswordField()	
	{
		$this->assertFalse($this->fieldset->has('verify-password'), 'Should not have verify password field by default');
	}
	
	/**
	 * @depends testHasNoPasswordField
	 */
	public function testAddPasswordField()
	{
		$this->fieldset->setShowPasswordField(true);
		
		$this->assertTrue($this->fieldset->has('password'), 'Should now have password field');
	}
	
	/**
	 * @depends testHasNoVerifyPasswordField
	 */
	public function testAddPasswordVerifyField()	
	{
		$this->fieldset->setShowVerifyPasswordField(true);
		
		$this->assertTrue($this->fieldset->has('verify-password'), 'Should now have a verify password field');
	}
}