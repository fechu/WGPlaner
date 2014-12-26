<?php

namespace Application\Acl\Assertion;

use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use SMCommon\Log\Logger;
use Application\Entity\Account;
use Application\Entity\User;

class AccountAssertion implements AssertionInterface
{
	/**
	 * Checks if an user has access to an account.
	 * This requires $role to be a User object and $resource to be a Account object. Otherwise false
	 * is returned in every case.
	 */
	public function assert(
			ZendAcl $acl,
			RoleInterface $role = null,
			ResourceInterface $resource = null,
			$privilege = null
	) {
		if (($role instanceof User) && ($resource instanceof Account)) {
			// Check if the user is assigned to this account
			return in_array($role, $resource->getUsers());
		} else {
			// By definition now allowed.
			return false;
		}
	}
}
