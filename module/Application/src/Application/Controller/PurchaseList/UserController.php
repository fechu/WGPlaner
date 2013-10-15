<?php
/**
 * @file UserController.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\PurchaseList;


class UserController extends AbstractActionController
{
	
	/**
	 * Lists all users of a purchase list.
	 */
	public function indexAction()
	{
		$purchaseList = $this->getPurchaseList();
		
		return array(
			'users' => $purchaseList->getUsers(),
		);
		
	}
	
	/**
	 * The action that lets you add a user to a purchase list.
	 */
	public function addAction()
	{
		
	}
}