<?php

namespace SMCommon;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\Log\LoggerAwareInterface;
use SMCommon\Doctrine\EntityManagerAwareInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\Log\Logger;
use Zend\Log\Writer\Stream;

class Module implements AutoloaderProviderInterface, 
						ControllerProviderInterface,
						ServiceProviderInterface
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
			'initializers' => array(
				function ($instance, $sm) {
					
					// Inject logger?
					if ($instance instanceof LoggerAwareInterface) {
						$instance->setLogger($sm->getServiceLocator()->get('Zend/Log'));
					}
					
					// Inject EntityManager?
					if ($instance instanceof EntityManagerAwareInterface) {
						$instance->setEntityManager($sm->getServiceLocator()->get('doctrine.entitymanager.orm_default'));
					}
				}
			),
		);
	}
	
	public function getServiceConfig()
	{
		return array(
			'factories' => array(
				'Zend\Log' => function ($sm) {
					$log = new Logger();
					$writer = new Stream('./data/application.log');
					$log->addWriter($writer);
			
					return $log;
				},
			),
		);
	}
	
}
