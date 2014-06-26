<?php
/**
 * @file BillRepository.php
 * @date June 18, 2014
 * @author Sandro Meier
 */

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Implements basic methods for finding bills.
 */
class BillRepository extends EntityRepository
{
	/**
	 * Finds all bills which contain purchases from the given account
	 *
	 * @param Account $account The account
	 * @param string $orderBy
	 * @param string $limit
	 * @param string $offset
	 * @param string $returnQueryBuilder
	 */
	public function findForAccount($account, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQueryBuilder = false)
	{
		$queryBuilder = $this->createQueryBuilder('bill');

		$queryBuilder->leftJoin('bill.purchases', 'p');
		$queryBuilder->leftjoin('p.account', 'a');

		$queryBuilder->where('a = :account');
		$queryBuilder->setParameter('account', $account);

		if ($returnQueryBuilder) {
			return $queryBuilder;
		}

		return $queryBuilder->getQuery()->getResult();
	}
}