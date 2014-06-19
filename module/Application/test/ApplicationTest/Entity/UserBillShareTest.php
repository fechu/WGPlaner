<?php
/**
 * @file UserBillShareTest.php
 * @date June 19, 2014
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Bill;
use Application\Entity\User;
use Application\Entity\UserBillShare;

class UserBillShareTest extends \PHPUnit_Framework_TestCase
{
	protected $userBillShare;

	public function setUp()
	{
		$this->userBillShare = new UserBillShare();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->userBillShare, 'Object was not created successfully');
	}

	public function testShareIs1ByDefault()
	{
		$this->assertEquals(1, $this->userBillShare->getShare(), "Share should be 1 by default");
	}

	public function testSetShareTo0DoesNotChangeValue()
	{
		$this->userBillShare->setShare(2);
		$this->assertEquals(2, $this->userBillShare->getShare(), "Should have changed to 2.");

		$this->userBillShare->setShare(0);

		$this->assertEquals(2, $this->userBillShare->getShare(), "Share should still be 2.");
	}

	public function testSetShare()
	{
		$this->userBillShare->setShare(42);

		$this->assertEquals(42, $this->userBillShare->getShare(), "Should have changed share");
	}

	public function testSetUser()
	{
		// Create and set user
		$user = new User();
		$this->userBillShare->setUser($user);

		// Check if we get the right user back.
		$this->assertEquals($user, $this->userBillShare->getUser(), "User should be equal to user that was set");
	}

	public function testSetBill()
	{
		// Create and set bill
		$bill = new Bill();
		$this->userBillShare->setBill($bill);

		// Check if we get the same bill back
		$this->assertEquals($bill, $this->userBillShare->getBill(), "Bill should be equal to bill that was set");
	}
}