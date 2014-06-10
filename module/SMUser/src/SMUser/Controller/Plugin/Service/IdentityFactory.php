<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SMUser\Controller\Plugin\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SMUser\Controller\Plugin\Identity;

class IdentityFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Zend\View\Helper\Identity
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $services = $serviceLocator->getServiceLocator();
        $identityService = $services->get('smuser.identity');
        $helper = new Identity($identityService);
        
        return $helper;
    }
}
