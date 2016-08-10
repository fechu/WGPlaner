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

				// Statistics
				'statistic' => array(
					'type'    => 'Segment',
					'options' => array(
						'route'			=> '/statistics',
					),
					'child_routes' => array(
						'graph' => array(
							'type'    => 'Segment',
							'options' => array(
								'route'			=> '/graph/:action',
								'constraints' 	=> array(
									'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
								),
								'defaults' => array(
									'__NAMESPACE__' => 'API\Controller\Statistic',
									'controller'	=> 'Graph',
								),
							),
						),
					),
				),

				// account data.
				'accounts' => array(
					'type'    => 'Segment',
					'options' => array(
						'route'			=> '/accounts[/:accountid]',
						'constraints' 	=> array(
							'accountid'			=> '[0-9]+'
						),
						'defaults' => array(
							'__NAMESPACE__' => 'API\Controller',
							'controller'	=> 'Account',
							'action'		=> null,
						),
					),
					'may_terminate' => true,
					'child_routes' => array(
						// purchases
						'purchase' => array(
							'type'    => 'Segment',
							'may_terminate' => true,
							'options' => array(
								'route'			=> '/purchases',
								'defaults' => array(
									'__NAMESPACE__' => 'API\Controller',
									'controller'	=> 'Purchase',
									'action'		=> null,
								),
							),
							'child_routes' => array(
								'actions' => array(
									'type' => 'Segment',
									'options' => array(
										'route' => '/:purchaseid/:action',
										'constraints' => array(
											'purchaseid' => '[0-9]+'
										),
										'defaults' => array(
											'__NAMESPACE__' => 'API\Controller',
											'controller' => 'Purchase',
										)
									)
								)
							)
						),
					),
				),
				// purchases without accounts.
				'purchase' => array(
					'type'    => 'Segment',
					'may_terminate' => true,
					'options' => array(
						'route'			=> '/purchases',
						'defaults' => array(
							'__NAMESPACE__' => 'API\Controller',
							'controller'	=> 'Purchase',
							'action'		=> null,
						),
					),
					'child_routes' => array(
						'actions' => array(
							'type' => 'Segment',
							'options' => array(
								'route' => '/:purchaseid/:action',
								'constraints' => array(
									'purchaseid' => '[0-9]+'
								),
								'defaults' => array(
									'__NAMESPACE__' => 'API\Controller',
									'controller' => 'Purchase',
								)
							)
						)
					)
				),
			),
		),
	)
);
