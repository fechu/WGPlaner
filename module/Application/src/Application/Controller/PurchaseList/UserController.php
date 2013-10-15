<?php
/**
 * @file UserController.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;


use Application\Form\SelectUserForm;
class UserController extends AbstractActionController
{
	
	/**
	 * Lists all users of a purchase list.
	 */
	public function indexAction()
	{
		$purchaseList = $this->getPurchaseList();
		
		return array(
			'users' 		=> $purchaseList->getUsers(),
			'purchaseList'	=> $purchaseList,
		);
		
	}
	
	/**
	 * The action that lets you add a user to a purchase list.
	 */
	public function addAction()
	{
		$form = new SelectUserForm($this->em);
		
		$purchaseList = $this->getPurchaseList();
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$user = $form->getSelectedUser();
				
				// Add the user to the purchase list
				if (!$purchaseList->hasUser($user)) {
					$purchaseList->addUser($user);
					$this->em->flush();
				}
				
				// Redirect to the users list
				return $this->redirect()->toRoute('purchase-list/user', array(
					'action' 			=> 'index',
					'purchaselistid'	=> $this->getPurchaseList()->getId(),
				));
			}
		}
		
		return array(
			'form'			=> $form,
			'purchaseList' 	=> $this->getPurchaseList(),
		);
	}
}