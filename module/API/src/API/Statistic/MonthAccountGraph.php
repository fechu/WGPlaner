<?php

namespace API\Statistic;

use Application\Entity\Account;
use Doctrine\ORM\EntityManager;
/**
 * A graph (image) about an account which shows exactely one month.
 */
class MonthAccountGraph extends AccountGraph {

	/**
	 * Create a new MonthAccountGraph
	 * @param Account $account
	 * @param \DateTime $month Only the month (and of course year) of this date will be used.
	 * @param EntityManager $em
	 */
	public function __construct($account, $month, $em)
	{
		parent::__construct($account, $em);

		// Set startdate and enddate based on the given month.
		$month = $month->format('Y-m');
		$this->startDate = new \DateTime("first day of " . $month);
		$this->endDate = new \DateTime("last day of " . $month);

		// As only one month is shown, we can just show the day number
		$this->dateFormatType = self::DATE_FORMAT_TYPE_DAY;
	}

	public function drawGraph()
	{
		parent::drawGraph();

		/* @var $graph \Graph */
		$graph = $this->graph;

		// Add subtitle
		$graph->subtitle->Set($this->startDate->format('F Y'));

		$graph->xaxis->title->Set('Day of Month');
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