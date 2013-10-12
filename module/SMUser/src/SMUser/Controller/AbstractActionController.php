<?php
/**
 * @file AbstractActionController.php
 * @date Oct 1, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller;

use SMCommon\Controller\AbstractActionController as SMCommonAbstractActioncontroller;
use SMUser\Entity\Repository\UserRepositoryInterface;

abstract class AbstractActionController extends SMCommonAbstractActioncontroller
{
	/**
	 * @return \SMUser\Entity\Repository\UserRepositoryInterface The user repository that has to be defined as a service.
	 */
	protected function getUserRepository()
	{
		// Get the configuration and find the user repository service key.
		$configuration = $this->getConfig();
		$smuserConfiguration = isset($configuration['smuser']) ? $configuration['smuser'] : array();
		$userRepoServiceKey = $smuserConfiguration['user_repository_service'];
		
		/* @var $repo \SMUser\Entity\Repository\UserRepositoryInterface */
		$repo = $this->getServiceLocator()->get($userRepoServiceKey);
		
		if (!$repo) {
			throw new \Exception('Could not find service named ' . $userRepoServiceKey . '. You need to define a service that implements the methods from SMUser\Entity\Repository\UserRepositoryInterface!');
		}
		
		return $repo;
	}
	
	public function getSMUserConfig()
	{
		$config = $this->getConfig();
		$smuserConfig = isset($config['smuser']) ? $config['smuser'] : array();
		return $smuserConfig;
	}
}