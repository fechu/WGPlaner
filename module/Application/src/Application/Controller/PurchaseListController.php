<?php
/**
 * @file PurchaseListController.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller;

use SMCommon\Controller\AbstractActionController;
use Application\Form\PurchaseListForm;
use Application\Entity\PurchaseList;
use Application\Form\PurchaseForm;
use Application\Entity\Purchase;

class PurchaseListController extends AbstractActionController
{
	/**
	 * List all Purchase lists where you are a user. 
	 */
	public function indexAction()
	{
		// If we have an ID we redirect to the showPurchase action.
		if ($id = $this->getId()) {
			return $this->forward()->dispatch('Application\Controller\PurchaseList', array(
				'action' 	=> 'purchases',
				'id'		=> $id,
			));
		}
		
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		$lists = $repo->findActive(new \DateTime());
		
		return array(
			'lists' => $lists,
		);
	}
	
	public function createAction()
	{
		$form = new PurchaseListForm();
		
		$purchaseList = new PurchaseList();
		$form->bind($purchaseList);
		
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				
				// Add the logged in user to this list
				$purchaseList->addUser($this->identity());
				
				// Persist the purchase list
				$this->em->persist($purchaseList);
				$this->em->flush();
				
				return $this->redirect()->toRoute('purchase-list');
			}	
		}
		
		return array(
			'form' => $form,
		);
	}
	
	public function editAction()
	{
		if (!$id = $this->requireId()) {
			return;
		}
		
		$form = new PurchaseListForm();
		
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');

		$purchaseList = $repo->find($id);
		
		if (!$purchaseList) {
			// not found
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		$form->bind($purchaseList);
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				// Persist the purchase list
				$this->em->flush();
				
				return $this->redirect()->toRoute('purchase-list');
			}
		}
		
		return array(
			'form' 			=> $form,
			'purchaseList' 	=> $purchaseList,
		);
	}
	
	
	public function addPurchaseAction()
	{
		if (!$id = $this->requireId()) {
			return;
		}
		
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		$purchaseList = $repo->find($id);
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
				
				return $this->redirect()->toRoute('purchase-list');
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
	 * Shows all Not active lists
	 */
	public function notActiveAction()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		$lists = $repo->findNotActive(new \DateTime());
		
		return array(
			'lists' => $lists,
		);
	}
	
	public function purchasesAction()
	{
		if (!$id = $this->requireId()) {
			return;
		}
		
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		
		$purchaseList = $repo->find($id);
		if (!$purchaseList) {
			$this->getResponse()->setStatusCode(404);
			return;
		}
		
		return array(
			'purchaseList'	=> $purchaseList,
		);
	}
}