<?php
/**
 * @file User.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use SMUser\Entity\UserInterface;
use SMCommon\Entity\AbstractEntity;
use Zend\Crypt\Password\Bcrypt;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * A user
 * 
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\UserRepository")
 */
class User extends AbstractEntity implements UserInterface
{
	/**
	 * The username of the user. 
	 * This is used for a login.
	 * 
	 * @ORM\Column(type="string")
	 */
	protected $username;
	
	/**
	 * The full name of the user. 
	 * This name is displayed.
	 * 
	 * @ORM\Column(type="string")
	 */
	protected $fullname;
	
	/**
	 * The password. 
	 * This is always encrypted. 
	 * 
	 * @ORM\Column(type="string")
	 */
	protected $password;
	
	/**
	 * The email address
	 * 
	 * @ORM\Column(type="string", nullable=true)
	 */
	protected $emailAdress;
	
	/**
	 * The key that gives the user access to the API.
	 * 
	 * @ORM\Column(type="string")
	 */
	protected $apiKey;
	
	/**
	 * The purchases made by this user.	
	 * 
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Application\Entity\Purchase", mappedBy="user")
	 */
	protected $purchases;
	
	/**
	 * The billing lists where the user can create entries
	 * 
	 * @var ArrayCollection
	 * @ORM\ManyToMany(targetEntity="Application\Entity\AbstractBillingList", mappedBy="users", cascade={"persist"})
	 */
	protected $billingLists;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->purchases = new ArrayCollection();
		$this->billingLists = new ArrayCollection();
	}
	
	public function setUsername($username)
	{
		if (is_string($username)) {
			$this->username = $username;
		}
		else {
			throw new \InvalidArgumentException('Username needs to be a string. Received ' . gettype($username));
		}
	}
	
	public function getUsername()
	{
		return $this->username;
	}
	
	public function setFullname($fullname)
	{
		if (is_string($fullname)) {
			$this->fullname = $fullname;
		}
		else {
			throw new \InvalidArgumentException('Fullname has to be a string. Received ' . gettype($fullname));
		}
	}
	
	public function getFullname()
	{
		return $this->fullname;
	}

	public function setEmailAddress($emailAdress)
	{
		if (is_string($emailAdress)) {
			$this->emailAdress = $emailAdress;
		}
		else {
			throw new \InvalidArgumentException('Email address has to be a string. Received ' . gettype($emailAdress));
		}
	}

	public function getEmailAddress()
	{
		return $this->emailAdress;
	}
	
	/**
	 * @return string The API key for the user.
	 */
	public function getAPIKey()
	{
		return $this->apiKey;
	}
	
	/**
	 * Generate a new random API Key.
	 */
	public function generateAPIKey()
	{
		$this->apiKey = md5($this->username . $this->emailAdress . uniqid());
	}

	public function setPassword($password)
	{
		if (is_string($password)) {
			$bcrypt = new Bcrypt();
			$this->password = $bcrypt->create($password);
		}
		else {
			throw new \InvalidArgumentException("Password has to be a string. Received " . gettype($password));
		}
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
	public function isCorrectPassword($password)
	{
		$bcrypt = new Bcrypt();
		return $bcrypt->verify($password, $this->getPassword());
	}
	
	/**
	 * Add a purchase to this user. 
	 * @warning This method will only update this side of the relationship. 
	 *			You are advised to use the setUser() method of Purchase to establish a
	 *			relationship between a user and a purchase. 
	 */
	public function addPurchase($purchase)
	{
		$this->purchases[] = $purchase;
	}
	
	/**
	 * Add a list to this user.
	 * @warning	This method will only update this side of the relationship. 
	 * 			You are advised to use the addUser() method of BillingList to establish a
	 * 			relationship between a user and a list.
	 */
	public function addBillingList($billingList)
	{
		$this->billingLists[] = $billingList;
	}
}
