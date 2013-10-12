<?php


namespace SMUser\View\Helper;

use Zend\Authentication\AuthenticationService;
use Zend\View\Exception;
use Zend\View\Helper\AbstractHelper;
use SMUser\Entity\Repository\UserRepositoryInterface;

/**
 * View helper plugin to fetch the authenticated identity.
 */
class Identity extends AbstractHelper
{
    /**
     * AuthenticationService instance
     *
     * @var AuthenticationService
     */
    protected $authenticationService;
    
    /**
     * User repo used to load the user.
     */
    protected $userRepository;
    
    /**
     * The user once it is loaded.
     */
    protected $identity;

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
        if (!$this->authenticationService instanceof AuthenticationService) {
            throw new Exception\RuntimeException('No AuthenticationService instance provided');
        }

        if (!$this->authenticationService->hasIdentity()) {
            return null;
        }
        
        return $this->getIdentity();
    }

    /**
     * Set AuthenticationService instance
     *
     * @param AuthenticationService $authenticationService
     * @return Identity
     */
    public function setAuthenticationService(AuthenticationService $authenticationService)
    {
        $this->authenticationService = $authenticationService;
        return $this;
    }

    /**
     * Get AuthenticationService instance
     *
     * @return AuthenticationService
     */
    public function getAuthenticationService()
    {
        return $this->authenticationService;
    }
    
    public function setUserRepository($repo)
    {
    	$this->userRepository = $repo;
    }
    
    public function getIdentity()
    {
    	if (!$this->identity) {
    		$identity = $this->authenticationService->getIdentity();
    		
    		if (!$this->userRepository instanceof UserRepositoryInterface) {
	            throw new Exception\RuntimeException('No UserRepository instance provided');
    		}
    		
    		// Load the user 
    		$this->identity = $this->userRepository->findOneByUsername($identity);
    	}

    	return $this->identity;
    }
}
