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

	public function setPassword($password)
	{
		if (is_string($password)) {
			$this->password = $password;
		}
		else {
			throw new \InvalidArgumentException("Password has to be a string. Received " . gettype($password));
		}
	}
	
	public function getPassword()
	{
		return $this->password;
	}
	
}
