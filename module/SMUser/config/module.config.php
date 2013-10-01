<?php 
return array(
	'smuser' => array(
		/**
		 * This key has to be overwritten by the application that uses SMUser. 
		 * 
		 * The repository needs to be a service in the service locator. You can specify
		 * the key that is used to get the service. 
		 */
		'user_repository_service' => 'smuser.user_repository'
	),
	
	// Routes
	'router' => array(
		'routes' => array(
			'user' => array(
				'type'    => 'Literal',
				'options' => array(
					'route'    => '/user',
					'defaults' => array(
						'__NAMESPACE__' => 'SMUser\Controller',
						'controller'    => 'User',
						'action'        => 'index',
					),
				),
				'may_terminate' => true,
				'child_routes' => array(
					'default' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'    => '[/:action[/:id]]',
							'constraints' => array(
								'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'		=> '[0-9]*',	
							),
							'defaults' => array(
								'controller'	=> 'User',
							),
						),
					),
				),
			),
		),
	),
	
	// View
	'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
	)
);