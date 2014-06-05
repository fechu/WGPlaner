<?php

namespace SMUser;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ControllerProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\Feature\ControllerPluginProviderInterface;

class Module implements AutoloaderProviderInterface, 
						ControllerProviderInterface, 
						ViewHelperProviderInterface,
						ControllerPluginProviderInterface
{
	
	public function onBootstrap(MvcEvent $e)
    {
    	$authService = $e->getApplication()->getServiceManager()->get('smuser.auth_service');
    	if(!$authService->getIdentity()) {
        	// Register for the route event
    		$eventManager = $e->getApplication()->getEventManager();
        	$eventManager->attach(MvcEvent::EVENT_ROUTE, array($this, 'onRoute'));
    	}
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
	
	public function getControllerPluginConfig()
	{
		return array(
			'factories' => array(
				'identity' => 'SMUser\Controller\Plugin\Service\IdentityFactory',
			)
		);
	}
	
	public function onRoute(MvcEvent $e)
	{
		$config = $e->getApplication()->getServiceManager()->get('config');
		$smuserConfig = isset($config['smuser']) ? $config['smuser'] : NULL;
		if ($smuserConfig) {
			$shouldRedirect = isset($smuserConfig['redirect_without_authentication']) ? $smuserConfig['redirect_without_authentication'] : false;
			
			if ($shouldRedirect) {
				$whitelist = $smuserConfig['route_whitelist'];
				$routeName = $e->getRouteMatch()->getMatchedRouteName();
				$action = $e->getRouteMatch()->getParam('action');
				
				// Check if we got that route or a childroute
				$redirect = true;
				foreach ($whitelist as $whitelistedRoute) {
					if (strpos($routeName, $whitelistedRoute) === 0) {
						$redirect = false;
						break;
					}
				}
				
				// Need to redirect?
				if ($redirect) { 
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
