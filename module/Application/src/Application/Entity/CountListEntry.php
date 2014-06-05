<?php
/**
 * @file CountListEntry.php
 * @date Oct 27, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use SMCommon\Entity\AbstractEntity;

/**
 * Something that can be counted. 
 * This is normally used with(in) a CountList.
 * 
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CountListEntryRepository")
 */
class CountListEntry extends AbstractEntity
{
	/**
	 * The title of the entry.
	 * 
	 * @ORM\Column(type="string")
	 */
	protected $title;
	
	/**
	 * The actual count of the entry. 
	 * This is what matters!
	 * @ORM\Column(type="float")
	 */
	protected $count;
	
	/**
	 * The count list that is the "parent" of this entry. Can be null.
	 * 
	 * @ORM\ManyToOne(targetEntity="Application\Entity\CountList", inversedBy="entries")
	 */
	protected $countList;
	
	/**
	 * Set the count list of this entry.
	 * @param CountList $list
	 */
	public function setCountList(CountList $list) 
	{
		$this->countList = $list;
		$list->addEntry($this);
	}
	
	/**
	 * Set the count
	 */
	public function setCount($count)
	{
		$this->count = $count;
	}
	
	/**
	 * @return float The count of this 
	 */
	public function getCount()
	{
		return $this->count;
	}
	
	/**
	 * Set the title
	 * @param string $title
	 */
	public function setTitle($title)
	{
		$this->title = $title;
	}
	
	/**
	 * @return string The title
	 */
	public function getTitle()
	{
		return $this->title;
	}
	
	/**
	 * Increase the count by $value.
	 * @param float $value
	 */
	public function increaseCount($value) 
	{
		$this->count += $value;
	}
}