<?php

namespace API\Statistic;

use Application\Entity\Account;
use Doctrine\ORM\EntityManager;
/**
 * A graph (image) about an account.
 * It shows a bar diagram for the total expenses on a day.
 */
class AccountGraph extends Graph {

	const DATE_FORMAT_TYPE_FULL  = 'd.m.Y';
	const DATE_FORMAT_TYPE_DAY   = 'd';
	const DATE_FORMAT_TYPE_MONTH = 'M';

	/**
	 * The account for which the graph is produced
	 * @var \Application\Entity\Account
	 */
	protected $account;

	/**
	 * The format which is used to format the DateTime on the x axis.
	 * Use one of the DATE_FORMAT_TYPE_* constants.
	 */
	protected $dateFormatType = self::DATE_FORMAT_TYPE_FULL;

	/**
	 * The startdate when the graph should start
	 * @var \DateTime
	 */
	protected $startDate;
	/**
	 * The startdate when the graph should start
	 * @var \DateTime
	 */
	protected $endDate;


	/**
	 * Create a new AccountGraph
	 * @param Account $account
	 * @param EntityManager $em
	 */
	public function __construct($account, $em)
	{
		parent::__construct($em);

		$this->account = $account;
	}

	protected function drawGraph()
	{
		// Draw the graph.
		$data = $this->retrieveData();

		// Configure the graph
		$graph = new \Graph($this->getWidth(), $this->getHeight());
		$graph->title->Set($this->account->getName());
		$graph->SetScale('datlin');

		// Set margin depending on date type
		if ($this->dateFormatType == self::DATE_FORMAT_TYPE_FULL) {
        	$graph->SetMargin(50, 30, 30, 90);
		}
		else {
			$graph->SetMargin(50, 30, 30, 40);
		}

		// Axis
		$graph->yaxis->title->Set('Expenses (CHF)');
		$graph->yaxis->SetTitleMargin(30);
		$graph->xaxis->SetLabelAngle(90);
		$graph->xaxis->scale->SetDateFormat($this->dateFormatType);

		// Add the barplot
		$barplot = new \BarPlot($data[1], $data[0]);
		$barplot->SetWidth($this->getWidth() / 50);


		$graph->Add($barplot);

		$this->graph = $graph;
	}

	/**
	 * Gets us the data from the database we need.
	 */
	protected function retrieveData()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');
		$data = $repo->findDailyAmountInRange($this->startDate, $this->endDate, $this->account);

		// We need to bring the data in the right form.
		$dates = array_map(function($entry) {
			$date = $entry['date'];
			return $date->getTimestamp();
		}, $data);
		$amounts = array_map(function($entry) { return floatval($entry['amount']); }, $data);

		return array($dates, $amounts);
	}

	public function getStartDate()
	{
		return $this->startDate;
	}

	public function setStartDate($startDate)
	{
		$this->startDate = $startDate;
		return $this;
	}

	public function getEndDate()
	{
		return $this->endDate;
	}

	public function setEndDate($endDate)
	{
		$this->endDate = $endDate;
		return $this;
	}

}