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
				);
				return $this->redirect()->toRoute('accounts/bills', $parameters);
			}
		}

		return array(
				'form' => $form,
		);
	}
}