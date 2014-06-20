<?php
/**
 * @file AccountController.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use Application\Form\AccountForm;
use Application\Entity\Account;
use Application\Entity\Purchase;
use Application\Form\DaterangeForm;

class AccountController extends AbstractAccountController
{

	public function __construct()
	{
		// Default id for account objects in the route
		$this->defaultId = 'account';
	}

	/**
	 * List all accounts that the user has access to.
	 */
	public function indexAction()
	{
		// If we have an account ID we redirect to the showPurchase action.
		if ($id = $this->getId()) {
			return $this->forward()->dispatch('Application\Controller\Account\Purchase', array(
				'__NAMESPACE__'		=> 'Application\Controller\Account',
				'action' 			=> 'index',
				'accountid'			=> $id,
			));
		}
		/** @var $repo \Application\Entity\Repository\AccountRepository */
		$repo = $this->em->getRepository('Application\Entity\Account');
		$accounts = $repo->findForUser($this->identity());

		return array(
			'accounts' => $accounts,
		);
	}


	public function selectDateAction()
	{
		$account = $this->getAccount();
		if (!$account) {
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$form = new DaterangeForm();

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			$form->setData($request->getPost());

			if ($form->isValid()) {
				// Construct the route to which the user gets redirected.
				$startDate = $form->getStartDate();
				$formattedStartDate = $startDate->format('d-m-Y');
				$endDate = $form->getEndDate();
				$formattedEndDate = $endDate->format('d-m-Y');

				$params = array(
					'accountid' => $account->getId(),
					''
				);
				$options = array(
					'query' => array(
						'start-date' 	=> $formattedStartDate,
						'end-date' 		=> $formattedEndDate,
					)
				);
				// Redirect
				return $this->redirect()->toRoute('accounts/list-action', $params, $options);
			}
		}


		return array(
				'account' => $account,
				'form' => $form
		);
	}

	/**
	 * Create a new account.
	 */
	public function createAction()
	{
		// Create form and bind new account to form.
		$form = new AccountForm();
		$account = new Account();
		$form->bind($account);


		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {

				// Add the logged in user to the account to make sure he has
				// the right to change the account.
				$account->addUser($this->identity());

				// Persist the account
				$this->em->persist($account);
				$this->em->flush();

				return $this->redirect()->toRoute('accounts');
			}
		}

		return array(
			'form' => $form,
		);
	}

	public function editAction()
	{
		// To edit an account we require an ID.
		if (!($id = $this->requireId())) {
			return;
		}

		$form = new AccountForm();

		/* @var $repo \Application\Entity\Repository\AccountRepository */
		$repo = $this->em->getRepository('Application\Entity\Account');

		$account = $repo->find($id);

		if (!$account) {
			// not found
			$this->getResponse()->setStatusCode(404);
			return;
		}

		$form->bind($account);

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				// Persist the account
				$this->em->flush($account);

				return $this->redirect()->toRoute('accounts');
			}
		}

		return array(
			'form' 			=> $form,
			'account'	 	=> $account
		);
	}
}