<?php
/**
 * @file PurchaseListController.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;

use Application\Form\PurchaseListForm;
use Application\Entity\PurchaseList;
use Application\Form\PurchaseForm;
use Application\Entity\Purchase;
use Application\Form\SelectPurchaseListForm;

class PurchaseListController extends AbstractActionController
{
	
	public function __construct()
	{
		// Default id for PurchaseList objects in the route is 'purchaselist'
		$this->defaultId = 'purchaselist';
	}
	
	/**
	 * List all Purchase lists where you are a user. 
	 */
	public function indexAction()
	{
		// If we have an ID we redirect to the showPurchase action.
		if ($id = $this->getId()) {
			return $this->forward()->dispatch('Application\Controller\PurchaseList\Purchase', array(
				'__NAMESPACE__'		=> 'Application\Controller\PurchaseList',
				'action' 			=> 'index',
				'purchaselistid'	=> $id,
			));
		}
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		$lists = $repo->findActiveForUser(new \DateTime(), $this->identity());
		
		return array(
			'lists' => $lists,
		);
	}
	
	/**
	 * Create a new purchase list. 
	 * 
	 * This action supports the query parameter "template". This should be an id of 
	 * another purchase list. Then the data of that purchase list will be used to prefill 
	 * the purchase list. 
	 */
	public function createAction()
	{
		$form = new PurchaseListForm();

		$templateId = $this->params()->fromQuery('template');
		$templatePurchaseList = NULL;
		if ($templateId) {
			// Load the purchase list as a template
			/* @var $repo \Application\Entity\Repository\ListRepository */
			$repo = $this->em->getRepository('Application\Entity\PurchaseList');
			$templatePurchaseList = $repo->find($templateId);
			
			if (!$templatePurchaseList) {
				$this->logger->notice('Template to create purchase list does not exist!', 
						array('templateId' => $templateId)
				);
			}
		}
		$purchaseList = new PurchaseList($templatePurchaseList);
		
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
	
	/**
	 * Select a template to create a purchase list.
	 */
	public function selectTemplateAction()
	{
		$form = new SelectPurchaseListForm($this->em, $this->identity());
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$template = $form->getSelectedPurchaseList();
				
				// Redirect to create a purchase list with this template
				$query = array('query' => array(
					'template' => $template->getId(),
				));
				$params = array(
					'action' => 'create',
				);
				return $this->redirect()->toRoute('purchase-list/action', $params, $query);
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
	
	
	
	
	/**
	 * Shows all Not active lists
	 */
	public function notActiveAction()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
		$lists = $repo->findNotActiveForUser(new \DateTime(), $this->identity());
		
		return array(
			'lists' => $lists,
		);
	}
	
	public function billAction()
	{
		$purchaseList = $this->getPurchaseList();

		return array(
			'purchaseList' => $purchaseList,
		);
	}
}