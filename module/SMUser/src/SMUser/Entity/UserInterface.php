<?php
/**
 * @file User.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Entity;

/**
 * The interface a user has to implement
 */
interface UserInterface 
{
	/**
	 * Set the username
	 * 
	 * @param string	$username
	 */
	public function setUsername($username);
	
	/**
	 * @return string	The username
	 */
	public function getUsername();
	
	/**
	 * Set the full name of the user
	 * 
	 * @param string $fullname
	 */
	public function setFullname($fullname);
	
	/**
	 * @return	 string		The full name of the user		
	 */
	public function getFullname();
	
	/**
	 * Set the email address
	 *
	 * @param	 string	$emailAddress		
	 */
	public function setEmailAddress($emailAdress);
	
	/**
	 * @return	 string		The email address		
	 */
	public function getEmailAddress();
	
	/**
	 * Set the password. 
	 * 
	 * @param string $password	The password.
	 */
	public function setPassword($password);
	
	/**
	 * @return string The hash of the password.
	 */
	public function getPassword();
}