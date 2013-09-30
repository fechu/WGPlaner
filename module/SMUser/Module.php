<?php

namespace SMUser;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;

class Module implements AutoloaderProviderInterface, ControllerProviderInterface
{
	public function getConfig()
	{
		return include __DIR__ . '/config/module.config.php';
	}
	
	public function getAutoloaderConfig()
	{
		return array(
			'Zend\Loader\StandardAutoloader' => array(
				'namespaces' => array(
					__NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
				),
			),
		);
	}
	
	public function getControllerConfig()
	{
		return array(
			'invokables' => array(
				'SMUser\Controller\User' => 'SMUser\Controller\UserController',	
			)
		);
	}
	
}
