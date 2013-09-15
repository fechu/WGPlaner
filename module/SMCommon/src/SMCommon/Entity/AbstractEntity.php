<?php

namespace SMCommon\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 **/
abstract class Base {
    
    
    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     **/
    protected $id;
    
    /**
     * @ORM\Column(type="utcdatetime")
     **/
    protected $created;
    
    /**
     * The date when the entity was modified the last time.
     * @ORM\Column(type="utcdatetime")
     **/
    protected $lastModified;
    
    /**
     * @ORM\Column(type="boolean")
     **/
    protected $deleted = false;
    
    public function __construct()
    {
		$this->created = new \DateTime();
		$this->lastModified = new \DateTime();
    }
    
    /**
     * @ORM\PreUpdate
     */
    public function updateLastModified()
    {
    	$this->lastModified = new \DateTime();
    }
    
    /**
     * Sets the deleted property. 
     * This method can be used to "undelete" an entity.
     */
    public function setDeleted($deleted) 
    {
    	$this->deleted = $deleted;
    }
    
    /**
     * Performs a soft delete of the entity to track deletions for iPhone/iPad
     **/
    public function delete()
    {
    	$this->setDeleted(true);
    }
    
    /**
     * Set the ID of the object. 
     * You don't need to set the id manually as doctrine creates it for you
     * 
     * warning: Do only use this if you know what you are doing!
     */
    public function setId($newId)
    {
    	$this->id = $newId;
    }
    
    /**
     * Returns the ID of the object
     **/
    public function getId()
    {
		return $this->id;
    }
    
    /**
     * Returns when the entity was created.
     **/
    public function getCreated()
    {
		return $this->created;
    }
    
    /**
     * Returns the date when the entity was last modified.
     */
    public function getLastModified()
    {
    	return $this->lastModified;
    }
    
    /**
     * Returns true if the entity is deleted.
     **/
    public function isDeleted()
    {
		return $this->deleted;
    }
}
