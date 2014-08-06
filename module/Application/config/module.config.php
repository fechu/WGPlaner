<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'router' => array(
        'routes' => include 'module.routes.php',
    ),
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index' 		=> 'Application\Controller\IndexController',
            'Application\Controller\Account\Account' 	=> 'Application\Controller\Account\AccountController',
            'Application\Controller\Account\User'	=> 'Application\Controller\Account\UserController',
            'Application\Controller\Account\Purchase'	=> 'Application\Controller\Account\PurchaseController',
            'Application\Controller\Account\Bill'	=> 'Application\Controller\Account\BillController',
            'Application\Controller\Settings'		=> 'Application\Controller\SettingsController',
        ),
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
    ),

    // Doctrine configuration
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(
                    __DIR__ . '/../src/Application/Entity',
                ),
            ),

            // default metadata driver, aggregates all other drivers into a single one.
            // Override `orm_default` only if you know what you're doing
            'orm_default' => array(
                'drivers' => array(
                    // register `my_annotation_driver` for any entity under namespace `My\Namespace`
                    'Application\Entity' => 'application_entities'
                )
            )
        ),
    ),

    // Navigation
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Konten',
                'route' => 'accounts',
                'order' => -1,
                'pages' => array(
                    array(
                        'label'		=> 'Alle Konten',
                        'route'		=> 'accounts/action',
                    ),
                    array(
                        'route'         => 'accounts/action',
                        'divider'       => true,
                    ),
                    array(
                        'label'		=> 'Archiv',
                        'route'		=> 'accounts/action',
                        'action'	=> 'archive',
                    ),
                ),
            ),
        ),
    ),
);
