<?php
/**
 * @file UserController.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller;


use SMUser\Form\UserForm;
use Application\Entity\User;
use SMUser\Form\PasswordForm;
use SMCommon\Form\DeleteForm;

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
		
		// Load that user
		/* @var $user \SMUser\Entity\UserInterface */
		$user = $this->getUserRepository()->findOneById($id);
		
		$form = new PasswordForm();
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				$password = $form->getPassword();
				
				$user->setPassword($password);
				
				$this->getUserRepository()->saveUser($user);
				
				// Forward to the user list
				return $this->redirect()->toRoute('user');
			}
		}
		
		return array(
			'form' => $form,
			'user' => $user,
		);
	}
	
	
	/**
	 * Delete a user
	 */
	public function deleteAction()
	{
		if (!$id = $this->requireId()) {
			return;
		}
		
		$form = new DeleteForm();
		
		// Load the user
		$user = $this->getUserRepository()->findOneById($id);
		if (!$user) {
			// User does not exist
			$this->response->setStatusCode(404);
			return;
		}
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			// Delete the user
			$this->getUserRepository()->removeUser($user);
			
			return $this->redirect()->toRoute('user');
		}
		
		
		return array(
			'form' => $form,
			'user' => $user,
		);
	}
}