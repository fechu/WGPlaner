<?php
/**
 * @file Purchase.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity;

use SMCommon\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\PurchaseRepository")
 */
class Purchase extends AbstractEntity
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
	
	public function __construct()
	{
		parent::__construct();
		
		$this->setDate(new \DateTime());
	}
	
	public function setDate($date)
	{
		if (is_string($date)) {
			$date = \DateTime::createFromFormat('d.m.Y', $date);
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
	
	
}