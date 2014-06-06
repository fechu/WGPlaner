<?php
/**
 * @file AbstractRestfulController.php
 * @date Nov 28, 2013
 * @author Sandro Meier
 */

namespace API\Controller;

use SMCommon\Controller\AbstractRestfulController as SMCommonRestfulController;
use Application\Entity\Account;

class AbstractRestfulController extends SMCommonRestfulController
{
	/**
	 * Get the identity provided.
	 * Authentification is done via the API key in the X-API-KEY header.
	 * @return A user object or void if authentication failed.
	 */
	protected function identity()
	{
		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();

		// No one is logged in by default.
		$user = null;

		if ($request->getHeaders()->has('X-API-KEY')) {
			$apiKey = $request->getHeader('X-API-KEY')->getFieldValue();
			if ($apiKey) {
				// Check if this belong to a user.
				/* @var $repo \Application\Entity\Repository\UserRepository */
				$repo = $this->em->getRepository('Application\Entity\User');

				$user = $repo->findOneByApiKey($apiKey);
			}
		}

		return $user;
	}

	/**
	 * Takes the id from the route and fetches the purchase list.
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
	 * Creates a response, that indicates an invalid API Key.
	 */
	protected function invalidAPIKeyResponse()
	{
		return $this->unauthorizedResponse('No user was found for the provided API key. Use the X-API-KEY header to provide an API Key.');
	}
}