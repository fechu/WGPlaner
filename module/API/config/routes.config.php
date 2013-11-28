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
					'purchase' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'			=> '/purchase[/:id][/:action]',
							'constraints' 	=> array(
								'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'			=> '[0-9]+'
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
		)
	);
 