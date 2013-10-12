<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SMUser\View\Helper\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SMUser\View\Helper\Identity;

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
        $helper = new Identity();
        if ($services->has('Zend\Authentication\AuthenticationService')) {
            $helper->setAuthenticationService($services->get('Zend\Authentication\AuthenticationService'));
        }
        
        // Get the configuration and find the user repository service key.
        $configuration = $services->get('config');
        $smuserConfiguration = isset($configuration['smuser']) ? $configuration['smuser'] : array();
        $userRepoServiceKey = $smuserConfiguration['user_repository_service'];
        
        /* @var $repo \SMUser\Entity\Repository\UserRepositoryInterface */
        $repo = $services->get($userRepoServiceKey);
        
        $helper->setUserRepository($repo);
        
        return $helper;
    }
}
