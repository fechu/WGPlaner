<?php
/**
 * @file UserBillShare.php
 * @date Jun 19, 2014
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use SMCommon\Entity\AbstractEntity;

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
	 * Unidirectional - OneToMany
	 *
	 * @var \Application\Entity\User
	 *
	 * @ORM\ManyToMany(targetEntity="User")
	 */
	protected $user;

	/**
	 * The bill to which in which this share is used for calculation.
	 *
	 * @var \Application\Entity\Bill;
	 *
	 * @ORM\ManyToOne(targetEntity="Bill")
	 */
	protected $bill;


	public function __construct()
	{
		// Set default values
		$this->share = 1;
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
		return $this->user;
	}

	public function setUser($user)
	{
		$this->user = $user;
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