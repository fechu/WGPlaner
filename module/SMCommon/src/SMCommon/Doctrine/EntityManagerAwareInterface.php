<?php 

namespace SMCommon\Doctrine;

use Doctrine\ORM\EntityManager;
interface EntityManagerAwareInterface {

	/**
	 * Set the entity manager
	 */
	public function setEntityManager(EntityManager $em);

}
