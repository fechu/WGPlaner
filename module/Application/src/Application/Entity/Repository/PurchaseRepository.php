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
use Application\Entity\Account;

class PurchaseRepository extends EntityRepository
{
	/**
	 * @param Account	$account 	The purchase list in which you need to know the next slip
	 * 										number.
	 *
	 * @return int The next available slip number.
	 */
	public function findNextSlipNumber($account)
	{
		$query = $this->createQueryBuilder('p');
		$query->select('MAX(p.slipNumber) as maxSlipNumber');

		$query->join('p.account', 'account');
		$query->andWhere('account = :account');
		$query->setParameter('account', $account);

		$result = $query->getQuery()->getSingleResult();
		return $result['maxSlipNumber'] + 1;
	}

	/**
	 * Finds unique stores that where used.
	 */
	public function findUniqueStores()
	{
		$query = $this->createQueryBuilder('p');
		$query->select('DISTINCT p.store');

		$result = $query->getQuery()->getScalarResult();

		return array_map('current', $result);
	}

	/**
	 * Find purchases in a date range (including dates)
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param Account $account
	 */
	public function findInRange($startDate, $endDate, $account = NULL)
	{
		$query = $this->createQueryBuilder('purchase');
		$query->orderBy("purchase.date", "ASC");

		// Set start date
		if ($startDate !== NULL) {
			$query->andWhere('purchase.date >= :startDate');
			$query->setParameter('startDate', $startDate);
		}

		// Set end date
		if ($endDate !== NULL) {
			$query->andWhere('purchase.date <= :endDate');
			$query->setParameter('endDate', $endDate);
		}

		// Set account
		if ($account !== NULL) {
			$query->join('purchase.account', 'account');
			$query->andWhere('account = :account');
			$query->setParameter('account', $account);
		}

		return $query->getQuery()->getResult();
	}

}
