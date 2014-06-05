<?php
/**
 * @file Logger.php
 * @date Oct 29, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\Log;

class Logger extends \Zend\Log\Logger
{
	/**
	 * Contains the identity if someone is logged in. 
	 */
    protected $identity;
	
	public function log($priority, $message, $extra = array())
    {
    	// Add the identity if we have one.
    	if ($this->identity) {
    		$extra['identity'] = $this->identity;
    	}
    	
    	parent::log($priority, $message, $extra);
    }
    
    /**
     * Set the identity
     * @param $identity
     */
    public function setIdentity($identity)
    {
    	$this->identity = $identity;
    }
    
}