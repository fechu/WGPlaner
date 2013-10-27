<?php
/**
 * @file CountList.php
 * @date Oct 27, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Entity;

use \Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\CountListRepository")
 */
class CountList extends AbstractBillingList
{
	/**
	 * The entries that can be counted. 
	 * 
	 * @ORM\OneToMany(targetEntity="Application\Entity\CountListEntry", mappedBy="countList")
	 */
	protected $entries;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->entries = new ArrayCollection();
	}
	
	/**
	 * Add an entry to the list.
	 * @warning Will no upgrade the opposite site of the relationship. You 
	 * are advised to use setCountList() of CountListEntry.
	 */
	public function addEntry($entry)
	{
		$this->entries[] = $entry;
	}
}