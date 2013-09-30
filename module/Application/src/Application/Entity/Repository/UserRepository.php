<?php
/**
 * @file UserRepository.php
 * @date Sep 30, 2013 
 * @author Sandro Meier
 */

namespace Application\Entity\Repository;
use Doctrine\ORM\EntityRepository;
use SMUser\Entity\Repository\UserRepositoryInterface;

class UserRepository extends EntityRepository implements UserRepositoryInterface
{
	public function findUserByUsername($username)
	{
		return $this->findOneBy(array('username' => $username));
	}
	
}
