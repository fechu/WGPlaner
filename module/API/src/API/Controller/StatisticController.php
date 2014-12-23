<?php
/**
 * @file StatisticController.php
 * @date Dec 23, 2014
 * @author Sandro Meier
 */

namespace API\Controller;

use Zend\View\Model\JsonModel;

/**
 *
 * Statistics! :-D
 *
 */
class StatisticController extends AbstractRestfulController
{

	public function testAction()
	{
		$width = 1200;
		$height = 500;

		$graph = new \Graph($width, $height);

		$graph->SetScale('intint');
		$graph->title->Set('Sunspot example');
		$graph->xaxis->title->Set('(year from 1701)');
		$graph->yaxis->title->Set('(# sunspots)');

		$ydata = array(1.2,5.2,4.5,5.5,10.0,0.8,7.7);
		$lineplot = new \LinePlot($ydata);

		$graph->Add($lineplot);

		$graph->Stroke();

	}

	public function storesAction()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');

		return new JsonModel($repo->findUniqueStores());
	}
}