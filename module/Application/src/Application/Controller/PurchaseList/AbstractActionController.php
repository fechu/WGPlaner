<?php
/**
 * @file AbstractActionController.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;

use SMCommon\Controller\AbstractActionController as ActionController;
use Application\Entity\PurchaseList;
use Application\Entity\Purchase;

class AbstractActionController extends ActionController
{
	/**
	 * Takes the id from the route and fetches the purchase list.
	 * @return PurchaseList
	 */
	protected function getPurchaseList()
	{
		$id = $this->getId('purchaselist');
	
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
	
		return $repo->find($id);
	}
	
	/**
	 * Takes the purchaseId from the route and fetches the product.
	 * @return Purchase
	 */
	protected function getPurchase()
	{
		$id = $this->getId('purchase');
		
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');
		
		return $repo->find($id);
	}
}