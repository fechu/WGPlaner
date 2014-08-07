<?php 

namespace Application\Navigation;

use Zend\Navigation\Service\DefaultNavigationFactory;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * Class Navigation
 *
 * Custom class that creates the navigation service
 *
 * @author Sandro Meier
 */
class NavigationFactory extends DefaultNavigationFactory
{
    protected function getPages(ServiceLocatorInterface $serviceLocator)
    {
        // Get the pages from the configuration
        parent::getPages($serviceLocator);

        // Create an array with the custom elements we want to add. 
        $em = $serviceLocator->get('doctrine.entitymanager.orm_default');
        $repo = $em->getRepository('Application\Entity\Account');

        // Get the user
        $identityService = $serviceLocator->get('smuser.identity');
        $user = $identityService->getIdentity();

        // Get the accounts 
        $accounts = $repo->findForUser($user);
        $accountsPage = $this->getAccountsPage();

        // Add a child page for every account
        $newPages = array();
        foreach ($accounts as $account){
            $page = array(
                'label' => $account->getName(),
                'route' => 'accounts/purchases',
                'action'=> 'index',
                'params'=> array(
                    'accountid'     => $account->getId(),
                )
            );
            $newPages[] = $page;
        }

        // Prepare and insert the new pages
        $newPages = $this->preparePages($serviceLocator, $newPages);
        $newPages = array_merge($newPages, $accountsPage['pages']);
        $accountsPage['pages'] = $newPages;
        
        // Replace the accounts object in the pages
        $index = $this->getAccountsPageIndex();
        $this->pages[$index] = $accountsPage;

        // Return the pages
        return $this->pages;
    }

    /**
     * Returns the accounts page. 
     *
     * @return  The accounts page represented as in the module configuration. Or null if it
     *          is not fount. But this should not be the case.
     */
    protected function getAccountsPage()
    {
        $index = $this->getAccountsPageIndex();
        if ($index !== NULL) {
            return $this->pages[$index];
        }

        return NULL;
    }

    /**
     * @return The index of the accounts page in the pages array or NULL if it is not found.
     */
    protected function getAccountsPageIndex()
    {
        // We have no choice. We have to go trough the list and look for the entry
        // with identifier 'accounts'.
        foreach ($this->pages as $index => $page){
            $identifier = isset($page['id']) ? $page['id'] : '';
            if ($identifier === 'accounts') {
                // We found it!
                return $index;
            }
        }

        return NULL;
    }
    
}

