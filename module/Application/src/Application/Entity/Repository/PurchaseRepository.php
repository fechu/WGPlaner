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
		$query = $this->getRangeQueryBuilder($startDate, $endDate, $account);
		return $query->getQuery()->getResult();
	}

	/**
	 * Find the total of all purchases on a daily basis in an interval.
	 * The returned associative array will contain all days. Even if the expenses during one
	 * day where 0.
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param Account $account
	 */
	public function findDailyAmountInRange($startDate, $endDate, $account = NULL)
	{
		// Eliminate time parts in start and enddate
		$startDate = new \DateTime($startDate->format('Y-m-d'));
		$endDate = new \DateTime($endDate->format('Y-m-d'));

		// We use raw SQL as Doctrine cannot use MySQL DATE function
		$sql = "SELECT DATE(date) AS date, SUM(amount) AS amount
				FROM Purchase
				INNER JOIN Account ON Purchase.account_id=Account.id
				WHERE date >= ? AND date <= ?";

		if ($account) {
			$sql .= " AND account_id = ?";
		}

		$sql .= " GROUP BY date ORDER BY date ASC";

		$statement = $this->getEntityManager()->getConnection()->prepare($sql);
		$arguments = array($startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'));

		if ($account) {
			$arguments[] = $account->getId();
		}

		$statement->execute($arguments);
		$result = $statement->fetchAll();

		// We need to create datetime objects in the date column. They are strings right now.
		$result = array_map(function($entry){
			$date = $entry['date'];
			$date = new \DateTime($date);
			$entry['date'] = $date;
			return $entry;
		}, $result);

		// Fill in the missing dates. ( All days that had no expenses )
		$currentDate = $startDate;
		$data = array();
		foreach ($result as $entry) {
			/* @var $entry \DateTime */
			$diff = $entry['date']->diff($currentDate, true);

			// Add the zero entries
			for ($i = 0; $i < ($diff->d - 1); $i++) {
				$date = clone $currentDate;
				$data[] = array(
					'date' => $date->add(new \DateInterval("P" . $i . "D")),
					'amount' => 0
				);
			}

			// Add the entry
			$data[] = $entry;
			$currentDate = $entry['date'];
		}

		// From the last entry to the enddate
		$diff = $currentDate->diff($endDate, true);
		for ($i = 0; $i < $diff->d; $i++) {
			$date = clone $currentDate;
            $data[] = array(
				'date' => $date->add(new \DateInterval("P" . $i . "D")),
				'amount' => 0
			);
		}

		return $data;
	}

	/**
	 * Find the total monthly amount in a date range. The result is grouped by month.
	 * The returned array will also contain entries for days at which the expenses were zero 0.
	 * @param \DateTime $startDate
	 * @param \DateTime $endDate
	 * @param Account $account
	 */
	public function findMonthlyAmountInRange($startDate, $endDate, $account = NULL)
	{
		// MySQL's MONTH() function is not available in Doctrine. So we use SQL directly.

		$sql = "SELECT DATE(date) AS date, SUM(amount) AS amount
				FROM Purchase
				INNER JOIN Account ON Purchase.account_id=Account.id
				WHERE date >= ? AND date <= ?";

		if ($account) {
			$sql .= " AND account_id = ?";
		}

		$sql .= " GROUP BY YEAR(date), MONTH(date) ORDER BY date ASC";

		$statement = $this->getEntityManager()->getConnection()->prepare($sql);
		$arguments = array($startDate->format('Y-m-d H:i:s'), $endDate->format('Y-m-d H:i:s'));

		if ($account) {
			$arguments[] = $account->getId();
		}

		$statement->execute($arguments);
		$result = $statement->fetchAll();

		// We need to create datetime objects in the date column. They are strings right now.
		$result = array_map(function($entry){
			$date = $entry['date'];
			$date = new \DateTime($date);
			$date = new \DateTime('first day of ' . $date->format('F Y'));
			$entry['date'] = $date;
			return $entry;
		}, $result);

		// Add the missing months. (e.g. months where the expenses were 0.
		$currentDate = $startDate;
		$data = array();
		foreach ($result as $entry) {
			/* @var $entry \DateTime */
			$diff = $entry['date']->diff($currentDate, true);

			// Add the zero entries
			for ($i = 0; $i < ($diff->m - 1); $i++) {
				$date = clone $currentDate;
				$data[] = array(
					'date' => $date->add(new \DateInterval("P" . $i . "M")),
					'amount' => 0
				);
			}

			// Add the entry
			$data[] = $entry;
			$currentDate = $entry['date'];
		}

		// From the last entry to the enddate
		$diff = $currentDate->diff($endDate, true);
		for ($i = 0; $i < $diff->m; $i++) {
			$date = clone $currentDate;
            $data[] = array(
				'date' => $date->add(new \DateInterval("P" . $i . "M")),
				'amount' => 0
			);
		}

		return $data;
	}


	/**
	 * Returns an array with stores and how much they appear overall pairs.
	 *
	 * @param \DateTime $startdate
	 * @param \DateTime $enddate
	 * @param Account $account
	 */
	public function findStoreCountsInRange($startDate, $endDate, $account = NULL)
	{
		$query = $this->getRangeQueryBuilder($startDate, $endDate, $account);

		// Adjust the query
		$query->select('purchase.store, COUNT(purchase.id) AS purchase_count');
		$query->groupBy('purchase.store');
		$query->orderBy('purchase_count', 'DESC');

		$result = $query->getQuery()->getResult();

		return $result;
	}

	public function findStoreAmountInRange($startDate, $endDate, $account = NULL)
	{
		$query = $this->getRangeQueryBuilder($startDate, $endDate, $account);

		// Adjust the query
		$query->select('purchase.store, SUM(purchase.amount) AS amount');
		$query->groupBY('purchase.store');
		$query->orderBy('amount', 'DESC');

		$result = $query->getQuery()->getResult();

		return $result;
	}

	/**
	 * Creates a query builder which selects all purchases in a date range.
	 * @param \DateTime $startDate All purchases after this date will be selected. Can be NULL.
	 * @param \DateTime $endDate All purchases before this date will be selected. Can be NULL.
	 * @param Account $account Only purchases assigned to this account will be selected. Can be NULL.
	 */
	protected function getRangeQueryBuilder($startDate, $endDate, $account = NULL)
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

		return $query;
	}
}
