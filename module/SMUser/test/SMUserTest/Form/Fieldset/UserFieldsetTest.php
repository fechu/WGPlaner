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
	
	public function testHasNoVerifyPasswordFieldByDefault()
	{
		$this->assertFalse($this->fieldset->has('verify-password'), 'Should not contain verify password field by default');
	}
	
	public function testAddVerifyPasswordField()
	{
		$this->fieldset->showVerifyPasswordField(true);
		
		$this->assertTrue($this->fieldset->has('verify-password'), 'Should now have a verify password field');
	}
	
	/**
	 * @depends testAddVerifyPasswordField
	 */
	public function testRemoveVerifyPasswordField()
	{
		$this->fieldset->showVerifyPasswordField(true);
		$this->fieldset->showVerifyPasswordField(false);
		
		$this->assertFalse($this->fieldset->has('verify-password'), 'Should not have verify password field anymore.');
	}
	
	public function testHasOnly2ElementsWhenNonLoginElementsRemoved()
	{
		$this->fieldset->removeNonLoginElements();
		
		$this->assertCount(2, $this->fieldset, 'Should only have 2 fields left.');
	}
	
	public function testFieldsetWithLoginElementsHasUsernameAndPasswordField()
	{
		$this->fieldset->removeNonLoginElements();
		
		$this->assertTrue($this->fieldset->has('username'), 'Should have username field');
		$this->assertTrue($this->fieldset->has('password'), 'Should have password field');
	}
}