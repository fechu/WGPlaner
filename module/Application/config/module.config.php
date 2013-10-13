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
        'routes' => array(
            'home' => array(
                'type' => 'Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),
            'purchase-list' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/purchase-list',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'PurchaseList',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'action' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'		=> '[/:id][/:action][/]',
							'constraints' => array(
								'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'		=> '[0-9]+',	
							),
							'defaults' => array(
								'controller'	=> 'PurchaseList',
							),
						),
					),
                ),
            ),
        ),
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
            'Application\Controller\Index' 			=> 'Application\Controller\IndexController',
            'Application\Controller\PurchaseList' 	=> 'Application\Controller\PurchaseListController'
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
    )
);
