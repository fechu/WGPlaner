<?php
/**
 * @file Identity.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Identity extends AbstractPlugin
{
	/**
	 * The user once it is loaded.
	 *
	 * @var IdentityService
	 */
	protected $identityService;
	
	/**
	 * @param IdentityService $identityService
	 */
	public function __construct($identityService)
	{
		$this->identityService = $identityService;
	}
	
	/**
	 * Retrieve the current identity, if any.
	 *
	 * If none available, returns null.
	 *
	 * @throws Exception\RuntimeException
	 * @return mixed|null
	 */
	public function __invoke()
	{
		return $this->identityService->getIdentity();
	}
}