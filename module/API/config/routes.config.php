<?php
/**
 * @file routes.config.php
 * @date Nov 28, 2013 
 * @author Sandro Meier
 */

return array(
		'routes' => array(
			'api' => array(
				'type' => 'Segment',
				'options' => array(
					'route'    => '/api',
					'defaults' => array(
						/// @todo Add api index controller (with documentation?)
						'__NAMESPACE__' => 'Application\Controller',
						'controller' 	=> 'Index',
						'action'     	=> 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					
					// Overall data
					'data' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'			=> '/data/:action',
							'constraints' 	=> array(
								'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
								'__NAMESPACE__' => 'API\Controller', 
								'controller'	=> 'Data',
							),
						),
					),
					
					// Purchase list data.
					'purchase-list' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'			=> '/purchase-list[/:purchaselistid]',
							'constraints' 	=> array(
								'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
								'purchaselistid'	=> '[0-9]+'
							),
							'defaults' => array(
								'__NAMESPACE__' => 'API\Controller', 
								'controller'	=> 'PurchaseList',
								'action'		=> null,
							),
						),
						'may_terminate' => true,
						'child_routes' => array(
							'purchase' => array(
								'type'    => 'Segment',
								'options' => array(
									'route'			=> '/purchase[/:purchaseid][/:action]',
									'constraints' 	=> array(
										'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
										'purchaseid'	=> '[0-9]+'
									),
									'defaults' => array(
										'__NAMESPACE__' => 'API\Controller',
										'controller'	=> 'Purchase',
										'action'		=> null,
									),
								),
							),
						),
					),
				),
			),
		)
	);
 