<?php
/**
 * @file PurchaseListTest.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace ApplicationTest\Entity;

use Application\Entity\Bill;
use Application\Entity\CombinedBill;
use Application\Entity\Purchase;
use Application\Entity\User;
use PHPUnit_Framework_TestCase;

class BillTest extends PHPUnit_Framework_TestCase {

    /* @var $bill Bill */
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

    /**
     * @todo There's a strange bug, when the users don't have usernames
     */
    public function testAddMultiplePurchases()
    {
        $user1 = new User();
        $user1->setUsername("Hansi");
        $purchase1 = new Purchase();
        $purchase1->setUser($user1);

        $user2 = new User();
        $user2->setUsername("Peterli");
        $purchase2 = new Purchase();
        $purchase2->setUser($user2);

        $this->bill->addPurchases(array($purchase1, $purchase2));

        $this->assertCount(2, $this->bill->getPurchases());
    }

    public function testGetPurchasesForUser()
    {
        $user1 = new User();
        $user1->setUsername("Hansi");
        $purchase1 = new Purchase();
        $purchase1->setUser($user1);

        $user2 = new User();
        $user2->setUsername("Peterli");
        $purchase2 = new Purchase();
        $purchase2->setUser($user2);

        $this->bill->addPurchases(array($purchase1, $purchase2));

        $purchases = $this->bill->getPurchases($user1);
        $this->assertCount(1, $purchases, "Should only return purchases of user1");
        $purchase = $purchases[0];
        $this->assertEquals($user1, $purchase->getUser(), "Should be purchase of user 1");
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
    // Combined Bills
    ///////////////////////////////////////////////////////////////////////

	public function testAddToCombinedBill()
	{
		$combinedBill = new CombinedBill();
	
		$this->bill->addToCombinedBill($combinedBill);

		$this->assertContains($combinedBill, $this->bill->getCombinedBills());
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

    public function testGetUserSharesTotalReturns0WithoutUsers()
    {
        $this->assertEquals(0, $this->bill->getTotalUserShare(), "Should be zero without users");
    }

    public function testGetTotalUserShareReturnsSumOfShares()
    {
        $user1 = new User();
        $user1->setUsername("Fechu");
        $user2 = new User();
        $user2->setUsername("Pingu");

        $this->bill->addUser($user1, 2);
        $this->bill->addUser($user2, 1);

        $this->assertEquals(3, $this->bill->getTotalUserShare(), "Should be sum of shares");
    }

    ////////////////////////////////////////////////////////////////////////
    // Calculations
    ////////////////////////////////////////////////////////////////////////

    public function testGetAmountReturns0IfBillHasNoPurchases()
    {
        $this->assertCount(0, $this->bill->getPurchases(), "Should not have any purchases");

        $this->assertEquals(0, $this->bill->getAmount(), "Should have 0 amount by default");
    }

    public function testGetAmountReturnsTotalAmount()
    {
        $purchase = new Purchase();
        $purchase->setAmount(5.5);

        $this->bill->addPurchases($purchase);

        $this->assertEquals(5.5, $this->bill->getAmount(), "Should have amount of single bill");
    }

    public function testGetTotalAmountWithMultiplePurchases()
    {
        $user = new User();
        $user->setUsername("Testuser");

        $this->addPurchases($user, array(4.5, 5.5));

        $this->assertEquals(10, $this->bill->getAmount(), "Should sum purchases to get amount");
    }

    public function testGetAmountOfUserUsesOnlyPurchasesOfThatUser()
    {
        $user1 = new User();
        $user1->setUsername("Fechu");
        $this->addPurchases($user1, 0.5);

        $user2 = new User();
        $user2->setUsername("Not Fechu");
        $this->addPurchases($user2, 1.0);

        $this->assertEquals(0.5, $this->bill->getAmount($user1), "Should only use user 1 purchases");
        $this->assertEquals(1.0, $this->bill->getAmount($user2), "Should only use user 2 purchases");
    }

    public function testGetAmountOfUserWithMultiplePurchasesReturnsCorrectSum()
    {
        $user1 = new User();
        $user1->setUsername("Fechu");
        $this->addPurchases($user1, array(1,2));

        $user2 = new User();
        $user2->setUsername("Not Fechu");
        $this->addPurchases($user2, array(3,4));

        $this->assertEquals(3, $this->bill->getAmount($user1), "Should sum up purchases of user1");
        $this->assertEquals(7, $this->bill->getAmount($user2), "Should sum up purchases of user2");
    }

    public function testGetBillableAmountReturns0WhenTheBillHasNoPurchases()
    {
        $this->assertEquals(0, $this->bill->getBillableAmount(), "Should be 0 by definition");
    }

    public function testGetBillableAmountReturnsTotalAmountIfUserArgumentIsNull()
    {
        // Add 2 purchases for first user
        $user1 = new User();
        $user1->setUsername("Fechu");
        $this->addPurchases($user1, array(1.0, 2.0));

        // Add 2 purchases for second user
        $user2 = new User();
        $user2->setUsername("Not Fechu");
        $this->addPurchases($user2, array(3.0, 4.0));

        $billable = $this->bill->getBillableAmount();
        $totalAmount = $this->bill->getAmount();

        $this->assertEquals($totalAmount, $billable, "Should be equal if no user given");
    }

    public function testGetBillableAmountIsTotalDividedByNumberOfUsers()
    {
        // By default if you add a user (s)he will be added with a share of 1.
        // So all users have share one, which results in a nomral split.

        $user1 = new User();
        $user1->setUsername("Fechu");
        $this->addPurchases($user1, array(2, 2));

        $user2 = new User();
        $user2->setUsername("Yoda");
        $this->addPurchases($user2, array(3, 3));

        // The billable amount should now be 5 for each user.
        $this->assertEquals(5, $this->bill->getBillableAmount($user1), "Should be 5");
        $this->assertEquals(5, $this->bill->getBillableAmount($user2), "Should be 5");
    }

    public function testGetBillableAmountTakesSharesIntoConsideration()
    {
        $user1 = new User();
        $user1->setUsername("Fechu");
        $this->addPurchases($user1, array(1));

        $user2 = new User();
        $user2->setUsername("Yoda");
        $this->addPurchases($user2, array(2));

        // Set different shares
        $this->bill->addUser($user1, 2);
        $this->bill->addUser($user2, 1);

        // user1 should now be billed 2/3 and user2 1/3. 
        $this->assertEquals(2, $this->bill->getBillableAmount($user1), "Should get 2/3 billed");
        $this->assertEquals(1, $this->bill->getBillableAmount($user2), "Should get 1/3 billed");
    }

    public function testGetAmountPerShareReturns0IfBillHasNoPurchases()
    {
        $this->assertEquals(0, $this->bill->getAmountPerShare(), "Should not have any average");
    }

    public function testGetAmountPerShare()
    {
        // This tests just a random sample

        $user = new User();
        $user->setUsername("Mario");
        $this->addPurchases($user, array(2));
        $this->bill->addUser($user, 2); // Set non default share

        $user2 = new User();
        $user->setUsername("Luigi");
        $this->addPurchases($user2, array(4));

        $amountPerShare = $this->bill->getAmountPerShare();
        $this->assertEquals(2, $amountPerShare, "Should have calculated the right way");
    }

    ////////////////////////////////////////////////////////////////////////
    // Helper Methods
    ////////////////////////////////////////////////////////////////////////

    /**
     * Adds for the given user one or multiple purchases. Depending on the $amounts parameter
     *
     * @param User        $user    The user for which to add paramters
     * @param array|float $amounts A float or an array of floats. The method will add
     *                             a purchase for every float wich the float as amount.
     */
    private function addPurchases($user, $amounts)
    {
        if (is_float($amounts) || is_integer($amounts)) {
            // Wrap the value into an array
            $amounts = array($amounts);
        }

        // Add purchases
        $purchases = array();
        foreach ($amounts as $amount) {
            $purchase = new Purchase();
            $purchase->setUser($user);
            $purchase->setAmount($amount);

            $purchases[] = $purchase;
        }

        $this->bill->addPurchases($purchases);
    }
}
