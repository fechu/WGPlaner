<?php
/**
 * @file FormatDateTest.php
 * @date Aug 7, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommonTest\View\Helper;

use SMCommon\View\Helper\FormatDate;

class FormatDateTest extends \PHPUnit_Framework_TestCase
{
	/**
	 * @var FormatDate
	 */
	protected $formatDate;
	
	public function setUp()
	{
		$config = array(
			'date-format' => 'j.d.Y',
			'time-format' => 'G:i'
		);
		$this->formatDate = new FormatDate($config);
	}
	
	public function testReturnsEmptyStringIfDateIsNULL()
	{
		$formatDate = $this->formatDate;
		$this->assertEquals('', $formatDate(NULL));
	}
}