<?php

/**
 * @file CombinedBillRepository.php
 * @date Nov 16, 2015
 * @author Sandro Meier
 */

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Implements basic methods for finding combined bills.
 */
class CombinedBillRepository extends EntityRepository {

	/**
	 * Find combined bills for a user.
	 * @param User $user
	 */
	public function findForUser($user)
	{
		$dql = "SELECT cb FROM \Application\Entity\CombinedBill cb INNER JOIN cb.bills b INNER JOIN b.userShares s WHERE ?1 MEMBER OF s.user";
		$qb = $this->getEntityManager()->createQuery($dql);
		$qb->setParameter(1, $user->getId());

		return $qb->getResult();
	}

}
