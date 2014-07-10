<?php
/**
 * @file UserBillShare.php
 * @date Jun 19, 2014
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use SMCommon\Entity\AbstractEntity;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Objects of this type describe the relationship of a user with a bill.
 * The share field allows users to define which user has to pay how much of a bill.
 *
 * A bill will have one or multiple of UserBillShare objects. Based on this objects it
 * then calculates the amounts the users have to pay. The idea behind the formula is the
 * following:
 *
 * "amount to pay for user x" = ("Total Amount" / sum("Shares")) * "Share of user x"
 *
 * @ORM\Entity
 */
class UserBillShare extends AbstractEntity
{
	/**
	 * A number that show how much this share's user has to pay relative to the others.
	 *
	 * @ORM\Column(type="float")
	 */
	protected $share;

	/**
	 * The user who's share is described by this object.
	 *
	 * Unidirectional - OneToMany (Needed due to doctrine limitations)
	 *
	 * @var ArrayCollection
	 *
	 * @ORM\ManyToMany(targetEntity="\Application\Entity\User")
	 */
	protected $user;

	/**
	 * The bill to which in which this share is used for calculation.
	 *
	 * @var \Application\Entity\Bill;
	 *
	 * @ORM\ManyToOne(targetEntity="\Application\Entity\Bill", inversedBy="userShares")
	 */
	protected $bill;


	public function __construct()
	{
		parent::__construct();

		// Set default values
		$this->share = 1;
		$this->user = new ArrayCollection();
	}

	public function getShare()
	{
		return $this->share;
	}

	public function setShare($share)
	{
		if ($share != 0) {
        	$this->share = $share;
		}
	}

	public function getUser()
	{
		$user = $this->user->first();
		return ($user != false) ? $user : NULL;
	}

	public function setUser($user)
	{
		$this->user->clear();
		$this->user->add($user);
	}

	public function getBill()
	{
		return $this->bill;
	}

	public function setBill($bill)
	{
		$this->bill = $bill;
	}

}