<?php
/**
 * @file ListRepository.php
 * @date Oct 27, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * Implements basic methods for finding lists. 
 * This class can be used as a repository directly or you can subclass it if you need
 * to modify or add methods.
 */
class ListRepository extends EntityRepository
{
	/**
	 * Find all active lists. 
	 * Lists are called active if the given date is between the start and the enddate.
	 */
	public function findActive($date, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQuerybuilder = false)
	{
		$queryBuilder = $this->createQueryBuilder('list');
	
		$queryBuilder->where('list.startDate < :date');
		$queryBuilder->andWhere('list.endDate > :date');
		$queryBuilder->setParameter('date', $date, 'utcdatetime');
	
		// Add all order by thingies!
		if (is_array($orderBy)) {
			foreach ($orderBy as $key => $ascOrDesc) {
				$queryBuilder->addOrderBy($key, $ascOrDesc);
			}
		}
	
		// Limit
		if ($limit) {
			$queryBuilder->setMaxResults($limit);
		}
	
		// Offset
		if ($offset) {
			$queryBuilder->setFirstResult($offset);
		}
	
		// Return the query builder?
		if ($returnQuerybuilder) {
			return $queryBuilder;
		}
	
		return $queryBuilder->getQuery()->getResult();
	}
	
	/**
	 * The same as findActive() but restricted to a specific user.
	 * @see findActive()
	 */
	public function findActiveForUser($date, $user, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQuerybuilder = false)
	{
		// Get the querybuilder
		$queryBuilder = $this->findActive($date, $orderBy, $limit, $offset, true);
	
		// Restrict the user
		$queryBuilder->join('list.users', 'user');
		$queryBuilder->andWhere('user = :user');
		$queryBuilder->setParameter('user', $user);
	
		// Return query builder for further modification?
		if ($returnQuerybuilder) {
			return $queryBuilder;
		}
	
		return $queryBuilder->getQuery()->getResult();
	}
	
	/**
	 * The opposite to find active. The date must be outside of the timespan given by 
	 * start and enddate.
	 */
	public function findNotActive($date, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQueryBuilder = false)
	{
		$this->createQueryBuilder('list');
	
		// The date must be outside the start and enddate.
		$queryBuilder->where('list.startDate > :date');
		$queryBuilder->orWhere('list.endDate < :date');
		$queryBuilder->setParameter('date', $date, 'utcdatetime');
	
		// Add all order by thingies!
		if (is_array($orderBy)) {
			foreach ($orderBy as $key => $ascOrDesc) {
				$queryBuilder->addOrderBy($key, $ascOrDesc);
			}
		}
	
		// Limit
		if ($limit) {
			$queryBuilder->setMaxResults($limit);
		}
	
		// Offset
		if ($offset) {
			$queryBuilder->setFirstResult($offset);
		}
	
		// Return query builder for further modification?
		if ($returnQueryBuilder) {
			return $queryBuilder;
		}
	
		return $queryBuilder->getQuery()->getResult();
	}
	
	/**
	 * The oppositve of findActiveForUser().
	 */
	public function findNotActiveForUser($date, $user, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQuerybuilder = false)
	{
		$queryBuilder = $this->findNotActive($date, $orderBy, $limit, $offset, true);
	
		$this->restrictUser($queryBuilder, $user);
	
		// Return query builder?
		if ($returnQuerybuilder) {
			return $queryBuilder;
		}
	
		return $queryBuilder->getQuery()->getResult();
	}
	
	public function findForUser($user, $orderBy = NULL, $limit = NULL, $offset = NULL, $returnQueryBuilder = false) 
	{
		$queryBuilder = $this->createQueryBuilder('list');
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
		$queryBuilder->join('list.users', 'user');
		$queryBuilder->andWhere('user = :user');
		$queryBuilder->setParameter('user', $user);
		
		return $queryBuilder;
	}
}