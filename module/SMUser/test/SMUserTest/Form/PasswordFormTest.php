<?php
/**
 * @file PasswordFieldsetTest.php
 * @date Oct 4, 2013 
 * @author Sandro Meier
 */
 
namespace SMUserTest\Form\Fieldset;


use SMUser\Form\PasswordForm;
class PasswordFormTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var PasswordForm
	 */
	protected $form;

	public function setUp()
	{
		$this->form = new PasswordForm();
	}
	
	public function testCreation()
	{
		$this->assertNotNull($this->form, 'form was not created successfully');
	}
	
	public function testHasPasswordFieldByDefault()
	{
		$this->assertTrue($this->form->has('password'), 'Form has no password field');
	}
	
	public function testHasVerifyPasswordFieldByDefault()
	{
		$this->assertTrue($this->form->has('verify-password'), 'Form has verify password field.');
	}
	
	public function testRemoveVerifyField()
	{
		$this->assertTrue($this->form->has('verify-password'));
		
		$oldCount = count($this->form);
		$this->form->showVerifyPasswordField(false);
		
		// Assert field was removed
		$this->assertFalse($this->form->has('verify-password'));
		$this->assertCount($oldCount - 1, $this->form);
	}
	
	/**
	 * @depends testRemoveVerifyField
	 */
	public function testAddVerifyField()
	{
		// Make sure it has no field
		$this->form->showVerifyPasswordField(false);
		
		$this->form->showVerifyPasswordField(true);
		$this->assertTrue($this->form->has('verify-password'), 'Should have verify password field');
		$this->assertCount(2, $this->form, 'Should now have 2 fields');
	}
}