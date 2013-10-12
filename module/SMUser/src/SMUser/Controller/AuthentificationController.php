<?php
/**
 * @file AuthentificationController.php
 * @date Oct 12, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller;

use SMUser\Form\LoginForm;
use SMUser\Authentication\Adapter\SMUserAdapter;
class AuthentificationController extends AbstractActionController
{
	public function loginAction()
	{
		$form = new LoginForm();
		
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		
		$messages = array();
		
		if ($request->isPost()) {
			$form->setData($request->getPost());
			
			if ($form->isValid()) {
				
				// Authenticate
				$data = $form->getData();
				$adapter = new SMUserAdapter($data['username'], $data['password'], $this->getUserRepository());
				
				/* @var $authService \Zend\Authentication\AuthenticationService */
				$authService = $this->getServiceLocator()->get('smuser.auth_service');
				$authService->setAdapter($adapter);
				
				// Authenticate
				$result = $authService->authenticate();

				if ($result->isValid()) {
					// Successful logged in!
					return $this->redirect()->toRoute('user');
				}
				else {
					$messages = $result->getMessages();
				}
			}
		}
		
		return array(
			'form' => $form,
			'messages' => $messages,
		);
	}
	
	public function logoutAction()
	{
		$authService = $this->getServiceLocator()->get('smuser.auth_service');
		$authService->clearIdentity();
		
		return $this->redirect()->toRoute('auth');
	}
}