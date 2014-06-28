<?php
/**
 * @file BillController
 * @date June 26, 2014
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use Application\Entity\Account;
use Application\Entity\Purchase;
use Application\Form\BillForm;
use Application\Entity\Bill;
use Application\Form\DaterangeForm;
use SMCommon\Form\DeleteForm;
use Application\Form\SelectUserForm;

class BillController extends AbstractAccountController
{
	public function __construct()
	{
		$this->defaultId = 'bill';
	}

	public function indexAction()
	{
		$account = $this->getAccount();

		/* @var $billRepo \Application\Entity\Repository\BillRepository */
		$billRepo = $this->em->getRepository('Application\Entity\Bill');
		$bills = $billRepo->findForAccount($account);

		return array(
			'account' 	=> $account,
			'bills'		=> $bills,
		);
	}

	public function createAction()
	{
		// Create form and bind new bill to the form.
		$form = new BillForm();
		$bill = new Bill();
		$form->bind($bill);


		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {

				// Persist the bill
				$this->em->persist($bill);
				$this->em->flush();

				$parameters = array(
					'accountid' => $this->getId('account'),
					'billid'	=> $bill->getId(),
					'action'	=> 'add-purchases',
				);
				return $this->redirect()->toRoute('accounts/bills', $parameters);
			}
		}

		return array(
				'form' => $form,
		);
	}

	/**
	 * Action to add purchases to a bill.
	 */
	public function addPurchasesAction()
	{
		$account = $this->getAccount();
		$bill = $this->getBill();

		$form = new DaterangeForm();
		$form->getActionCollection()->setSubmitButtonTitle('Hinzufügen');

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {

				// Select all purchases in the timespan from this account and add
				// them to the bill.
				/* @var $repo \Application\Entity\Repository\PurchaseRepository */
				$repo = $this->em->getRepository('Application\Entity\Purchase');

				// Find and add the purchases
				$purchases = $repo->findInRange($form->getStartDate(),
												$form->getEndDate(),
												$account);
				$bill->addPurchases($purchases);

				$this->em->flush();

				// Redirect to the bill.
				$params = array(
					'accountid' => $account->getId(),
					'billid'	=> $bill->getId(),
					'action'	=> 'view',
				);
				$this->redirect()->toRoute('accounts/bills', $params);
			}
		}

		return array(
			'account' 	=> $account,
			'bill' 		=> $bill,
			'form' 		=> $form,
		);

	}

	public function deleteAction()
	{
		$bill = $this->getBill();
		if (!$bill) {
			$this->getResponse()->setStatusCode(404);
			$this->logger->info("Tried to delete bill with ID " .
								$this->getId('bill') .
								", but bill does not exist.");
		}

		$form = new DeleteForm();

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		if ($request->isPost()) {
			// Delete the bill
			$this->em->remove($bill);
			$this->em->flush();

			$params = array(
				'accountid' => $this->getId('account')
			);
			return $this->redirect()->toRoute('accounts/bills', $params);
		}

		return array(
			'form' => $form,
			'bill' => $bill,
		);
	}

	/**
	 * Add an user to a bill.
	 */
	public function addUserAction()
	{
		$form = new SelectUserForm($this->em);
		$form->getActionCollection()->setSubmitButtonTitle("Hinzufügen");

		$bill = $this->getBill();
		if (!$bill) {
			$this->getRequest()->setStatusCode(404);
			return;
		}

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		if ($request->isPost()) {
			$form->setData($request->getPost());
			if ($form->isValid()) {
				$user = $form->getSelectedUser();

				// Add the user to the bill
				$bill->addUser($user);
				$this->em->flush();

				// Redirect to the users list
				return $this->redirect()->toRoute('accounts/bills', array(
						'action' 	=> 'users',
						'accountid'	=> $this->getAccount()->getId(),
						'billid'	=> $bill->getId(),
				));
			}
		}

		return array(
				'form'		=> $form,
				'account' 	=> $this->getAccount(),
				'bill'		=> $bill,
		);
	}

	/**
	 * List the users that belong to this bill.
	 */
	public function usersAction()
	{
		$bill = $this->getBill();

		// We need a bill
		if (!$bill) {
			$this->getRequest()->setStatusCode(404);
			return;
		}

		return array(
			'account'	=> $this->getAccount(),
			'bill' 		=> $bill,
		);

	}
}