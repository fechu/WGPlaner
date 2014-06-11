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

	/**
	 * Gets a string from the route (GET parameter) and tries to get a date out of it.
	 * The date needs to have the following format:
	 *
	 * Day-Month-Year
	 *
	 * e.g:
	 *
	 * 3.7.1992
	 *
	 * @param string $key The key under which to find the date in the parameter bag.
	 * @return A date when the operation is successful or false otherwise.
	 */
	protected function getDateFromRoute($key)
	{
		$dateString = $this->params()->fromQuery($key, false);
		if ($dateString !== false) {
			// Try to parse it.
			/* @var $date \DateTime */
			$date = \DateTime::createFromFormat("d-m-Y", $dateString);

			if ($date !== false) {
				$date->setTime(0, 0, 0);
			}

			// $date is now either a valid DateTime object or false because
			// createFromFormat returns false on failure.
			return $date;
		}
		else {
			return false;
		}

	}
}