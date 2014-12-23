<?php
/**
 * @file StatisticController.php
 * @date Dec 23, 2014
 * @author Sandro Meier
 */

namespace API\Controller;

use Zend\View\Model\JsonModel;
use API\Statistic\AccountGraph;
use API\Statistic\MonthAccountGraph;

/**
 *
 * Statistics! :-D
 *
 */
class StatisticController extends AbstractRestfulController
{

	public function testAction()
	{
		$repo = $this->em->getRepository('Application\Entity\Account');
		$account = $repo->findOneBy(array());
		$accountGraph = new MonthAccountGraph($account, new \DateTime(), $this->em);

		$graph = $accountGraph->getGraph();

		$graph->Stroke();

	}

	/**
	 * Returns a bar graph containing the monthly expenses in an account.
	 */
	public function monthExpensesAction()
	{
		/// TODO Implement acess rights

		$repo = $this->em->getRepository('Application\Entity\Account');
		$accountId = $this->params()->fromQuery('accountid',-1);
		$account = $repo->find($accountId);

		// Get the month
		$month = $this->params()->fromQuery('month', date('Y-m'));
		$month = new \DateTime($month);

		if ($account) {
			// Create the graph
			$accountGraph = new MonthAccountGraph($account, $month, $this->em);

			// Set size
			$size = $this->getGraphSize();
			$accountGraph->setWidth($size[0]);
			$accountGraph->setHeight($size[1]);

			// Render the graph
			$accountGraph->getGraph()->Stroke();
		}
		else {
		  	$this->getResponse()->setStatusCode(404);
		}
	}

	/**
	 * Reads the width and height of the requested graph from GET parameters
	 */
	protected function getGraphSize()
	{
		$width = $this->params()->fromQuery('width', 600);
		$height = $this->params()->fromQuery('height', 250);
		return array($width, $height);
	}

	public function storesAction()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');

		return new JsonModel($repo->findUniqueStores());
	}
}