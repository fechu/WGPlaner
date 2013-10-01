<?php
/**
 * @file UserController.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Controller;

use SMCommon\Controller\AbstractActionController;

class UserController extends AbstractActionController
{
	/**
	 * Show a list of all users to modify them.
	 */
	public function indexAction()
	{
		$configuration = $this->getConfig();
		$smuserConfiguration = isset($configuration['smuser']) ? $configuration['smuser'] : array();
		$userRepoServiceKey = $smuserConfiguration['user_repository_service'];
		
		/* @var $repo \SMUser\Entity\Repository\UserRepositoryInterface */
		$repo = $this->getServiceLocator()->get($userRepoServiceKey);
		
		return array(
			'users' => $repo->findAll(),
		);
	}
}