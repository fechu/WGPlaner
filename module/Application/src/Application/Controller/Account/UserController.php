<?php
/**
 * @file UserController.php
 * @date Oct 15, 2013
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use Application\Form\SelectUserForm;
use SMCommon\Form\DeleteForm;

class UserController extends AbstractAccountController
{
	public function __construct()
	{
		$this->defaultId ='user';
	}

	/**
	 * Lists all users of an account.
	 */
	public function indexAction()
	{
		$account = $this->getAccount();

		return array(
			'users' 	=> $account->getUsers(),
			'account'	=> $account,
		);

	}

	/**
	 * The action that lets you add a user to an account.
	 */
	public function addAction()
	{
		$form = new SelectUserForm($this->em);

		$account = $this->getAccount();

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$user = $form->getSelectedUser();

				// Add the user to the purchase list
				if (!$account->hasUser($user)) {
					$account->addUser($user);
					$this->em->flush();
				}

				// Redirect to the users list
				return $this->redirect()->toRoute('account/users', array(
					'action' 	=> 'index',
					'accountid'	=> $this->getAccount()->getId(),
				));
			}
		}

		return array(
			'form'		=> $form,
			'account' 	=> $account
		);
	}

	public function removeAction()
	{
		$user = $this->getUser();
		if (!$user) {
			$this->setStatusCode(404);
			return;
		}

		$form = new DeleteForm('Entfernen');
		$account = $this->getAccount();

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {

			if (count($account->getUsers()) == 1) {
				// You can't remove the last user
				$this->logger->info('The last user of list ' . $account->getName() . ' cant be deleted!');
			}
			else {
				// Remove the user from this list.
				$account->removeUser($user);
				$this->em->flush();
			}

			// Go to user list
			return $this->redirect()->toRoute('account/users', array(
				'accountid'	=> $account->getId(),
			));
		}

		return array(
			'user' 		=> $user,
			'account'	=> $account,
			'form' 		=> $form,
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