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

	///////////////////////////////////////////////////////////////////////
	// Name
	///////////////////////////////////////////////////////////////////////

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

	///////////////////////////////////////////////////////////////////////
	// User
	///////////////////////////////////////////////////////////////////////

	public function testHasNoUsersByDefault()
	{
		$this->assertCount(0, $this->bill->getUsers());
	}

	public function testAddUser()
	{
		$user = new User();

		$this->bill->addUser($user);

		$this->assertContains($user, $this->bill->getUsers(), "Should contain user");

	}

	///////////////////////////////////////////////////////////////////////
	// Purchases
	///////////////////////////////////////////////////////////////////////


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

	public function testAddingPurchaseWithNewUserAddsUserToBill()
	{
		$user = new User();
		$purchase = new Purchase();
		$purchase->setUser($user);

		$this->bill->addPurchases($purchase);

		$share = $this->bill->getUserShare($user);
		$this->assertNotNull($share, "Share should have been created for the new user");
	}

	///////////////////////////////////////////////////////////////////////
	// User Shares
	///////////////////////////////////////////////////////////////////////

	public function testHasNoUserSharesByDefault()
	{
		$this->assertCount(0,$this->bill->getUserShares());
	}

	public function testAddUserWithShare()
	{
		$user = new User();

		$this->bill->addUser($user, 2);

		$this->assertCount(1, $this->bill->getUserShares(), "Should now contain a user share");
	}

	public function testGetShareForUser()
	{
		$user = new User();
		$this->bill->addUser($user, 2);

		$share = $this->bill->getUserShare($user);

		$this->assertEquals($user, $share->getUser(), "The user of the share should be the user");
		$this->assertEquals(2, $share->getShare(), "Should be the same as set.");
	}

	public function testAddingUserWithShareTwiceUpdatesShare()
	{
		$user = new User();
		$this->bill->addUser($user, 2);

		$this->assertCount(1, $this->bill->getUserShares());

		// Add a second time
		$this->bill->addUser($user, 3);

		// Make sure it is not added again
		$this->assertCount(1, $this->bill->getUserShares());

		// Check if the share was updated
		$share = $this->bill->getUserShare($user);
		$this->assertEquals(3, $share->getShare(), "Share should have been updated");

	}

	public function testAddingUserShareSetsBillOfUserShare()
	{
		$user = new User();
		$this->bill->addUser($user);

		$share = $this->bill->getUserShare($user);

		$this->assertEquals($this->bill, $share->getBill(), "Should have set the bill of the share.");
	}
}