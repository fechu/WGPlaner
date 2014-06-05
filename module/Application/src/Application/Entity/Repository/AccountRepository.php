<?php
/**
 * @file AccountRepository.php
 * @date Oct 27, 2013
 * @author Sandro Meier
 */

namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Implements basic methods for finding accounts.
 */
class ListRepository extends EntityRepository
{

	public function findForUser($user, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQueryBuilder = false)
	{
		$queryBuilder = $this->createQueryBuilder('account');
		$this->restrictUser($queryBuilder, $user);

		if ($returnQueryBuilder) {
			return $queryBuilder;
		}

		return $queryBuilder->getQuery()->getResult();
	}

	/**
	 * Restrict a query builder to only select lists of $user.
	 */
	protected function restrictUser($queryBuilder, $user)
	{
		// Restrict the user
		$queryBuilder->join('account.users', 'user');
		$queryBuilder->andWhere('user = :user');
		$queryBuilder->setParameter('user', $user);

		return $queryBuilder;
	}
}