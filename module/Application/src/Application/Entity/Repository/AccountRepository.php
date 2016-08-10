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
class AccountRepository extends EntityRepository
{

	/**
	 * Finds the account to which all unassigned purchases are automatically assigned.
	 */
	public function findUnassignedAccount()
	{
		$queryBuilder = $this->createQueryBuilder('account');
		$queryBuilder->andWhere('account.name = :name');
		$queryBuilder->setParameter('name', 'unassigned');

		return $queryBuilder->getQuery()->getSingleResult();
	}

	/**
	 * Finds all accounts for a user which are not archived.
	 */
	public function findForUser($user, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQueryBuilder = false)
	{
		$queryBuilder = $this->createQueryBuilder('account');
		$this->restrictUser($queryBuilder, $user);
		$queryBuilder->andWhere('account.archived = 0');

		if ($returnQueryBuilder) {
			return $queryBuilder;
		}

		return $queryBuilder->getQuery()->getResult();
	}

	/**
	 * Finds all accounts for a user which are archived.
	 */
	public function findArchivedForUser($user, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQueryBuilder = false)
	{
		$queryBuilder = $this->createQueryBuilder('account');
		$this->restrictUser($queryBuilder, $user);
		$queryBuilder->andWhere('account.archived = 1');

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
