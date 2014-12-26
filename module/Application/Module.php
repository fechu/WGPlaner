<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\ModuleManager\Feature\ServiceProviderInterface;
use Zend\ModuleManager\Feature\ViewHelperProviderInterface;
use Application\Acl\Acl;
use Zend\View\Model\ViewModel;

class Module implements AutoloaderProviderInterface,
                        ServiceProviderInterface,
                        ViewHelperProviderInterface
{
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);

        $serviceManager = $e->getApplication()->getServiceManager();
        /* @var $entityManager \Doctrine\ORM\EntityManager */
        $entityManager = $serviceManager->get('doctrine.entitymanager.orm_default');

        // Enable the soft delete filter by default. If you want the delete objects to, you have to manually disable this filter.
        $entityManager->getFilters()->enable('soft_delete');


        // Setup acl check if a user is logged in.
        // If no user is logged in, SMUser handles it.
        $identityService = $serviceManager->get('smuser.identity');
        if ($identityService->getIdentity() != NULL) {
        	$e->getApplication()->getEventManager()->attach('route', array($this, 'checkAcl'));
        }

        // Setup a listener which listenes for the dispatch.error event
        $eventManager->attach('dispatch.error', array($this, 'processDispatchError'));
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

    public function getServiceConfig()
    {
        return array(
            'factories' => array(

                /**
                 * The repository used by SMUser. In this application it is
                 * the UserRepository which implements the
                 * SMUser\Entity\Repository\UserRepositoryInterface
                 */
                'smuser.user_repository' => function ($sm) {
                    /* @var $entityManager \Doctrine\ORM\EntityManager */
                    $entityManager = $sm->get('doctrine.entitymanager.orm_default');
                    $repo = $entityManager->getRepository('Application\Entity\User');

                    return $repo;
                },

                /**
                 * Custom navigation factory which loads the dynamic menu
                 */
                'Navigation' => 'Application\Navigation\NavigationFactory',

            ),
            'invokables' => array(
            	'acl'	=> 'Application\Acl\Acl',
            ),
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'usertable'	=> 'Application\View\Helper\UserTable',
                'purchasetable' => 'Application\View\Helper\PurchaseTable',
            	'graphs' => 'Application\View\Helper\Graphs',
            	'acl'	=> 'Application\View\Helper\Acl',
            )
        );
    }

    public function checkAcl(MvcEvent $e)
    {
    	/* @var $request \Zend\Http\Request */
    	$request = $e->getRequest();

		// Check if the request contains an accountid. If yes, let's check if the user is allowed
		// to access that account
		$accountid = $e->getRouteMatch()->getParam('accountid', -1);
		if ($accountid != -1) {

    		$user = $e->getApplication()->getServiceManager()->get('smuser.identity')->getIdentity();
    		$sm = $e->getApplication()->getServiceManager();
    		$entityManager = $sm->get('doctrine.entitymanager.orm_default');
    		$account = $entityManager->find('Application\Entity\Account', $accountid);

    		if ($account) {
    			/* @var $acl \Application\Acl\Acl */
    			$acl = $sm->get('acl');
    			if (!$acl->isAllowed($user, $account, 'view')) {
    				// The user is not allowed to view this account.
    				$logger = $sm->get('Zend\Log');
    				$arguments = array(
    					'user' => $user,
    					'account' => $account
    				);
    				$logger->info(
    						'User tried to access account which he is not allowed to',
    						$arguments
    				);

    				// Let's trigger an error!
					$e->setError(Acl::ACL_ACCESS_DENIED);
					$e->setParam('route', $e->getRouteMatch()->getMatchedRouteName());
					$e->getApplication()->getEventManager()->trigger('dispatch.error', $e);
    			}
    		}
		}

		// We do nothing... All checks passed.
    }

    /**
     * Processes dispatch.error events.
     * This method will only handle errors which have ACL_ACCESS_DENIED as error set.
     */
    public function processDispatchError(MvcEvent $event)
    {
    	$error = $event->getError();

    	// Check if we got an access denied error.
    	if ($error != Acl::ACL_ACCESS_DENIED) {
    		return;
    	}

    	$baseModel = new ViewModel();
    	$baseModel->setTemplate('layout/layout');

    	$model = new ViewModel();
    	$model->setTemplate('error/403');

    	$baseModel->addChild($model);
    	$baseModel->setTerminal(true);

    	$event->setViewModel($baseModel);

    	$response = $event->getResponse();
    	$response->setStatusCode(403);

    	$event->setResponse($response);
    	$event->setResult($baseModel);

		return false;
    }
}
