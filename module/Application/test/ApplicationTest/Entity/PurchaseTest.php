<?php
/**
 * @file PurchaseTest.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Purchase;
use Application\Entity\Bill;

class PurchaseTest extends \PHPUnit_Framework_TestCase
{
	protected $purchase;

	public function setUp()
	{
		$this->purchase= new Purchase();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->purchase, 'Purchase was not created successfully');
	}

	public function testCreatedWithApiIsFalseByDefault()
	{
		$this->assertFalse($this->purchase->getCreatedWithAPI(), "Purchase should not be created with API by default");
	}

	public function testSetCreatedWithApiChangesValue()
	{
		$this->purchase->setCreatedWithAPI(true);

		$this->assertTrue($this->purchase->getCreatedWithAPI(), "Should have changed the value");

		$this->purchase->setCreatedWithAPI(false);
		$this->assertFalse($this->purchase->getCreatedWithAPI(), "Should have changed value again");
	}

	public function testAddToBill()
	{
		$bill = new Bill();

		$this->purchase->addToBill($bill);

		$this->assertContains($bill, $this->purchase->getBills(), "Bill should be listed in purchase's bills");
	}

}
