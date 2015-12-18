<?php
/**
 * @file CombinedBill
 * @date Nov 16, 2015
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SMCommon\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CombinedBillRepository")
 */
class CombinedBill extends AbstractEntity
{
    /**
     * The name of the bill
     * @ORM\Column(type="string")
     */
    protected $name;

	/**
	 * Is this combined bill paid?
	 * @var boolean
	 * @ORM\Column(type="boolean")
	 */
	protected $paid = false;

    /**
     * The bills which belong to this combined bill.
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Bill", inversedBy="combinedBills")
     */
    protected $bills;

    public function __construct()
    {
        parent::__construct();

        $this->bills = new ArrayCollection();
    }


  
    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
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
	 * Add a bill to this combined bill. Also updates the inverse side. 
	 * 
	 * @param Bill $bill
	 */
	public function addBill($bill)
	{
		$this->bills[] = $bill;
		$bill->addToCombinedBill($this);
	}

	public function getBills()
	{
		return $this->bills->toArray();
	}

	/**
	 * Get all users involved with this combined bill.
	 * @return User[]
	 */
	public function getUsers()
	{
		$users = [];
		/** @var Bill $bill */
		foreach ($this->getBills() as $bill) {
			$users = array_merge($users, $bill->getUsers());
		}
		return array_unique($users);
	}

	/**
	 * Get the total amount a user has spent in all this bills.
	 * @param User $user Specify a user. If NULL, the total amount is returned.
	 * @return float
	 */
	public function getAmount($user = NULL) 
	{
		$amount = 0.0;
		/* @var $bill Bill */
		foreach ($this->getBills() as $bill) {
			$amount += $bill->getAmount($user);
		}
		return $amount;
	}

	/**
	 * Get the total amount a user has to pay in these bills.
	 * @param User $user Specify a user. If NULL, the total amount is returned.
	 * @return float
	 */
	public function getBillableAmount($user = NULL)
	{
		$amount = 0.0;
		/* @var $bill Bill */
		foreach ($this->getBills() as $bill) {
			$amount += $bill->getBillableAmount($user);
		}
		return $amount;
	}

	/**
	 * Calculates the balance of the given user for this combined bill. Positives means
	 * he will receive money, negative means he has to pay.
	 * @param $user The user for which you want the total.
	 * @return float
	 */
	public function getTotal($user)
	{
		return $this->getAmount($user) - $this->getBillableAmount($user);
	}

	/**
	 * @return boolean
	 */
	public function isPaid()
	{
		return $this->paid;
	}

	/**
	 * @param boolean $paid
	 */
	public function setPaid($paid)
	{
		$this->paid = $paid;
	}
}
