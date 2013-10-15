<?php
/**
 * @file UserController.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;


use Application\Form\SelectUserForm;
use SMCommon\Form\DeleteForm;
class UserController extends AbstractActionController
{
	public function __construct()
	{
		$this->defaultId ='user';
	}
	
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
	
	public function removeAction()
	{
		$user = $this->getUser();
		if (!$user) {
			$this->setStatusCode(404);
			return;
		}
		
		$form = new DeleteForm();
		$purchaseList = $this->getPurchaseList();
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		
		if ($request->isPost()) {
			// Remove the user from this list.
			$purchaseList->removeUser($user);
			$this->em->flush();
			
			// Go to user list
			return $this->redirect()->toRoute('purchase-list/user', array(
				'purchaselistid'	=> $purchaseList->getId(),
			));
		}
		
		return array(
			'user' => $user,
			'purchaseList'	=> $purchaseList,
			'form' => $form,
		);
	}
	
	/**
	 * Loads and returns the user based on the userid from the route.
	 */
	protected function getUser()
	{
		if ($id = $this->getId()) {
			$user = $this->em->find('Application\Entity\User', $id);
			return $user;
		}
	} 
}