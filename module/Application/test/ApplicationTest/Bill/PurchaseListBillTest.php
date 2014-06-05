<?php
/**
 * @file PurchaseListBillTest.php
 * @date Nov 2, 2013 
 * @author Sandro Meier
 */
 
namespace ApplicationTest\Bill;

use Application\Bill\BillingList\PurchaseListBill;
use Application\Entity\Purchase;
use Application\Entity\User;
use Application\Entity\PurchaseList;
class PurchaseListBillTest extends \PHPUnit_Framework_TestCase
{
	protected $list;

	public function setUp()
	{
		$this->list = new PurchaseListBill();
	}
	
	public function testSetAndGetPurchases()
	{
		$purchases = array(new Purchase());
		
		$this->list->setPurchases($purchases);
		
		$this->assertEquals($purchases, $this->list->getPurchases(), 'Should return the array of purchases');
	}
	
	public function testGetPurchasesWithInvalidUserThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		$invalidUser = 'Im not a user!';
		
		$this->list->getPurchases($invalidUser); // Throws exception
	}
	
	public function testGetPurchasesForUser()
	{
		$user1 = new User();
		$user1->setFullname('Sandro Meier');
		
		$user2 = new User();
		$user2->setFullname('Mein Nachbar');
		
		// Add purchases
		$this->addPurchases($user1, 3);
		$this->addPurchases($user2, 7);
		
		// Total purchase count
		$this->assertCount(10, $this->list->getPurchases(), 'getPurchases should return all objects');
		
		// purchases for user1
		$this->assertCount(3, $this->list->getPurchases($user1), 'getPurchases should only return objects for user1');
		
		// purchases for user2
		$this->assertCount(7, $this->list->getPurchases($user2), 'getPurchases should only return objects for user2');
	}
	
	public function testUpdatesPurchasesForEachUserAfterPurchaseReset()
	{
		$user = new User();
		$user->setFullname('Sandro Meier');
		
		// Add purchases
		$this->addPurchases($user, 2);
		$this->assertCount(2, $this->list->getPurchases($user));
		
		// Set new purchases
		$this->addPurchases($user, 2);
		$this->assertCount(4, $this->list->getPurchases($user), 'Should update purchases for a user after chaning purchases.');
	}
	
	public function testGetTotalAmountForUser()
	{
		$user1 = new User();
		$user1->setFullname('Sandro Meier');
	
		$user2 = new User();
		$user2->setFullname('Mein Nachbar');
	
		// Add purchases
		$this->addPurchases($user1, 2, 10);
		$this->addPurchases($user2, 3, 20);
	
		// Total Amount
		$this->assertEquals(80, $this->list->getTotalAmount());
	
		// Amount for user1
		$this->assertEquals(20, $this->list->getTotalAmount($user1), 'Should return sum for user1');
	
		// Amount for user2
		$this->assertEquals(60, $this->list->getTotalAmount($user2), 'Should return sum for user2');
	}
	
	public function testCreationWithPurchaseList()
	{
		$purchaseList = new PurchaseList();
		$purchaseList->addPurchase(new Purchase());
		$purchaseList->addPurchase(new Purchase());
		
		$bill = new PurchaseListBill($purchaseList);
		
		$this->assertEquals($purchaseList->getPurchases(), $bill->getPurchases(), 'Should set the purchases from the purchase list during initialization.');
	}
	
	/**
	 * Adds a number of purchases to the purchase list for auser.
	 * @param User $user The user whoe the purchases should belong to.
	 * @param int $count The number of purchases to add.
	 */
	protected function addPurchases($user, $count = 1, $amount = 10)
	{
		$purchases = $this->list->getPurchases();
		for ($i = 0; $i < $count; $i++) {
			$purchase = new Purchase();
			$purchase->setDescription('Purchase #' . $i);
			$purchase->setUser($user);
			$purchase->setAmount($amount);
			$purchases[] = $purchase;
		}
		
		$this->list->setPurchases($purchases);
	}
}