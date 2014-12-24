<?php
/**
 * @file StatisticController.php
 * @date Dec 23, 2014
 * @author Sandro Meier
 */

namespace API\Controller\Statistic;

use Zend\View\Model\JsonModel;
use API\Statistic\AccountGraph;
use API\Statistic\MonthAccountGraph;
use API\Statistic\YearAccountGraph;
use API\Controller\AbstractRestfulController;

/**
 *
 * All sort of graphs.
 *
 * All methods accept the following GET paramters. Each method may also accept additional paramters.
 * Check out their description.
 *
 *   width:  The width of the returned image. Default: 600
 *   height: The height of the returned image. Default: 250
 *
 */
class GraphController extends AbstractRestfulController
{

	/**
	 * Returns a bar graph containing the yearly expenses grouped by month.
	 *
	 * The following GET parameters can be used.
	 *   accountid: The ID of the account which should be used.
	 *   year: Which year you want to display. E.g. 2014
	 *
	 */
	public function yearlyExpensesAction()
	{
		// Implement Access Rights

		// Get the account
		$account = $this->getAccount();

		// Get the year
		$year = intval($this->params()->fromQuery('year', -1));
		$date = new \DateTime();
		if ($year != -1) {
        	$date = new \DateTime($year . "-01-01");
		}

		if ($account) {
			$graph = new YearAccountGraph($account, $date, $this->em);

			// Set size
			$size = $this->getGraphSize();
			$graph->setWidth($size[0]);
			$graph->setHeight($size[1]);

			// Render the graph
			$graph->getGraph()->Stroke();
		}
		else {
			$this->getResponse()->setStatusCode(404);
		}
	}

	/**
	 * Returns a bar graph containing the monthly expenses in an account.
	 *
	 * The following GET arguments can be used:
	 *  accountid: The ID of the account which should be used.
	 *  month:  A month in the Format Y-m (e.g. 2014-05). If this is not supplied, the current month
	 *  		will be used.
	 */
	public function monthlyExpensesAction()
	{
		/// TODO Implement acess rights

		$account = $this->getAccount();

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

	/**
	 * Fetches the account based on the ID provided with the accountid GET parameter.
	 */
	protected function getAccount()
	{
		$repo = $this->em->getRepository('Application\Entity\Account');
		$accountId = $this->params()->fromQuery('accountid',-1);
		$account = $repo->find($accountId);
		return $account;
	}
}