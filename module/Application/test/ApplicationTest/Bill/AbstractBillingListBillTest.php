<?php
/**
 * @file AbstractBillingListBillTest.php
 * @date Nov 2, 2013 
 * @author Sandro Meier
 */
 
namespace ApplicationTest\Bill;

use Application\Bill\BillingList\AbstractBillingListBill;
use Application\Entity\User;
class AbstractBillingListBillTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var AbstractBillingListBill
	 */
	protected $bill;
	
	public function setUp()
	{
		// It is a abstract class. So we need to use a mock to test.
		$this->bill = $this->getMockForAbstractClass('Application\Bill\BillingList\AbstractBillingListBill');
	}
	
	public function testSetUsers()
	{
		$users = array();
		$user = new User();
		$user->setFullname('Sandro Meier');
		$users[] = $user;
		
		$this->bill->setUsers($users);
		
		$this->assertEquals($users, $this->bill->getUsers(), 'Should return the same array of users');
	}
}