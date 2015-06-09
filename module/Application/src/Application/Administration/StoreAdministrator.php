<?php

namespace Application\Administration;

use Doctrine\ORM\EntityManager;

class StoreAdministrator
{
	/**
	 * The entity manager used when the stores are merged.
	 * @var EntityManager
	 */
	protected $em;

	public function __construct($em)
	{
		$this->em = $em;
	}

	/**
	 * All purchases with store = $store will get renamed to have $newStore as their name.
	 * @param string $store
	 * @param string $targetStore
	 */
	public function rename($store, $newStore)
	{
		$purchaseRepo = $this->em->getRepository('Application\Entity\Purchase');
		$toChange = $purchaseRepo->findBy(array('store' => $store));

		foreach ($toChange as $purchase) {
			$purchase->setStore($newStore);
		}

		$this->em->flush();
	}

}