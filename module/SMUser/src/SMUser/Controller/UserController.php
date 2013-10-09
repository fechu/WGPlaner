<?php
/**
 * @file UserController.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller;


use SMUser\Form\UserForm;
use Application\Entity\User;
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
		
		// Load the user
		$repo = $this->getUserRepository();
		$user = $repo->findOneById($id);
		if (!$user) {
			$this->response->setStatusCode(404);
			return;
		}
		
		$form = new UserForm();
		$form->bind($user);
				
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {

				
			// Set the data
			$form->setData($request->getPost());
						
			if ($form->isValid()) {
				// Valid data! Save!
				$repo->saveUser($user);
				
				// And redirect!
				return $this->redirect()->toRoute('user');
			}
		}
				
		return array(
			'form' => $form
		);	
	}
	
	/**
	 * Create a user
	 */
	public function createAction()
	{	
		$form = new UserForm();
		$form->setShowPasswordFields(true);
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$user = $this->getUserRepository()->createNewUser();
			$form->bind($user);
			
			// Set the data
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				// Valid new user! Save!
				$this->getUserRepository()->saveUser($user);
				
				// And redirect!
				// To to the user overview
				return $this->redirect()->toRoute('user');
			}
			
		}
		
		return array(
			'form' => $form
		);
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