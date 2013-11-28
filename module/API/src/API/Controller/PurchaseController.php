<?php
/**
 * @file PurchaseController.php
 * @date Nov 25, 2013 
 * @author Sandro Meier
 */
 
namespace API\Controller;

use Zend\View\Model\JsonModel;
use SMCommon\Controller\AbstractRestfulController;

class PurchaseController extends AbstractRestfulController
{
	
	public function storesAction()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');
		
		return new JsonModel($repo->findUniqueStores());
	}
	
	public function create($data)
	{
		var_dump($data);
	}
}