<?php
/**
 * @file PurchaseController.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;

use SMCommon\Controller\AbstractActionController;
use Application\Entity\PurchaseList;
use Application\Form\PurchaseForm;
use Application\Entity\Purchase;

class PurchaseController extends AbstractActionController
{
	/**
	 * Lists all purchases for of a purchase list.
	 */
	public function indexAction()
	{
		
		$purchaseList = $this->getPurchaseList();
		if (!$purchaseList) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		return array(
			'purchaseList'	=> $purchaseList,
		);
	}	
	
	/**
	 * Add a purchase to a purchase list.
	 */
	public function addAction()
	{
		$purchaseList = $this->getPurchaseList();
		if (!$purchaseList) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
	
		$form = new PurchaseForm();
	
		$purchase = new Purchase();
		$form->bind($purchase);
	
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				// Set the logged in user user who did the purchase.
				$purchase->setUser($this->identity());
				$purchaseList->addPurchase($purchase);	// Add the purchase to this list.
				$this->em->persist($purchase);
				$this->em->flush();
				
				// Show all purchases of the list
				return $this->redirect()->toRoute('purchase-list/purchase', array(
					'purchaselistid' 	=> $purchaseList->getId(),
				));
			}
		}
		else {
			// Set default slip number
			/* @var $repo \Application\Entity\Repository\PurchaseRepository */
			$repo = $this->em->getRepository('Application\Entity\Purchase');
			$form->setSlipNumber($repo->findNextSlipNumber());
		}
	
		return array(
			'form' => $form,
			'purchaseList' => $purchaseList,
		);
	}
	
	/**
	 * Takes the id from the route and fetches the purchase list.
	 * @return PurchaseList|NULL 
	 */
	protected function getPurchaseList()
	{
		$id = $this->getId('purchaselist');
		
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		
		return $repo->find($id);
	}
}