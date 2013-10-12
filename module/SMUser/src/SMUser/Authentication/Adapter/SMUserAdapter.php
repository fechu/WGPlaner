<?php
/**
 * @file SMUserAdapter.php
 * @date Oct 12, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Authentication\Adapter;

use Zend\Authentication\Adapter\AbstractAdapter;
use SMUser\Entity\Repository\UserRepositoryInterface;
use Zend\Authentication\Result;

/**
 * Authentication adapter that uses the User repository of SMUser.
 */
class SMUserAdapter extends AbstractAdapter
{
	protected $userRepository;
	protected $username;
	protected $password;
	
	/**
	 * @param string	$username 	The username
	 * @param string	$password	The password
	 * @param UserRepositoryInterface $userRepo
	 */
	public function __construct($username, $password, UserRepositoryInterface $userRepo) 
	{
		$this->username = $username;
		$this->password = $password;
		$this->userRepository = $userRepo;
	}
	
	public function authenticate()
	{
		/* @var $user \SMUser\Entity\UserInterface */
		$user = $this->userRepository->findOneByUsername($this->username);
		if (!$user) {
			$messages = array('Benutzername oder Passwort falsch!');
			return new Result(Result::FAILURE_IDENTITY_NOT_FOUND, $this->username, $messages);
		}
		
		if ($user->isCorrectPassword($this->password)) {
			// Success
			return new Result(Result::SUCCESS, $this->username);
		}
		else {
			$messages = array('Benutzername oder Passwort falsch!');
			return new Result(Result::FAILURE_CREDENTIAL_INVALID, $this->username, $messages);
		}
	}
}