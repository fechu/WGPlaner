<?php
/**
 * @file AccountController.php
 * @date Nov 28, 2013
 * @author Sandro Meier
 */

namespace API\Controller;

use Zend\View\Model\JsonModel;
use DoctrineModule\Stdlib\Hydrator\DoctrineObject;

class AccountController extends AbstractRestfulController
{
	public function __construct()
	{
		$this->identifierName = "accountid";
	}

	/**
	 * Get accounts for the authenticated user.
	 *
	 */
	public function getAccount()
	{
		/* @var $repo \Application\Entity\Repository\AccountRepository */
		$repo = $this->em->getRepository('Application\Entity\Account');

		$user = $this->identity();
		if ($user) {

			// Active or all lists
        	$lists = $repo->findForUser($user);

			$accounts = array_map(function($account) {
				/* @var $list \Application\Entity\Account */
				return array(
					"id"	=> $account->getId(),
					"name" 	=> $account->getName(),
				);
			}, $lists);


			return new JsonModel(array('account' => $accounts));
		}
		else {
			return $this->invalidAPIKeyResponse();
		}
	}
}