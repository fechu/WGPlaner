<?php
/**
 * @file BillController
 * @date June 26, 2014
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use Application\Entity\Account;
use Application\Entity\Purchase;

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
}