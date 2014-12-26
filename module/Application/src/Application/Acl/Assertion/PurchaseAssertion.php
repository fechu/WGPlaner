<?php

namespace Application\Acl\Assertion;

use Zend\Permissions\Acl\Assertion\AssertionInterface;
use Zend\Permissions\Acl\Acl as ZendAcl;
use Zend\Permissions\Acl\Role\RoleInterface;
use Zend\Permissions\Acl\Resource\ResourceInterface;
use SMCommon\Log\Logger;
use Application\Entity\Account;
use Application\Entity\User;
use Application\Entity\Purchase;

class PurchaseAssertion implements AssertionInterface
{
	/**
	 * Checks if an user is the owner of a Purchase.
	 * This requires $role to be a User object and $resource to be a Purchase object. Otherwise
	 * false is returned in every case.
	 */
	public function assert(
			ZendAcl $acl,
			RoleInterface $role = null,
			ResourceInterface $resource = null,
			$privilege = null
	) {
		if ($role instanceof User && $resource instanceof Purchase) {
			return ($role === $resource->getUser());
		}
		else {
			return false;
		}
	}
}
