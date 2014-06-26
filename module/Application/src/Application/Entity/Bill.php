<?php
/**
 * @file Bill.php
 * @date Jun 18, 2014
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SMCommon\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\BillRepository")
 */
class Bill extends AbstractEntity
{
	/**
	 * The name of the bill
	 * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * The user(s) that have to pay or get money from this bill.
	 *
	 * @var ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="\Application\Entity\User", inversedBy="bills")
	 */
	protected $users;

	/**
	 * The purchases which belong to this bill.
	 *
	 * This is actually a one to many unidirectional relationship. But doctrine handles this as
	 * a many to many with a unique constraint in the jointable.
	 *
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Purchase", inversedBy="bills")
	 */
	protected $purchases;

	public function __construct()
	{
		parent::__construct();

		$this->purchases = new ArrayCollection();
		$this->users = new ArrayCollection();
	}

	/**
	 * Add purchases to this bill.
	 * @param array $purchases An array of Purchase objects.
	 */
	public function addPurchases($purchases)
	{
		/* @var $purchase \Application\Entity\Purchase */
		foreach ($purchases as $purchase) {
			$this->purchases[] = $purchase;

			// Check (and set if needed) the inverse side
			if (in_array($this, $purchase->getBills()) == false) {
				$purchase->addToBill($this);
			}

		}
	}

	/**
	 * Get the purchases that are covered by this bill.
	 */
	public function getPurchases()
	{
		return $this->purchases->toArray();
	}

	public function setName($name)
	{
		if (is_string($name)) {
			$this->name = $name;
		}
		else {
			throw new \InvalidArgumentException("Value is not a string. String required.");
		}
	}


	/**
	 * Get the name of the bill.
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Add a user to the account.
	 *
	 * @param \Application\Entity\User $user
	 */
	public function addUser($user)
	{
		if (!$this->users->contains($user)) {
			$this->users[] = $user;

			// Set inverse side too.
			if (in_array($this, $user->getBills()) == false) {
				$user->addBill($this);
			}
		}
	}

	/**
	 * Get the users that have something to do with this bill.
	 */
	public function getUsers()
	{
		return $this->users->toArray();
	}

}