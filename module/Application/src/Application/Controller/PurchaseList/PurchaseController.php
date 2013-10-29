<?php
/**
 * @file PurchaseController.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;

use Application\Entity\PurchaseList;
use Application\Form\PurchaseForm;
use Application\Entity\Purchase;

class PurchaseController extends AbstractActionController
{
	public function __construct()
	{
		$this->defaultId = 'purchase';
	}
	
	/**
	 * Lists all purchases for of a purchase list. 
	 * If a purchaseId is present in the route it will be forwarded to show that purchase.
	 */
	public function indexAction()
	{
		$purchaseId = $this->getId();
		$purchaseListId = $this->getId('purchaselist');
		
		// If we have an ID we redirect to the showPurchase action.
		if ($purchaseId && $purchaseListId) {
			return $this->forward()->dispatch('Application\Controller\PurchaseList\Purchase', array(
				'__NAMESPACE__'		=> 'Application\Controller\PurchaseList',
				'action' 			=> 'view',
				'purchaselistid'	=> $purchaseListId,
				'purchaseid'		=> $purchaseId,
			));
		}
		
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
				$purchase->setPurchaseList($purchaseList);	// Add the purchase to this list.
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
	
	public function editAction()
	{
		$purchase = $this->getPurchase();
		$purchaseList = $this->getPurchaseList();
		
		if ($purchase->getPurchaseList() != $purchaseList) {
			// Purchase is not in this list.
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$form = new PurchaseForm();
		$form->bind($purchase);
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				// Got valid data.
				$this->em->flush();
				
				return $this->redirect()->toRoute('purchase-list/purchase', array('action' => NULL), array(), true);
			}
		}
		
		return array(
			'purchase' => $purchase,
			'form'	=> $form,
		);
	}
	
	/**
	 * View a purchase
	 */
	public function viewAction()
	{
		$purchaseList = $this->getPurchaseList();
		$purchase = $this->getPurchase();
		
		if ($purchase->getPurchaseList() != $purchaseList) {
			// Purchase is not in this purchase list!
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		return array(
			'purchase' => $purchase
		);
	}

}