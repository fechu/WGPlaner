<?php
/**
 * @file AbstractBillingListBill.php
 * @date Oct 31, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Bill\BillingList;

/**
 * Bill for a AbstractBilling list. 
 * This class is meant to be extended by classes like PurchaseListBill.
 */
abstract class AbstractBillingListBill
{
	/**
	 * The users associated with this bill.
	 */
	protected $users;
	
	/**
	 * Set the users of this bill.
	 */
	public function setUsers($users)
	{
		$this->users = $users;
	}
	
	/**
	 * Get all users of this bill.
	 */
	public function getUsers()
	{
		return $this->users;
	}
}