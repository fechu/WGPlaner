<?php
/**
 * @file Purchase.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace Application\Entity;

use SMCommon\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Console\Application;
use Doctrine\Common\Collections\ArrayCollection;
use Zend\Permissions\Acl\Resource\ResourceInterface;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\PurchaseRepository")
 */
class Purchase extends AbstractEntity implements ResourceInterface
{
	/**
	 * The date of the purchase
	 *
	 * @ORM\Column(type="utcdatetime")
	 */
	protected $date;

	/**
	 * The number of the slip.
	 *
	 * @ORM\Column(type="integer", nullable=true)
	 */
	protected $slipNumber;

	/**
	 * Is a receipt available for this purchase. 
	 * Use the API to get the image. 
	 * 
	 * @var boolean
	 * 
	 * @ORM\Column(type="boolean")
	 */
	protected $hasReceipt = false;

	/**
	 * Name of the store where you bought the things.
	 *
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $store;

	/**
	 * Description of what you bought.
	 *
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $description;

	/**
	 * The amount you paid.
	 *
	 * @ORM\Column(type="float")
	 */
	protected $amount;

	/**
	 * Who did the purchase.
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="purchases")
	 */
	protected $user;

	/**
	 * The account this purchase belongs to.
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\Account", inversedBy="purchases")
	 */
	protected $account;

	/**
	 * The bills to which the purchase belongs to.
	 * A purchase can be added to multiple bills if needed.
	 *
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Application\Entity\Bill", mappedBy="purchases", cascade={"persist"})
	 */
	protected $bills;


	/**
	 * If the purchase was created through the API.
	 * @var boolean
	 *
	 * @ORM\Column(type="boolean")
	 */
	protected $createdWithAPI;

	/**
	 * Is this purchase verified. 
	 * This is by default true. If the purchase is created automatically 
	 * (e.g. by scanning a receipt) it is advised to set this to false. 
	 * The coupon will then get special attention from the user which has to
	 * verify it actively.
	 * 
	 * @var boolean
	 * 
	 * @ORM\Column(type="boolean")
	 */
	protected $verified = true;


	public function __construct()
	{
		parent::__construct();

		$this->setDate(new \DateTime());
		$this->setCreatedWithAPI(false);

		$this->bills = new ArrayCollection();
	}

	public function setDate($date)
	{
		if (is_string($date)) {
			$date = \DateTime::createFromFormat('Y-m-d', $date);
			$date->setTime(0,0,0);
		}
		$this->date = $date;
	}

	public function getDate()
	{
		return $this->date;
	}

	public function setSlipNumber($number)
	{
		$this->slipNumber = (int)$number;
	}

	public function getSlipNumber()
	{
		return $this->slipNumber;
	}

	public function setHasReceipt($hasReceipt)
	{
		$this->hasReceipt = $hasReceipt;
	}

	public function getHasReceipt()
	{
		return $this->hasReceipt;
	}

	public function hasReceipt()
	{
	    return $this->getHasReceipt();
	}

	public function setStore($store)
	{
		$this->store = $store;
	}

	public function getStore()
	{
		return $this->store;
	}

	public function setDescription($desc)
	{
		$this->description = $desc;
	}

	public function getDescription()
	{
		return $this->description;
	}

	public function setAmount($amount)
	{
		$this->amount = (float)$amount;
	}

	public function getAmount()
	{
		return $this->amount;
	}

	/**
	 * @param \Application\Entity\User $user The user you want to assign to this purchase.
	 */
	public function setUser($user)
	{
		$this->user = $user;
		$user->addPurchase($this);
	}

	public function getUser()
	{
		return $this->user;
	}

	/**
	 * Set the purchase list of this
	 */
	public function setAccount($account)
	{
		$this->account = $account;
		$account->addPurchase($this);
	}

	public function getAccount()
	{
		return $this->account;
	}

	/**
	 * Set if the purchase was created with the API
	 * @return the boolean
	 */
	public function getCreatedWithAPI()
	{
		return $this->createdWithAPI;
	}

	/**
	 * @param boolean $createdWithAPI
	 */
	public function setCreatedWithAPI($createdWithAPI)
	{
		$this->createdWithAPI = $createdWithAPI;
	}

	/**
	 * 
	 * @return boolean
	 */
	function getVerified() 
	{
	    return $this->verified;
	}

	/*
	 * @param boolean $verified
	 */
	function setVerified($verified) 
	{
	    $this->verified = $verified;
	}

	/**
	 * Verifies the purchase. 
	 */
	function verify()
	{
	    $this->verified = true;
	}

	/**
	 * Add this purchase to a bill.
	 *
	 * @warning This does not set the inverse side. So use bill->addPurchases() instead, if
	 *	    you don't want to update the inverse side automatically.
	 *
	 * @param \Application\Entity\Bill $bill
	 */
	public function addToBill($bill)
	{
		$this->bills[] = $bill;
	}

	/**
	 * Get the bills in which this purchase is listed.
	 * @return array
	 */
	public function getBills()
	{
		return $this->bills->toArray();
	}

	public function getResourceId()
	{
		return 'purchase';
	}
}
