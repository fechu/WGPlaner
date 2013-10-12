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
					'action' => array(
						'type'    => 'Segment',
						'options' => array(
							'route'		=> '[/:id][/:action][/]',
							'constraints' => array(
								'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
								'id'		=> '[0-9]+',	
							),
							'defaults' => array(
								'controller'	=> 'User',
							),
						),
					),
				),
			),
			'auth' => array(
				'type'    => 'Segment',
				'options' => array(
					'route'    => '/auth[/:action][/]',
					'defaults' => array(
						'__NAMESPACE__' => 'SMUser\Controller',
						'controller'    => 'Authentification',
						'action'        => 'login',
					),
					'constraints'	=> array(
						'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
					),
				),					
			)
		),
	),
	
	// View
	'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
	),
	
	// Navigation
	'navigation' => array(
         'default' => array(
             array(
                 'label' => 'Benutzer',
                 'route' => 'user',
                 'pages' => array(
                     array(
                         'label' 	=> 'Bearbeiten',
                         'route' 	=> 'user/action',
                     	 'action'	=> 'edit',
                     	 'visible' 	=> false,
                     ),
                 	array(
                 		'label' 	=> 'Passwort ändern',
                 		'route' 	=> 'user/action',
                 		'action'	=> 'change-password',
                 		'visible' 	=> false,
                 	),
                 	array(
                 		'label'		=> 'Erstellen',
                 		'route'		=> 'user/action',
                 		'action'	=> 'create',
                 		'visible' 	=> false
                 	)
                 ),
             ),
         ),
     ),
	
	
     'service_manager' => array(
     	'invokables' => array(
     		'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
     	),
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
     	'aliases' => array(
     		'smuser.auth_service' => 'Zend\Authentication\AuthenticationService',
     	),
     ),
);