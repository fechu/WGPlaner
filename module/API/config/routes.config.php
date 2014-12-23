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

					// Statistics
					'statistic' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'			=> '/statistics/:action',
							'constraints' 	=> array(
								'action'    		=> '[a-zA-Z][a-zA-Z0-9_-]*',
							),
							'defaults' => array(
								'__NAMESPACE__' => 'API\Controller',
								'controller'	=> 'Statistic',
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
							'purchase' => array(
								'type'    => 'Segment',
								'options' => array(
									'route'			=> '/purchases',
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
