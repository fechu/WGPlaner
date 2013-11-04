<?php
/**
 * @file PurchaseListTest.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace ApplicationTest\Entity;

use Application\Entity\PurchaseList;
use Application\Entity\Purchase;
class PurchaseListTest extends \PHPUnit_Framework_TestCase
{
	protected $list;
	
	public function setUp()
	{
		$this->list = new PurchaseList();
	}

	public function testSuccessfulCreation()
	{
		$this->assertNotNull($this->list, 'List was not created successfully');
	}
	
	public function testAddPurchase()
	{
		$purchase = new Purchase();
		$purchase->setDate(new \DateTime());
		
		$startDate = new \DateTime();
		$startDate->sub(new \DateInterval('P10D'));	// Subtract 10 days.
		$endDate = new \DateTime();
		$endDate->add(new \DateInterval('P10D'));	// Add 10 days.
		
		$oldCount = count($this->list->getPurchases());
		$this->list->addPurchase($purchase);
		
		$this->assertCount($oldCount + 1, $this->list->getPurchases());
		$this->assertContains($purchase, $this->list->getPurchases());
	}
	
	public function testAddPurchaseOutOfBoundsThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		$purchaseDate = new \DateTime();
		$purchaseDate->add(new \DateInterval('P20D'));	// Later then end date
		
		$purchase = new Purchase();
		$purchase->setDate($purchaseDate);
		
		$startDate = new \DateTime();
		$startDate->sub(new \DateInterval('P10D'));	// Subtract 10 days.
		$endDate = new \DateTime();
		$endDate->add(new \DateInterval('P10D'));	// Add 10 days.
		
		$this->list->setStartDate($startDate);
		$this->list->setEndDate($endDate);
		
		$oldCount = count($this->list->getPurchases());
		$this->list->addPurchase($purchase); // Throws excpetion
	}
}