<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/zf2 for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace SMUser\Authentication\Service;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;
use SMUser\View\Helper\Identity;
use SMUser\Authentication\IdentityService;

class IdentityServiceFactory implements FactoryInterface
{
    /**
     * {@inheritDoc}
     *
     * @return \Zend\View\Helper\Identity
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $helper = new IdentityService();
        if ($serviceLocator->has('Zend\Authentication\AuthenticationService')) {
            $helper->setAuthenticationService($serviceLocator->get('Zend\Authentication\AuthenticationService'));
        }
        
        // Get the configuration and find the user repository service key.
        $configuration = $serviceLocator->get('config');
        $smuserConfiguration = isset($configuration['smuser']) ? $configuration['smuser'] : array();
        $userRepoServiceKey = $smuserConfiguration['user_repository_service'];
        
        /* @var $repo \SMUser\Entity\Repository\UserRepositoryInterface */
        $repo = $serviceLocator->get($userRepoServiceKey);
        
        $helper->setUserRepository($repo);
        
        return $helper;
    }
}
