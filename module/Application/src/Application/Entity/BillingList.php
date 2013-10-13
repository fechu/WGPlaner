<?php
/**
 * @file List.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity;

use SMCommon\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 */
class BillingList extends AbstractEntity 
{
	/**
	 * The name of the list
	 * @ORM\Column(type="string")
	 */
	protected $name;
	
	/**
	 * The beginning of the period when you can enter data in this list.
	 * 
	 * @ORM\Column(type="utcdatetime")
	 */
	protected $startDate;
	
	/**
	 * The end of the period when you can enter data in this list.
	 * 
	 * @ORM\Column(type="utcdatetime")
	 */
	protected $endDate;
	
	public function setName($name)
	{
		
		if (is_string($name)) {
			$this->name = $name;
		}
		else {
			throw new \InvalidArgumentException("Value is not a string. String required.");
		}
	}
	
	
	public function getName()
	{
		return $this->name;
	}
	
	public function setStartDate($date)
	{
		// If no enddate we set it!
		if (!$this->endDate) {
			$this->startDate = $date;
		}
		else {
			// We need to check if it is valid.
			if ($this->validStartAndEndDate($date, $this->endDate)) {
				$this->startDate = $date;
			}
			else {
				throw new \InvalidArgumentException("Startdate may not be after enddate!");
			}
		}
	}
	
	public function getStartDate()
	{
		return $this->startDate;
	}
	
	public function setEndDate($date)
	{
		// If no startdate is set yet, we just set the enddate.
		if (!$this->startDate) {
			$this->endDate = $date;
		}
		else {
			// We have to check if it is valid with the start date.
			if ($this->validStartAndEndDate($this->startDate, $date)) {
				$this->endDate = $date;
			}
			else {
				throw new \InvalidArgumentException("Enddate may not be before startdate!");
			}
		}
	}
	
	public function getEndDate()
	{
		return $this->endDate;
	}
	
	/**
	 * Checks if the given Startdate is before the end date. 
	 * 
	 * @param \DateTime	$startDate
	 * @param \DateTime $endDate
	 * @return	boolean	True if they are valid, false otherwise
	 */
	protected function validStartAndEndDate($startDate, $endDate)
	{
		return $startDate < $endDate;
	}
}