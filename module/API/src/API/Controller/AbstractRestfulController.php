<?php
/**
 * @file AbstractRestfulController.php
 * @date Nov 28, 2013 
 * @author Sandro Meier
 */
 
namespace API\Controller;

use SMCommon\Controller\AbstractRestfulController as SMCommonRestfulController;

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
	 * @return PurchaseList
	 */
	protected function getPurchaseList()
	{
		$id = $this->getId('purchaselist');
	
		/* @var $repo \Application\Entity\Repository\PurchaseListRepository */
		$repo = $this->em->getRepository('Application\Entity\PurchaseList');
	
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