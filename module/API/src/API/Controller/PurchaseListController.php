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
	
	/**
	 * Get purchase lists for the authenticated user. 
	 * 
	 * Pameters: 
	 * 
	 * active	boolean 	Return all lists or only currently active lists.
	 * 						Accepts 0 and 1 as values
	 */
	public function getList()
	{
		/* @var $repo \Application\Entity\Repository\ListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		
		$user = $this->identity();
		if ($user) {
			
			// Active or all lists
			$active = $this->params()->fromQuery('active', false);
			if ((bool)$active) {
				$lists = $repo->findActiveForUser(new \DateTime(), $user);
			}
			else {
				$lists = $repo->findForUser($user);
			}

			$lists = array_map(function($list) {
				/* @var $list \Application\Entity\PurchaseList */
				return array(
					"id"	=> $list->getId(),
					"name" 	=> $list->getName(),
				);
			}, $lists);
			
			
			return new JsonModel(array('purchaseList' => $lists));
		}
		else {
			return $this->invalidAPIKeyResponse();
		}
	}
}