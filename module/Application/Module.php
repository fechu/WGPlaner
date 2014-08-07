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
        );
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'usertable'	=> 'Application\View\Helper\UserTable',
                'purchasetable' => 'Application\View\Helper\PurchaseTable',
            )
        );
    }
}
