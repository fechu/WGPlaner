<?php
/**
 * @file PurchaseListBill.php
 * @date Oct 31, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Bill\BillingList;

use Application\Entity\User;
use Application\Entity\Purchase;
class PurchaseListBill extends AbstractBillingListBill
{
	/**
	 * An array containing all purchases.
	 */
	protected $purchases;
	
	/**
	 * An associative array containg the purchases sorted by user. 
	 * 
	 * @param PurchaseList $purchaseList	The purchase list the bill should belong to. 
	 * 
	 * @warning Lazy loaded by getPurchases() for a user.
	 */
	protected $userPurchases;
	
	public function __construct($purchaseList = NULL)
	{
		
		if ($purchaseList) {
			$this->setPurchases($purchaseList->getPurchases());		
		}
		
		$this->userPurchases = array();
	}
	
	/**
	 * @throws \BadMethodCallException 	You cannot set the users of a PurchaseListBill. 
	 * 									Set the purchases and the users will be taken from there
	 */
	public function setUsers($users)
	{
		// Not allowed!
		throw new \BadMethodCallException(
				"You cannot set the users of a PurchaseListBill. Set the purchases and the users will be taken from the purchases." 
		);
	}
	
	/**
	 * Set the purchases of this bill.
	 */
	public function setPurchases($purchases)
	{
		// Make sure to reload the user purchases the next time they are needed.
		$this->userPurchases = array();
		
		$this->purchases = $purchases;
	}
	
	/**
	 * @param User $user If this argument is set, all purchases of that user will be returned.
	 * @return array All purchases of this bill. They are not in a specific order.
	 */
	public function getPurchases($user = NULL)
	{
		if ($user) {
			if (!$user instanceof User) {
				throw new \InvalidArgumentException(sprintf(
					'Expected an instance of %s got %s',
					'Application\Entity\User',
					(is_object($user) ? get_class($user) : gettype($user))
				));
			}
			
			// Check if we have already calculated the purchases for a user.
			$hash = spl_object_hash($user);
			if (!isset($this->userPurchases[$hash])) {
				// Get the purchases for this user.
				$userPurchases = array_filter($this->purchases, function(Purchase $purchase) use ($user) {
					return $purchase->getUser() == $user;
				});
				$this->userPurchases[$hash] = $userPurchases;
			}
			
			return $this->userPurchases[$hash];
		}
		
		return $this->purchases;
	}
	
	/**
	 * @param User $user If this argument is set, the total amount of all purchases of $user is returned.
	 * @returm int float The total amount of the purchases amounts.
	 */
	public function getTotalAmount($user = NULL) 
	{
		if ($user) {
			if (!$user instanceof User) {
				throw new \InvalidArgumentException(sprintf(
						'Expected an instance of %s got %s',
						'Application\Entity\User',
						(is_object($user) ? get_class($user) : gettype($user))
				));
			}
		}
		
		$purchases = $this->getPurchases($user);
		
		// Calculate the total
		$totalAmount = 0;
		foreach ($purchases as $purchase) {
			/* @var $purchase \Application\Entity\Purchase */
			$totalAmount += $purchase->getAmount();
		}
		
		return $totalAmount;
	}
}