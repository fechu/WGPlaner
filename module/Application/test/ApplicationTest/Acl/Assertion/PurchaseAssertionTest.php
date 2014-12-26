<?php
/**
 * @file PurchaseAssertionTest.php
 * @date Dec 26, 2014
 * @author Sandro Meier
 */

namespace ApplicationTest\Acl\Assertion;


use Application\Entity\User;
use Application\Acl\Acl;
use Zend\Permissions\Acl\Role\GenericRole;
use Zend\Permissions\Acl\Resource\GenericResource;
use Application\Acl\Assertion\PurchaseAssertion;
use Application\Entity\Purchase;

class PurchaseAssertionTest extends \PHPUnit_Framework_TestCase
{
	protected $purchaseAssertion;

	public function setUp()
	{
		$this->purchaseAssertion = new PurchaseAssertion();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->purchaseAssertion, 'Assertion was not created successfully');
	}

	public function testNotAllowedIfPurchaseHasNoOwner()
	{
		$user = new User();
		$purchase = new Purchase();
		$acl = new Acl();

		$this->assertFalse(
				$this->purchaseAssertion->assert($acl, $user, $purchase),
				'Assertion should fail if purchase has no owner'
		);
	}

	public function testNotAllowedIfUserIsNotOwnerOfPurchase()
	{
		$user = new User();
		$owner = new User();
		$purchase = new Purchase();
		$purchase->setUser($owner);
		$acl = new Acl();

		$this->assertFalse(
				$this->purchaseAssertion->assert($acl, $user, $purchase),
				'Assertion should fail if user is not owner of purchase'
		);
	}

	public function testAllowedIfUserIsOwner()
	{
		$user = new User();
		$purchase = new Purchase();
		$purchase->setUser($user);
		$acl = new Acl();

		$this->assertTrue(
			$this->purchaseAssertion->assert($acl, $user, $purchase),
			'Assertion should succeed if user is owner of purchase'
		);
	}

	public function testNotAllowedIfRoleIsNotUserObject()
	{
		$purchase = new Purchase();
		$acl = new Acl();
		$role = new GenericRole('user');

		$this->assertFalse(
			$this->purchaseAssertion->assert($acl, $role, $purchase),
			'Assertion should fail if the role is not a user object.'
		);
	}

	public function testNotAllowedIfResourceIsNotPurchaseObject()
	{
		$resource = new GenericResource('purchase');
		$acl = new Acl();
		$user = new User();

		$this->assertFalse(
			$this->purchaseAssertion->assert($acl, $user, $resource),
			'Assertion should fail if resource is not a purchase object'
		);
	}
}
