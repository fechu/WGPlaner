<?php
/**
 * @file AbstractRestfulController.php
 * @date Nov 28, 2013
 * @author Sandro Meier
 */

namespace API\Controller;

use SMCommon\Controller\AbstractRestfulController as SMCommonRestfulController;
use Application\Entity\Account;
use Application\Entity\User;
use Zend\Mvc\MvcEvent;
use Zend\View\Model\JsonModel;

class AbstractRestfulController extends SMCommonRestfulController
{

    public function onDispatch(MvcEvent $e)
	{
		// Check if we got an identity. Otherwise we don't go on with processing.
		if ($this->identity() == NULL) {
			/* @var $response \Zend\Http\Response */
			$response = $e->getResponse();
			$response->setStatuscode(403);
			$json = $this->generateErrorViewModel(
					'Unauthorized access.',
					'Provide an API key with the X-API-KEY header or login to use the API.'
  			);
			$response->setContent($json->serialize());
			$response->getHeaders()->addHeaderLine('Content-Type', 'application/json');
			return $response;
		}

		// Just go on. We know that we have an authenticated user.
		return parent::onDispatch($e);
	}

	/**
	 * Get the identity provided.
	 * First it is checked if a user is logged in. If not,
	 * authentification is done via the API key in the X-API-KEY header.
	 * @return A user object or NULL if authentication failed.
	 */
	protected function identity()
	{
		// Check if a user is logged in.
		$identityPlugin = $this->plugin('identity');
		$user = $identityPlugin();

		// Check if we already got an identity. If not, we look if we got an API key.
		if (!($user instanceof User)) {

			/* @var $request \Zend\Http\Request */
			$request = $this->getRequest();

			if ($request->getHeaders()->has('X-API-KEY')) {
				$apiKey = $request->getHeader('X-API-KEY')->getFieldValue();
				if ($apiKey) {
					// Check if this belong to a user.
					/* @var $repo \Application\Entity\Repository\UserRepository */
					$repo = $this->em->getRepository('Application\Entity\User');

					$user = $repo->findOneByApiKey($apiKey);
				}
			}
		}

		return $user;

	}

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
	 * Takes the id from the route and fetches the purchase.
	 * @return Application\Entity\Purchase The purchase or nil if it doesn't exist.
	 */
	protected function getPurchase()
	{
		$id = $this->getId('purchase');

		$repo = $this->em->getRepository('Application\Entity\Purchase');
		
		return $repo->find($id);
	}
}