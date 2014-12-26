<?php
/**
 * @file Acl.php
 * @date Dec 26, 2014
 * @author Sandro Meier
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

/**
 * A view helper to easily access the acl in a view.
 */
class Acl extends AbstractHelper implements ServiceLocatorAwareInterface
{
	/**
	 * The plugin manager
	 */
	protected $pluginManager;

	/**
	 * Forwards the call with all arguments to the acl's isAllowed method.
	 */
	public function __invoke($role = null, $resource = null, $privilege = null)
	{
		// Get the service locator
		$sm = $this->pluginManager->getServiceLocator();

		// Forward the call to the ACL.
		$acl = $sm->get('acl');
		return $acl->isAllowed($role, $resource, $privilege);
	}

	public function setServiceLocator(ServiceLocatorInterface $serviceLocator)
	{
		$this->pluginManager = $serviceLocator;
		return $this;
	}

	public function getServiceLocator()
	{
		return $this->pluginManager;
	}
}
