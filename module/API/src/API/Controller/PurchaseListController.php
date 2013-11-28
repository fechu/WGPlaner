<?php
/**
 * @file PurchaseListController.php
 * @date Nov 28, 2013 
 * @author Sandro Meier
 */
 
namespace API\Controller;

use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class PurchaseListController extends AbstractRestfulController
{
	public function __construct()
	{
		$this->identifierName = "purchaselistid";
	}
	
	public function getList()
	{
		/* @var $repo \Application\Entity\Repository\ListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		
		$user = $this->identity();
		if ($user) {
			$lists = $repo->findForUser($user);
			$hydrator = new DoctrineObject($this->em, 'Application\Entity\PurchaseList');
			$lists = array_map(function($list) use ($hydrator) {
				/* @var $list \Application\Entity\PurchaseList */
				$listData = $hydrator->extract($list);
				
				// Remove unwanted data
				unset($listData['purchases']);
				unset($listData['users']);
				
				return $listData;
				
			}, $lists);
			
			
			return new JsonModel(array('purachseList' => $lists));
		}
		else {
			return $this->invalidAPIKeyResponse();
		}
	}
}