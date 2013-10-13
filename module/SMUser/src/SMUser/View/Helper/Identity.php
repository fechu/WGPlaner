<?php


namespace SMUser\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Exception;
use Zend\View\Helper\AbstractHelper;
use SMUser\Entity\Repository\UserRepositoryInterface;
use SMUser\Authentication\IdentityService;

/**
 * View helper plugin to fetch the authenticated identity.
 */
class Identity extends AbstractHelper
{
    /**
     * The user once it is loaded.
     * 
     * @var IdentityService
     */
    protected $identityService;
    
    /**
     * @param IdentityService $identityService
     */
    public function __construct($identityService)
    {
    	$this->identityService = $identityService;
    }

    /**
     * Retrieve the current identity, if any.
     *
     * If none available, returns null.
     *
     * @throws Exception\RuntimeException
     * @return mixed|null
     */
    public function __invoke()
    {
    	return $this->identityService->getIdentity();
    }

}
