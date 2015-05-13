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
use API\Statistic\StoresGraph;
use Application\Entity\Account;
use API\Statistic\StoresAmountGraph;

/**
 *
 * All sort of graphs.
 *
 * All methods accept the following GET paramters. Each method may also accept additional paramters.
 * Check out their description.
 *
 *   width:  The width of the returned image.
 *   height: The height of the returned image.
 *
 */
class GraphController extends AbstractRestfulController
{

	/**
	 * Returns a pie chart containing the absolute counts of the stores of the purchases.
	 *
	 * The following GET parameters can be used
	 * accountid: The ID of the account. Can also be NULL.
	 * startdate: A date of the form Y-m-d. If not given, all purchases will be used til enddate.
	 * enddate: A date of the form Y-m-d. If not given, all purchases from startdate onwards
	 * 			will be used.
	 * max_store_count: The maximal number of stores which should be shown. All others are combined
	 * 					 in a "others" section.
	 * height: Default 500
	 * width: Default 500
	 */
	public function storeCountAction()
	{
		// Get the account
		$account = $this->getAccount();

		if ($account != NULL) {
			// Check if the user has access to that account
			if (!in_array($this->identity(), $account->getUsers())) {
				return $this->forbiddenResponse('You have no access to this account.');
			}
		}

		// Get start & end date
		$startdate = $this->params()->fromQuery('startdate', null);
		$enddate = $this->params()->fromQuery('enddate', null);
		if ($startdate) {
			$startdate = new \DateTime($startdate);
		}
		if ($enddate) {
			$enddate = new \DateTime($enddate);
		}

		// Get the max_store_number
		$maxStoreNumber = intval($this->params()->fromQuery('max_store_count', -1));

		// Get the size
		$size = $this->getGraphSize(500,500);

		// Create and configure the graph.
		$graph = new StoresGraph($this->em, $startdate, $enddate, $account);
		$graph->setMaxStoreCount($maxStoreNumber);

		// Adjust the size
		$graph->setWidth($size[0]);
		$graph->setHeight($size[1]);

		$graph->getGraph()->Stroke();
	}

	public function storeAmountAction()
	{
		// Get the account
		$account = $this->getAccount();

		if ($account != NULL) {
			// Check if the user has access to that account
			if (!in_array($this->identity(), $account->getUsers())) {
				return $this->forbiddenResponse('You have no access to this account.');
			}
		}

		// Get start & end date
		$startdate = $this->params()->fromQuery('startdate', null);
		$enddate = $this->params()->fromQuery('enddate', null);
		if ($startdate) {
			$startdate = new \DateTime($startdate);
		}
		if ($enddate) {
			$enddate = new \DateTime($enddate);
		}

		// Get the max_store_number
		$maxStoreNumber = intval($this->params()->fromQuery('max_store_count', -1));

		// Get the size
		$size = $this->getGraphSize(500,500);

		// Create and configure the graph.
		$graph = new StoresAmountGraph($this->em, $startdate, $enddate, $account);
		$graph->setMaxStoreCount($maxStoreNumber);

		// Adjust the size
		$graph->setWidth($size[0]);
		$graph->setHeight($size[1]);

		$graph->getGraph()->Stroke();
	}

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
		// Get the account
		$account = $this->getAccount();
		if ($account != NULL) {
			// Check if the user has access to that account
			if (!in_array($this->identity(), $account->getUsers())) {
				return $this->forbiddenResponse('You have no access to this account.');
			}
		}

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
			return $this->badRequestResponse('GET parameter accountid is required.');
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
		$account = $this->getAccount();
		if ($account != NULL) {
			// Check if the user has access to that account
			if (!in_array($this->identity(), $account->getUsers())) {
				return $this->forbiddenResponse('You have no access to this account.');
			}
		}

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
			return $this->badRequestResponse('GET parameter accountid is required.');
		}
	}

	/**
	 * Reads the width and height of the requested graph from GET parameters
	 */
	protected function getGraphSize($defaultWidth = 600, $defaultHeight = 250)
	{
		$width = $this->params()->fromQuery('width', $defaultWidth);
		$height = $this->params()->fromQuery('height', $defaultHeight);
		return array($width, $height);
	}

	/**
	 * Fetches the account based on the ID provided with the accountid GET parameter.
	 * @return Account
	 */
	protected function getAccount()
	{
		$repo = $this->em->getRepository('Application\Entity\Account');
		$accountId = $this->params()->fromQuery('accountid',-1);
		$account = $repo->find($accountId);
		return $account;
	}
}