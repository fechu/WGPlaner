<?php
/**
 * @file PurchaseList.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\PurchaseListRepository")
 */
class PurchaseList extends AbstractBillingList
{
	/**
	 * The purchases in this list.
	 * 
	 * This is actually a one to many unidirectional relationship. But doctrine handles this as
	 * a many to many with a unique constraint in the jointable.
	 * 
	 * @ManyToMany(targetEntity="Application\Entity\Purchase")
     * @JoinTable(name="purchaselist_purchases",
     *      joinColumns={@JoinColumn(name="purchaselist_id", referencedColumnName="id")},
     *      inverseJoinColumns={@JoinColumn(name="purchase_id", referencedColumnName="id", unique=true)}
     *      )
	 */
	protected $purchases;
	
	public function __construct()
	{
		$this->purchases = new ArrayCollection();
	}
	
	/**
	 * Add a purchase to this list.
	 * The date of the purchase needs to be between the start and end date of 
	 * this list. 
	 * @param \Application\Entity\Purchase $purchase
	 * @throws InvalidArgumentException If the date of the purchase is not between start and enddate of 
	 * 									this list.
	 */
	public function addPurchase($purchase) 
	{
		if ($this->isDateInPeriod($purchase->getDate())) {
			$this->purchases[] = $purchase;
		}
		else {
			throw new \InvalidArgumetnException('Purchase date needs to be between start and enddate of this list!');
		}
	}
	
	public function getPurchases()
	{
		return $this->purchases->toArray();
	}
}