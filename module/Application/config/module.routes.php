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
                '__NAMESPACE__' => 'Application\Controller',
                'controller' 	=> 'Index',
                'action'     	=> 'index',
            ),
        ),
    ),
    'administration' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/administration',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' 	=> 'Administration',
                'action'     	=> 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'action' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'		=> '[/:action]',
                    'constraints' 	=> array(
                        'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                ),
            ),
        ),
    ),
    'settings' => array(
        'type' => 'Segment',
        'options' => array(
            'route'    => '/settings',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller',
                'controller' 	=> 'Settings',
                'action'     	=> 'api',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'action' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'		=> '[/:action]',
                    'constraints' 	=> array(
                        'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                ),
            ),
        ),
    ),
    'accounts' => array(
        'type'    => 'Literal',
        'options' => array(
            'route'    => '/accounts',
            'defaults' => array(
                '__NAMESPACE__' => 'Application\Controller\Account',
                'controller'    => 'Account',
                'action'        => 'index',
            ),
        ),
        'may_terminate' => true,
        'child_routes' => array(
            'action' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'	    => '[/:action]',
                    'constraints'   => array(
                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                ),
            ),
            'list-action' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'	    => '[/:accountid[/:action]]',
                    'constraints'   => array(
                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'accountid' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'action'    => 'index'
                    ),
                ),
            ),
            'users' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'	    => '/:accountid/users[/:userid][/:action]',
                    'constraints'   => array(
                        'accountid' => '[0-9]+',
                        'userid'    => '[0-9]+',
                        'action'    => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller'	=> 'User',
                        'action'	=> 'index'
                    ),
                ),
            ),
            'purchases' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'	    => '/:accountid/purchases[/:purchaseid][/:action]',
                    'constraints'   => array(
                        'accountid'	=> '[0-9]+',
                        'purchaseid'	=> '[0-9]+',
                        'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller'	=> 'Purchase',
                        'action'	=> 'index'
                    ),
                ),
            ),
            'bills' => array(
                'type'    => 'Segment',
                'options' => array(
                    'route'	    => '/:accountid/bills[/:billid][/:action]',
                    'constraints'   => array(
                        'accountid'	=> '[0-9]+',
                        'billid'	=> '[0-9]+',
                        'action'    	=> '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller'	=> 'Bill',
                        'action'	=> 'index'
                    ),
                ),
            ),
        ),
    ),
);
