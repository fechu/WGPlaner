<?php
/**
 * @file module.config.php
 * @date Nov 25, 2013
 * @author Sandro Meier
 */

return array(

	// Additional configuration for smuser.
	'smuser' => array(
		'route_whitelist' => array(
			'api',
		),
	),

	// Routes in routes.config.php
	'router' => include 'routes.config.php',

	'controllers' => array(
		'invokables' => array(
			'API\Controller\Purchase' 				=> 'API\Controller\PurchaseController',
			'API\Controller\Account'				=> 'API\Controller\AccountController',
			'API\Controller\Data'					=> 'API\Controller\DataController',
			'API\Controller\Statistic\Graph'		=> 'API\Controller\Statistic\GraphController',
		),
	),

	'view_manager' => array(
		'strategies' => array(
			'ViewJsonStrategy',
		),
	),
);
