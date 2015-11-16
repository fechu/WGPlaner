<?php
/**
 * @file CombinedBillTest.php
 * @date Nov 16, 2015
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Bill;
use Application\Entity\CombinedBill;
use Application\Entity\User;
use PHPUnit_Framework_TestCase;

class CombinedBillTest extends PHPUnit_Framework_TestCase {

    /* @var $bill Bill */
    protected $combinedBill;

    public function setUp()
    {
        $this->combinedBill = new CombinedBill();
    }

    public function testSuccessfulCreation()
    {
        $this->assertNotNull($this->combinedBill, 'CombinedBill was not created successfully');
    }

    ///////////////////////////////////////////////////////////////////////
    // Name
    ///////////////////////////////////////////////////////////////////////

    public function testSetName()
    {
        $name = "September 2012";

        $this->combinedBill->setName($name);
        $this->assertEquals($name, $this->combinedBill->getName(), 'Name was not set correctly');
    }

    public function testNonStringNameThrowsException()
    {
        $this->setExpectedException('InvalidArgumentException');

        $invalidName = array();

        $this->combinedBill->setName($invalidName);	// Should throw exception
    }

	public function testAddBill()
	{
		$bill = new Bill();
		$this->combinedBill->addBill($bill);

		$this->assertContains($bill, $this->combinedBill->getBills(), 'Should contain bill');
	}

	public function testAddBillSetsInverseSide()
	{
		$bill = new Bill();
		$this->combinedBill->addBill($bill);

		$this->assertContains(
				$this->combinedBill,
				$bill->getCombinedBills(),
				'Inverse side should be set'
				);
	}

	public function testGetUsers()
	{
		$user1 = new User();
		$user1->setUsername('foo1');
		$user2 = new User();
		$user2->setUsername('foo2');

		$bill1 = new Bill();
		$bill1->addUser($user1);
		$bill2 = new Bill();
		$bill2->addUser($user2);

		$this->combinedBill->addBill($bill1);
		$this->combinedBill->addBill($bill2);

		$this->assertEquals($this->combinedBill->getUsers(), [$user1, $user2]);
	}

	public function testGetAmountSumOfAmountOfBills()
	{
		$bill1 = $this->getMockBuilder('Application\Entity\Bill')->getMock();
		$bill1->method('getAmount')->willReturn(44);

		$bill2 = $this->getMockBuilder('Application\Entity\Bill')->getMock();
		$bill2->method('getAmount')->willReturn(66);

		$this->combinedBill->addBill($bill1);
		$this->combinedBill->addBill($bill2);

		$this->assertEquals(110, $this->combinedBill->getAmount());
	}

	public function testGetBillableAmountSumOfBillableAmountOfBills()
	{
		$bill1 = $this->getMockBuilder('Application\Entity\Bill')->getMock();
		$bill1->method('getBillableAmount')->willReturn(44);

		$bill2 = $this->getMockBuilder('Application\Entity\Bill')->getMock();
		$bill2->method('getBillableAmount')->willReturn(66);

		$this->combinedBill->addBill($bill1);
		$this->combinedBill->addBill($bill2);

		$this->assertEquals(110, $this->combinedBill->getBillableAmount());
	}
}
