<?php
/**
 * @file PurchaseListTest.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Account;
use Application\Entity\Purchase;

class AccountTest extends \PHPUnit_Framework_TestCase
{
	protected $account;

	public function setUp()
	{
		$this->account = new Account();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->account, 'Account was not created successfully');
	}

	public function testAddPurchase()
	{
		$purchase = new Purchase();
		$purchase->setDate(new \DateTime());

		$oldCount = count($this->account->getPurchases());
		$this->account->addPurchase($purchase);

		$this->assertCount($oldCount + 1, $this->account->getPurchases());
		$this->assertContains($purchase, $this->account->getPurchases());
	}

	public function testSetName()
	{
		$name = "The answer is 42";

		$this->account->setName($name);
		$this->assertEquals($name, $this->account->getName(), 'Name was not set correctly');
	}


	public function testNonStringNameThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');

		$invalidName = array();

		$this->account->setName($invalidName);	// Should throw exception
	}

        public function testArchivedIsFalseByDefault()
        {
            $this->assertFalse($this->account->isArchived());
        }

        public function testSetArchivedChangesArchivedFlag()
        {
            $this->account->setArchived(true);
            $this->assertTrue($this->account->isArchived());
        }

        public function testArchivedChangesArchivedFlag()
        {
            $this->account->archive();
            $this->assertTrue($this->account->isArchived());
        }
        
        
}
