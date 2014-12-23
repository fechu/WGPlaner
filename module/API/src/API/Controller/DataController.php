<?php
/**
 * @file DataController.php
 * @date Nov 28, 2013
 * @author Sandro Meier
 */

namespace API\Controller;

use Zend\View\Model\JsonModel;
/**
 * API methods that do not belong anywhere.
 * Uses the /data route.
 */
class DataController extends AbstractRestfulController
{
	public function storesAction()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');

		return new JsonModel($repo->findUniqueStores());
	}
}