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
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use SMCommon\View\Helper\FormatDate;

class Module implements AutoloaderProviderInterface, 
						ControllerProviderInterface,
						ServiceProviderInterface,
						ViewHelperProviderInterface
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
	
	public function getViewHelperConfig()
	{
		return array(
			'invokables' => array(
				'pageHeader' 	=> 'SMCommon\View\Helper\PageHeader',
				'table'			=> 'SMCommon\View\Helper\Table',
				'objectUrl'		=> 'SMCommon\View\Helper\ObjectUrl',
				'prettyprint'	=> 'SMCommon\View\Helper\PrettyPrint',
			),
			'factories' => array(
				'formatDate' => function ($sm) {
					$appConfig = $sm->getServiceLocator()->get('config');
					return new FormatDate(isset($appConfig['formatdate']) ? $appConfig['formatdate'] : array());
				},
			)
		);
	}
	
}
