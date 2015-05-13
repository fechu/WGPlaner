<?php

namespace API\Statistic;

/**
 * Shows the stores with their size relative to the amount that was bought there.
 *
 */
class StoresAmountGraph extends Graph {

	protected $startDate;
	protected $endDate;
	protected $account;

	/**
	 * The maximal number of stores (slices) that is shown. If there are more then that they will
	 * be merged together into one slice called "others".
	 */
	protected $maxStoreCount = -1;

	public function __construct($em, $startDate = NULL, $endDate = NULL, $account = NULL)
	{
		parent::__construct($em);
		$this->width = 500;
		$this->height = 500;

		$this->startDate = $startDate;
		$this->endDate = $endDate;
		$this->account = $account;
	}

	protected function drawGraph()
	{
		// Get the data
		$data = $this->retrieveData();

		// Prepare the labels
		$labels = array_map(function($entry) {
			return $entry . " (%.2f)";
		}, $data[0]);

		$graph = new \PieGraph($this->width, $this->height);
		$graph->SetShadow();

		$graph->title->Set('Amount per store');

		if (count($data[1])) {
			// We got data. So let's plot it!
			$plot = new \PiePlot($data[1]);
			$plot->SetLabels($labels);
			$plot->SetGuideLines();
			$plot->SetGuideLinesAdjust(1.4);
			$plot->SetLabelType(PIE_VALUE_ABS);

        	$graph->Add($plot);
		}

		$this->graph = $graph;
	}

	protected function retrieveData()
	{
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $this->em->getRepository('Application\Entity\Purchase');
		$data = $repo->findStoreAmountInRange($this->startDate, $this->endDate, $this->account);

		// Bring the data into the right form
		$stores = array();
		$amounts = array();
		foreach ($data as $entry) {
			$stores[] = $entry['store'];
			$amounts[] = $entry['amount'];
		}

		if (($this->maxStoreCount > 0) && (count($stores) > $this->maxStoreCount + 1)) {
			// We need to merge the rest into an another section.
			$stores = array_slice($stores, 0, $this->maxStoreCount);
			$new_amounts = array_slice($amounts, 0, $this->maxStoreCount);

			// Sum the rest up.
			$sum = 0;
			for ($i = $this->maxStoreCount; $i < count($amounts); $i++) {
				$sum += $amounts[$i];
			}
			$stores[] = "others";
			$new_amounts[] = $sum;
			$amounts = $new_amounts;
		}

		return array($stores, $amounts);
	}

	public function getMaxStoreCount()
	{
		return $this->maxStoreCount;
	}

	public function setMaxStoreCount($maxStoreCount)
	{
		$this->maxStoreCount = $maxStoreCount;
		return $this;
	}
}