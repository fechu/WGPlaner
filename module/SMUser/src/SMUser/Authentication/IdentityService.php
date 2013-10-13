<?php
/**
 * @file IdentityService.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Authentication;

use Zend\Authentication\AuthenticationService;
use SMUser\Entity\Repository\UserRepositoryInterface;

/**
 * A generic service that checks if we have an identity. 
 */
class IdentityService
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
    		
    		if (!$this->userRepository instanceof UserRepositoryInterface) {
	            throw new \RuntimeException('No UserRepository instance provided');
    		}
    		
    		if (!$this->authenticationService instanceof AuthenticationService) {
	            throw new \RuntimeException('No AuthentificationService instance provided');
    		}
    		
    		if ($this->authenticationService->hasIdentity()) {
    			$identity = $this->authenticationService->getIdentity();
    		
    			// Load the user 
    			$this->identity = $this->userRepository->findOneByUsername($identity);
    		}
    	}

    	return $this->identity;
    }
}