<?php

namespace API\Statistic;


use Application\Entity\Account;
use Doctrine\ORM\EntityManager;
/**
 * A graph (image) about an account which shows exactely one year.
 */
class YearAccountGraph extends AccountGraph {

	/**
	 * Create a new MonthAccountGraph
	 * @param Account $account
	 * @param \DateTime $year Only the year of this date will be used.
	 * @param EntityManager $em
	 */
	public function __construct($account, $year, $em)
	{
		parent::__construct($account, $em);

		// Set startdate and enddate based on the given month.
		$year = $year->format('Y');
		$this->startDate = new \DateTime("first day of January " . $year);
		$this->endDate = new \DateTime("last day of December " . $year);

		// As only one month is shown, we can just show the day number
		$this->dateFormatType = self::DATE_FORMAT_TYPE_MONTH;
	}

	public function drawGraph()
	{
		parent::drawGraph();

		/* @var $graph \Graph */
		$graph = $this->graph;

		// Add subtitle
		$graph->subtitle->Set($this->startDate->format('Y'));

		// Set tick labels in x-axis
		$graph->xaxis->SetTickLabels(array("Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"));
		$graph->xaxis->SetLabelAlign('left');
		$graph->xaxis->SetLabelAngle(65);
	}

	protected function retrieveData()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');
		$data = $repo->findMonthlyAmountInRange($this->startDate, $this->endDate, $this->account);

		// We need to bring the data in the right form.
		$dates = array_map(function($entry) {
			$date = $entry['date'];
			return $date->getTimestamp();
		}, $data);
		$amounts = array_map(function($entry) { return floatval($entry['amount']); }, $data);

		return array($dates, $amounts);
	}

	public function setStartDate($startDate)
	{
		// Do nothing. Should not be able to overwrite start date
		return $this;
	}

	public function setEndDate($endDate)
	{
		// Do nothing. Should not be able to overwrite start date
		return $this;
	}

}