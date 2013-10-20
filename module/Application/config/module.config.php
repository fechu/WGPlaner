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
                    	'__NAMESPACE__' => 'Application\Controller\PurchaseList',
                        'controller' 	=> 'PurchaseList',
                        'action'     	=> 'index',
                    ),
                ),
            ),
            'purchase-list' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/purchase-list',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller\PurchaseList',
                        'controller'    => 'PurchaseList',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'action' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'		=> '[/:action]',
							'constraints' => array(
								'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
							),
						),
					),
					'list-action' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'		=> '[/:purchaselistid[/:action]]',
							'constraints' => array(
								'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
								'purchaselistid'	=> '[0-9]+'
							),
							'defaults' => array(
								'action'		=> 'index'
							),
						),
					),
                	'user' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'		=> '/:purchaselistid/users[/:userid][/:action]',
                			'constraints' => array(
                				'purchaselistid'	=> '[0-9]+',
                				'userid'			=> '[0-9]+',
                				'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
                			),
                			'defaults' => array(
                				'controller'	=> 'User',
                				'action'		=> 'index'
                			),
                		),
                	),
                	'purchase' => array(
                		'type'    => 'Segment',
                		'options' => array(
                			'route'		=> '/:purchaselistid/purchases[/:purchaseid][/:action]',
                			'constraints' => array(
                				'purchaselistid'	=> '[0-9]+',
                				'purchaseid'		=> '[0-9]+',
                				'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
                			),
                			'defaults' => array(
                				'controller'	=> 'Purchase',
                				'action'		=> 'index'
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
            'Application\Controller\Index' 						=> 'Application\Controller\IndexController',
            'Application\Controller\PurchaseList\PurchaseList' 	=> 'Application\Controller\PurchaseList\PurchaseListController',
            'Application\Controller\PurchaseList\User'			=> 'Application\Controller\PurchaseList\UserController',
            'Application\Controller\PurchaseList\Purchase'		=> 'Application\Controller\PurchaseList\PurchaseController',
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
    			'label' => 'Einkaufslisten',
    			'route' => 'purchase-list',
    			'order' => -1,
    			'pages' => array(
    				array(
    					'label'		=> 'Aktuelle Listen',
    					'route'		=> 'purchase-list/action',
    					'action'	=> 'index',
    					'visible'	=> false,
    				),
    				array(
    					'label'		=> 'Nicht aktive Listen',
    					'route'		=> 'purchase-list/action',
    					'action'	=> 'not-active',
    					'visible'	=> false,
    				),
    				array(
    					'label'		=> 'Einkauf hinzufügen',
    					'route'		=> 'purchase-list/action',
    					'action'	=> 'add-purchase',
    					'visible'	=> false,
    				),
    			),
    		),
    	),
    ),
    
);
