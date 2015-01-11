<?php
return array(
	'smuser' => array(
		/**
		 * This key has to be overwritten by the application that uses SMUser.
		 *
		 * The repository needs to be a service in the service locator. You can specify
		 * the key that is used to get the service.
		 */
		'user_repository_service' => 'smuser.user_repository',

		/**
		 * Should the SMUser module redirect every request to the login page that is not authenticated?
		 */
		'redirect_without_authentication' => true,

		/**
		 * Route Whitelist
		 * All routes in this list will be ignored if no one is authenticated.
		 */
		'route_whitelist' => array(
			'auth',
			'login'
		),

		/**
		 * The route where to redirect after login.
		 */
		'redirect_after_login' => 'home',

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
			'profile'	=> array(
				'type'    => 'Literal',
				'options' => array(
					'route'		=> '/user/profile',
					'defaults' => array(
						'__NAMESPACE__' => 'SMUser\Controller',
						'controller'	=> 'User',
						'action'		=> 'view',
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
                 'label' => 'Users',
                 'route' => 'user',
             ),
         ),
     ),


     'service_manager' => array(
     	'invokables' => array(
     		'Zend\Authentication\AuthenticationService' => 'Zend\Authentication\AuthenticationService',
     	),
        'factories' => array(
        	'smuser.identity' 	=> 'SMUser\Authentication\Service\IdentityServiceFactory',
        ),
     	'aliases' => array(
     		'smuser.auth_service' => 'Zend\Authentication\AuthenticationService',
     	),
     ),
);
