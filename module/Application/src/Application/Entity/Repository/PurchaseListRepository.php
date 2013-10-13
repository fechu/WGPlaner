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

class PurchaseListRepository extends EntityRepository 
{
	public function findActive($date, $orderBy = NULL, $limit = NULL, $offset = NULL) 
	{
		$queryBuilder = $this->getEntityManager()->createQueryBuilder();
		$queryBuilder->select('purchaseList');
		$queryBuilder->from('Application\Entity\PurchaseList', 'purchaseList');
		
		$queryBuilder->where('purchaseList.startDate < :date');
		$queryBuilder->andWhere('purchaseList.endDate > :date');
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
		
		return $queryBuilder->getQuery()->getResult();
	}
	
	public function findNotActive($date, $orderBy = NULL, $limit = NULL, $offset = NULL) 
	{
		$queryBuilder = $this->getEntityManager()->createQueryBuilder();
		$queryBuilder->select('purchaseList');
		$queryBuilder->from('Application\Entity\PurchaseList', 'purchaseList');
		
		$queryBuilder->where('purchaseList.startDate > :date');
		$queryBuilder->orWhere('purchaseList.endDate < :date');
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
		
		return $queryBuilder->getQuery()->getResult();
	}
}
