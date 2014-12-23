<?php

namespace API\Statistic;

/**
 * A graph (image) about something.
 * This is the base class for many other graph.
 */
class Graph {

	/**
	 * The Entity Manager which should be used to fetch the data.
	 * @var \Doctrine\ORM\EntityManager
	 */
	protected $em;

	protected $width = 600;
	protected $height = 250;

	/**
	 * The actual graph.
	 * @var \Graph
	 */
	protected $graph;

	public function __construct($em)
	{
		$this->em = $em;
	}

	/**
	 * This will create the graph.
	 * Use getGraph to get it.
	 */
	protected function drawGraph()
	{
		throw new \Exception("Cannot draw graph. You're supposed to overwrite this method");
	}

	public function getGraph()
	{
		if ($this->graph == NULL) {
			$this->drawGraph();
		}

		return $this->graph;
	}

	public function getWidth()
	{
		return $this->width;
	}

	public function setWidth($width)
	{
		$this->width = $width;
		return $this;
	}

	public function getHeight()
	{
		return $this->height;
	}

	public function setHeight($height)
	{
		$this->height = $height;
		return $this;
	}



}