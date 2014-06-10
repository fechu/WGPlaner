<?php
/**
 * @file AbstractAccountController.php
 * @date Oct 15, 2013
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use SMCommon\Controller\AbstractActionController as ActionController;
use Application\Entity\Account;
use Application\Entity\Purchase;

class AbstractAccountController extends ActionController
{
	/**
	 * Takes the id from the route and fetches the account.
	 * @return Account
	 */
	protected function getAccount()
	{
		$id = $this->getId('account');

		/* @var $repo \Application\Entity\Repository\AccountRepository */
		$repo = $this->em->getRepository('Application\Entity\Account');

		return $repo->find($id);
	}

	/**
	 * Takes the purchaseId from the route and fetches the product.
	 * @return Purchase
	 */
	protected function getPurchase()
	{
		$id = $this->getId('purchase');

		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');

		return $repo->find($id);
	}
}