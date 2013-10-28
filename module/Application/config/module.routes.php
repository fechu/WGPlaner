<?php
/**
 * @file module.routes.php
 * @date Oct 27, 2013 
 * @author Sandro Meier
 * 
 * This files contains the routes for the application module. 
 * It is included in the module.config.php file.
 */

return array(
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
					'route'			=> '[/:action]',
					'constraints' 	=> array(
						'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
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
	'count-list' => array(
		'type'    => 'Literal',
		'options' => array(
			'route'    => '/count-list',
			'defaults' => array(
				'__NAMESPACE__' => 'Application\Controller\CountList',
				'controller'    => 'CountList',
				'action'        => 'index',
			),
		),
		'may_terminate' => true,
		'child_routes' => array(
			'action' => array(
				'type' => 'Segment',
				'options' => array(
					'route' => "/:action",
					'constraints' => array(
						'action' => '[a-zA-Z][a-zA-Z0-9_-]*'
					),
				),
			)
		),
	),
);
 