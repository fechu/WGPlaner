<?php

namespace Application\Acl;

use Zend\Permissions\Acl\Acl as ZendAcl;
use Application\Acl\Assertion\AccountAssertion;

class Acl extends ZendAcl
{

	/**
	 * A constant which can be used to indicate an DENIED error.
	 */
	const ACL_ACCESS_DENIED = "ACL_ACCESS_DENIED";

	public function __construct()
	{
		// Add the roles
		$this->addRole('user');

		// Add the resources
		$this->addResource('account');

		// Setup the rules
		$this->setupRules();
	}

	protected function setupRules()
	{
		// User account interaction.
		$this->allow('user', 'account', NULL, new AccountAssertion());
	}

}
