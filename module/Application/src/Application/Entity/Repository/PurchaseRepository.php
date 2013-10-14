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

class PurchaseRepository extends EntityRepository 
{
	/**
	 * @return int The next available slip number. 
	 */
	public function findNextSlipNumber()
	{
		$query = $this->createQueryBuilder('p');
		$query->select('MAX(p.slipNumber) as maxSlipNumber');
		
		$result = $query->getQuery()->getSingleResult();
		return $result['maxSlipNumber'] + 1;
	}
}
