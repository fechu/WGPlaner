<?php
/**
 * @file UserController.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller;


class UserController extends AbstractActionController
{
	/**
	 * Show a list of all users to modify them.
	 */
	public function indexAction()
	{
		/* @var $repo \SMUser\Entity\Repository\UserRepositoryInterface */
		$repo = $this->getUserRepository();
		
		return array(
			'users' => $repo->findAll(),
		);
	}
	
	/**
	 * Edit a user.
	 */
	public function editAction()
	{
		// We require an id.
		if (!$id = $this->requireId()) {
			return;
		}
		
		
		
	}
	
	/**
	 * Create a user
	 */
	public function createAction()
	{
		
	}
	
	/**
	 * Change the password of a user.
	 */
	public function changePasswordAction()
	{
		// We require an id
		if (!$id = $this->requireId()) {
			return;
		}
	}
}