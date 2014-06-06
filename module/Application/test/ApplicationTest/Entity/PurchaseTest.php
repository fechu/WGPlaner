<?php
/**
 * @file PurchaseTest.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Purchase;

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

	public function testCreatedWithApiIsNoByDefault()
	{
		$
	}

}