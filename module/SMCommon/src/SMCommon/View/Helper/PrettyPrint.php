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
	
	/**
	 * Pretty print a button group. 
	 * 
	 * @param array 		$urls 	All Title/Link pairs. The first is the title and the link for the button. 
	 * 								All others will be in the dropdown.
	 * @param string|null	$style	The style of the button. (danger, primary...)
	 */
	public function splitButton($urls, $style = NULL)
	{
		if (count($urls) < 1) {
			throw new \InvalidArgumentException('URLs array needs to contain at least one element. ');
		}
		
		$mainButtonArray = $urls[0];
		$mainButtonTitle 	= isset($mainButtonArray['title']) ? $mainButtonArray['title'] : '';
		$mainButtonUrl 		= isset($mainButtonArray['url']) ? $mainButtonArray['url'] : ''; 
		
		$html = '<div class="btn-group">';
		$html .='<button class="btn" onclick="window.location.href=\''. $mainButtonUrl .'\'">'. $mainButtonTitle .'</button>';
		$html .='<button class="btn dropdown-toggle" data-toggle="dropdown">';
		$html .='<span class="caret"></span>';
		$html .='</button>';
		
		// Dropdown
		$html .= '<ul class="dropdown-menu">';
		
		// Add dropdown links.
		for ($i = 1; $i < count($urls); $i++) {
			
			$itemArray = $urls[$i];
			$itemTitle 	= isset($itemArray['title']) ? $itemArray['title'] : '';
			$itemUrl 	= isset($itemArray['url']) ? $itemArray['url'] : '';
			
			$html .= '<li><a href="'. $itemUrl .'">'. $itemTitle .'</a></li>';
		}
		
		$html .= '</ul></div> ';
		return $html;
	}
	
	public function link($url, $title, $class = NULL) 
	{
		if ($class) {
			$classAttribute = 'class="'. $class .'"';
		}
		else {
			$classAttribute = '';
		}
		return '<a href="'. $url .'" class="'. $class .'">' . $title . '</a> '; 
	}

        public function alert($message, $style = "info")
        {
            return '<div class="alert alert-'. $style .'">' . $message . '</div>';
        }
}

?>
