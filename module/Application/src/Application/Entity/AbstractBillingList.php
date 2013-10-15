<?php
/**
 * @file List.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity;

use SMCommon\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\MappedSuperclass
 */
class AbstractBillingList extends AbstractEntity 
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
	
	/**
	 * The user(s) associated with this list.
	 * 
	 * @var ArrayCollection
	 * 
	 * @ORM\ManyToMany(targetEntity="\Application\Entity\User", inversedBy="billingLists")
	 * @ORM\JoinTable(name="billinglists_users")
	 */
	protected $users;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->users = new ArrayCollection();
	}
	
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
		if (is_string($date)) {
			// Try to make it a DateTime object
			$date = \DateTime::createFromFormat('Y-m-d', $date);
			$date->setTime(0,0,0);
		}
		
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
		if (is_string($date)) {
			// Try to make it a DateTime object
			$date = \DateTime::createFromFormat('Y-m-d', $date);
			$date->setTime(23, 59, 59);
		}
		
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
	 * Add a user to this list.
	 * 
	 * @param Application\Entity\User $user
	 */
	public function addUser($user)
	{
		$this->users[] = $user;
		$user->addBillingList($this);
	}
	
	/**
	 * Remove a user from the list
	 * 
	 * @param Application\Entity\User
	 */
	public function removeUser(User $user) 
	{
		$this->users->removeElement($user);
	}
	
	public function getUsers()
	{
		return $this->users->toArray();
	}
	
	/**
	 * Checks if the user is associated with this list.
	 */
	public function hasUser($user)
	{
		return in_array($user, $this->users->toArray());
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
	
	/**
	 * Checks if the date is in the list period. i.e. after startdate and before enddate.
	 */
	protected function isDateInPeriod($date)
	{
		$result = true;

		// Do we have a startdate. If yes, check if the given date is after the startdate.
		if ($this->startDate) {
			if (!($date >= $this->startDate)) {
				$result = false;
			}
		}
		
		// Do we have an enddate. If yes, check if the given date is before the enddate.
		if ($this->endDate) {
			if (!($date <= $this->endDate)) {
				$result = false;
			}
		}
		
		return $result;
	}
}