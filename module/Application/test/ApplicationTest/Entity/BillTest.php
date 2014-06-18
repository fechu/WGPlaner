<?php
/**
 * @file PurchaseListTest.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Bill;
use Application\Entity\User;
use Application\Entity\Purchase;

class BillTest extends \PHPUnit_Framework_TestCase
{
	protected $bill;

	public function setUp()
	{
		$this->bill = new Bill();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->bill, 'Bill was not created successfully');
	}

	public function testSetName()
	{
		$name = "September 2012";

		$this->bill->setName($name);
		$this->assertEquals($name, $this->bill->getName(), 'Name was not set correctly');
	}


	public function testNonStringNameThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$invalidName = array();

		$this->bill->setName($invalidName);	// Should throw exception
	}

	public function testHasNoUsersByDefault()
	{
		$this->assertCount(0, $this->bill->getUsers());
	}

	public function testAddUser()
	{
		$user = new User();

		$this->bill->addUser($user);

		$this->assertContains($user, $this->bill->getUsers(), "Should contain user");

		// Check if inverse side is also set
		$this->assertContains($this->bill, $user->getBills(), "Inverse side of relationship was not set");
	}

	public function testHasNoPurchasesByDefault()
	{
		$this->assertCount(0, $this->bill->getPurchases());
	}

	public function testAddPurchase()
	{
		$purchase = new Purchase();

		$this->bill->addPurchases(array($purchase));

		$this->assertContains($purchase, $this->bill->getPurchases(), "Purchase was not added to bill");

		// Check if inverse side is set
		$this->assertContains($this->bill, $purchase->getBills(), "Inverse side of relationship was not set");
	}

}