<?php
/**
 * @file SettingsController.php
 * @date Nov 18, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller;


use SMCommon\Controller\AbstractActionController;

class SettingsController extends AbstractActionController
{
	/**
	 * API Settings.
	 */
	public function apiAction()
	{
		
	}
	
	/**
	 * Generate a new API for the logged in user.
	 */
	public function generateApiKeyAction()
	{
		/* @var $user \Application\Entity\User */
		$user = $this->identity();
		$user->generateAPIKey();
		$this->em->flush($this->identity());
		$this->logger->info("Generated new API Key", array('user' => $user) );
		
		// Redirect to api settings
		return $this->redirect()->toRoute('settings/action', array('action' => 'api'));
	}
}