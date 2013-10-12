<?php 

namespace SMCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * PrettyPrint all sort of stuff. 
 * 
 * It helps you print for example an email address as an HTML <a></a> element.
 */
class PrettyPrint extends AbstractHelper 
{
	
	public function __invoke()
	{
		return $this;
	}
	
	/**
	 * Pretty prints an email address.
	 * 
	 * @param string 		$address The email address. 
	 * @param string|NULL 	The title shown for the link. 
	 */
	public function email($address, $title = NULL)
	{
		$title = ($title != NULL) ? $title : $address; 
		return '<a href="mailto:'. $address .'">' . $title . '</a>';
	}
}

?>