<?php
/**
 * @file PurchaseListTest.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace APITest\Statistic;


use API\Statistic\Graph;
class GraphTest extends \PHPUnit_Framework_TestCase
{
	protected $graph;

	public function setUp()
	{
		$this->graph = new Graph(NULL);
	}

	public function testGetGraphThrowsException()
	{
		// This is the baseclass. So one should not be able to get a graph out of it.
		$this->setExpectedException('Exception');
		$this->graph->getGraph();
	}

	public function testDefaultHeightAndWidth()
	{
		$this->assertEquals(600, $this->graph->getWidth());
		$this->assertEquals(250, $this->graph->getHeight());
	}
}
