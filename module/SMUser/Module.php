<?php

namespace SMUser;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;

class Module implements AutoloaderProviderInterface, ControllerProviderInterface, ViewHelperProviderInterface
{
	
	public function onBootstrap(MvcEvent $e)
    {
        // Register for the route event
    	$eventManager = $e->getApplication()->getEventManager();
        $eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    }
	
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
				'SMUser\Controller\User' 				=> 'SMUser\Controller\UserController',	
				'SMUser\Controller\Authentification' 	=> 'SMUser\Controller\AuthentificationController',	
			)
		);
	}
	
	public function getViewHelperConfig()
	{
		return array(
			'factories' => array(
				'identity' => 'SMUser\View\Helper\Service\IdentityFactory',
			),
		);
	}
	
	public function onRoute(MvcEvent $e)
	{
		$config = $e->getApplication()->getServiceManager()->get('config');
		$smuserConfig = isset($config['smuser']) ? $config['smuser'] : NULL;
		if ($smuserConfig) {
			$shouldRedirect = isset($smuserConfig['redirect_without_authentication']) ? $smuserConfig['redirect_without_authentication'] : false;
			
			if ($shouldRedirect) {
				$routeName = $e->getRouteMatch()->getMatchedRouteName();
				$action = $e->getRouteMatch()->getParam('action');
				if ($routeName !== 'auth' || $action !== 'login') {
		            $url = $e->getRouter()->assemble(array(), array('name' => 'auth'));
		            $response=$e->getResponse();
		            $response->getHeaders()->addHeaderLine('Location', $url);
		            $response->setStatusCode(302);
		            $response->sendHeaders();
		            //  When an MvcEvent Listener returns a Response object,
		            // It automatically short-circuit the Application running
		            return $response;
				}
			}
		}
	}
	
}
