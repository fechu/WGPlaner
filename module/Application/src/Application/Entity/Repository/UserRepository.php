<?php
/**
 * @file UserRepository.php
 * @date Sep 30, 2013 
 * @author Sandro Meier
 */

namespace Application\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use SMUser\Entity\Repository\UserRepositoryInterface;
use Application\Entity\User;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
	public function findOneByUsername($username)
	{
		return $this->findOneBy(array('username' => $username));
	}

	public function findOneById($id)
	{
		return $this->find($id);
	}
	
	public function findOneByApiKey($key)
	{
		return $this->findOneBy(array('apiKey' => $key));
	}
	
	public function createNewUser()
	{
		// Create and return a new user
		$user = new User();
		return $user;
	}
	
	public function saveUser($user)
	{
		// Save (persist) the user
		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush($user);
	}
	
	public function removeUser($user)
	{
		/* @var $user \Application\Entity\User */
		$user->delete(); 	// Soft delete the user
		$this->getEntityManager()->flush($user);
	}
}
