<?php
/**
 * @file UserText.php
 * @date Sep 22, 2013 
 * @author Sandro Meier
 */
 
namespace ApplicationTest\Entity;


use Application\Entity\BillingList;
class BillingListTest extends \PHPUnit_Framework_TestCase
{
	protected $billingList;
	
	public function setUp()
	{
		$this->billingList = new BillingList();
	}
	
	public function testListIsCreatedSuccessfully()
	{
		$this->assertNotNull($this->billingList, 'List is not created!');
	}
	
	public function testSetName()
	{
		$name = "List Name";
		
		$this->billingList->setName($name);
		$this->assertEquals($name, $this->billingList->getName(), 'Name was not set correctly');
	}
	
	
	public function testNonStringNameThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		$invalidName = array();
			
		$this->billingList->setName($invalidName);	// Should throw exception
	}
	
	public function testSetStartdate()
	{
		$startDate = new \DateTime();
		$this->billingList->setStartDate($startDate);
		
		$this->assertEquals($startDate, $this->billingList->getStartDate(), 'Should be equal with the startdate that was set.');
	}
	
	public function testSetEndDate()
	{
		$endDate = new \DateTime();
		$this->billingList->setEndDate($endDate);
		
		$this->assertEquals($endDate, $this->billingList->getEndDate(), 'Should be equal with the enddate that was set.');
	}
	
	public function testSetStartdateAfterEnddateThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		$startDate = new \DateTime('now');
		$endDate = new \DateTime('now');
		$endDate->sub(new \DateInterval('P10D')); 	// Subtract 10 days.
		
		$this->billingList->setEndDate($endDate);
		
		$this->billingList->setStartDate($startDate);	// Throws exception if startdate is after enddate.
	}
	
	public function testSetEnddateBeforeStartDateThrowsException()
	{
		$this->setExpectedException('InvalidArgumentException');
		
		$startDate = new \DateTime('now');
		$endDate = new \DateTime('now');
		$endDate->sub(new \DateInterval('P10D')); 	// Subtract 10 days.
		
		$this->billingList->setStartDate($startDate);
		
		$this->billingList->setEndDate($endDate);	// Throws exception if enddate is before startdate.
	}
}