<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace API;
use Zend\ModuleManager\Feature\AutoloaderProviderInterface;

class Module implements AutoloaderProviderInterface
{
	
    public function getConfig() 
    {
	return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() 
    {
	return array(
	    'Zend\Loader\StandardAutoloader' => array(
		'namespaces' => array(
		    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
		),
	    ),
	);
    }

    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
		'receiptImage' => 'API\View\Helper\ReceiptImage'
	    )
	);

    }


}
