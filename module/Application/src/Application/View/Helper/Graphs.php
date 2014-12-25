<?php
/**
 * @file Graphs.php
 * @date Dec 25, 2014
 * @author Sandro Meier
 */

namespace Application\View\Helper;

use Zend\Form\View\Helper\AbstractHelper;
use Application\Entity\Account;

/**
 * A handy viewhelper to render graphs.
 */
class Graphs extends AbstractHelper
{
	public function __invoke()
	{
		// We just return this instance.
		// Actual things should be done using the other methods.
		return $this;
	}

	/**
	 * Creates an url to the api which returns a monthly expenses bar graph.
	 * @param Account $account The account
	 * @param string $month The month of which you you want the graph. Format: Y-m (e.g. 2014-05)
	 * @param int $width The width of the graph.
	 * @param int $height The height of the graph.
	 */
	public function monthlyExpensesGraph($account, $month = NULL, $width = NULL, $height = NULL)
	{

		// Prepare query parameters
		$query = array(
			"accountid" => $account->getId()
		);

		if ($month !== NULL) {
			$query['month'] = $month;
		}

		if ($width != NULL) {
			$query['width'] = intval($width);
		}
		if ($height != NULL) {
			$query['height'] = intval($height);
		}

		$params = array( 'action' => 'monthly-expenses');

		/* @var $urlHelper \Zend\View\Helper\Url */
		$urlHelper = $this->getView()->plugin('url');

		// Create the url
		$url = $urlHelper("api/statistic/graph", $params, array('query' => $query));
		return $url;
	}

	/**
	 * Creates an url to the api which returns a store count overview pie chart.
	 * @param Account $account 		The account from which the purchases are taken.
	 * @param \DateTime|string $startdate  All purchases after this date will be used to generate the chart
	 * @param \DateTime|string $enddate 	All purchases before this date will be used to generate the chart
	 * @param int $maxStoreCount 	The number of stores that should be shown. The rest will be merged
	 * 							 	into an "others" section.
	 * @param int $width		 	The width of the graph
	 * @param int $height 		 	The height of the graph
	 */
	public function storeCountGraph(
			$account = NULL,
			$startdate = NULL,
			$enddate = NULL,
			$maxStoreCount = -1,
			$width = NULL,
			$height = NULL
	) {
		$query = array();
		if ($account !== NULL) {
			$query['accountid'] = $account->getId();
		}

		if ($startdate instanceof \DateTime) {
			$query['startdate'] = $startdate->format('Y-m-d');
		}
		else if (is_string($startdate)) {
			$query['startdate'] = $startdate;
		}

		if ($enddate instanceof \DateTime) {
			$query['enddate'] = $enddate->format('Y-m-d');
		}
		else if (is_string($enddate)) {
			$query['enddate'] = $enddate;
		}

		$query['max_store_count'] = intval($maxStoreCount);

		if ($width != NULL) {
			$query['width'] = intval($width);
		}
		if ($height != NULL) {
			$query['height'] = intval($height);
		}

		$params = array('action' => 'store-count');

		/* @var $urlHelper \Zend\View\Helper\Url */
		$urlHelper = $this->getView()->plugin('url');

		// Create the url
		$url = $urlHelper("api/statistic/graph", $params, array('query' => $query));
		return $url;
	}
}
