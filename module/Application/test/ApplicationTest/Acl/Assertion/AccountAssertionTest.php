<?php
/**
 * @file AccountAssertionTest.php
 * @date Dec 26, 2014
 * @author Sandro Meier
 */

namespace ApplicationTest\Acl\Assertion;


use Application\Acl\Assertion\AccountAssertion;
use Application\Entity\User;
use Application\Entity\Account;
use Application\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;

class AccountAssertionTest extends \PHPUnit_Framework_TestCase
{
	protected $accountAssertion;

	public function setUp()
	{
		$this->accountAssertion = new AccountAssertion();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->accountAssertion, 'Assertion was not created successfully');
	}

	public function testNotAllowedIfUserNotInAccount()
	{
		$user = new User();
		$account = new Account();
		$acl = new Acl();

		$this->assertFalse(
				$this->accountAssertion->assert($acl, $user, $account),
				'Assertion should fail if user not in account'
		);
	}

	public function testAllowedIfUserInAccount()
	{
		$user = new User();
		$account = new Account();
		$account->addUser($user);
		$acl = new Acl();

		$this->assertTrue(
			$this->accountAssertion->assert($acl, $user, $account),
			'Assertion should succeed if user belongs to account'
		);
	}

	public function testNotAllowedIfRoleIsNotUserObject()
	{
		$account = new Account();
		$acl = new Acl();
		$role = new GenericRole('user');

		$this->assertFalse(
			$this->accountAssertion->assert($acl, $role, $account),
			'Assertion should fail if the role is not a user object.'
		);
	}

	public function testNotAllowedIfResourceIsNotAccountObject()
	{
		$resource = new GenericResource('account');
		$acl = new Acl();
		$user = new User();

		$this->assertFalse(
			$this->accountAssertion->assert($acl, $user, $resource),
			'Assertion should fail if resource is not an Account object'
		);
	}
}
