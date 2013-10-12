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
		$link = "mailto:" . $address;
		return $this->link($link, $title);
	}
	
	/**
	 * Pretty print a link as a (Twitter Bootstrap 2) button.
	 * 
	 * @param string	$url	The url
	 * @param string	$title	The text on the button.
	 * @param string	$style	The style. For example 'primary', 'danger' or 'success';
	 */
	public function button($url, $title, $style = NULL)
	{
		$class = 'btn';
		if ($style) {
			$class .= ' btn-' . $style;
		}
		return $this->link($url, $title, $class);
	}
	
	public function link($url, $title, $class = NULL) 
	{
		if ($class) {
			$classAttribute = 'class="'. $class .'"';
		}
		else {
			$classAttribute = '';
		}
		return '<a href="'. $url .'" class="'. $class .'">' . $title . '</a>'; 
	}
}

?>