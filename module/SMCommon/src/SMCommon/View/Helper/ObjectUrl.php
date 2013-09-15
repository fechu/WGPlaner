<?php
/**
 * @file ObjectUrl.php
 * @date Aug 29, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;
use Zend\Stdlib\ArrayUtils;

class ObjectUrl extends AbstractHelper
{
	/**
	 * To render something use the render() and button() objects of the returned class
	 * 
	 * @see render()
	 * @see button()
	 * @return An instance of the view helper
	 */
	public function __invoke()
	{
		return $this;
	}
	
	public function button($object, $title = NULL,  $action = 'show', $style = NULL, $params = array())
	{
		$btnStyle = $style ? 'btn btn-' . $style : 'btn';
		return $this->render($object, $action, $params, $title, $btnStyle);
	}
	
	/**
	 * Renders an URL to an object with the specified parameters
	 * 
	 * @param ObjectUrlProvider	$object	An object that implements the ObjectUrlProvider Interface. 
	 * @param String			$action	The action that should be performed. This is a action for a AbstractActionController. It will be
	 * 									set as the 'action' parameter in the route.
	 * @param array				$params Additional GET parameters that will be appended to the URL. You don't have to specify the ID. It will 
	 * 									be added automatically
	 * @param string|NULL		$class	The value of the optional (HTML) class attribute.
	 * 
	 * @return string	The rendered URL
	 */
	public function render(ObjectUrlProvider $object, $action = 'show', $params = array(), $title = NULL, $class = NULL )
	{
		if (!$object instanceof ObjectUrlProvider) {
			throw new InvalidArgumentExcpetion('Invalid object provided. Objects must implement ObjectUrlProvider interface');
		}
		
		// Get url plugin
		$url = $this->getView()->plugin('url');
		
		// Get the data
		$routeName = $object->getObjectRoute();
		$idParam = array('id' => $object->getId());
		$params = ArrayUtils::merge($params, $idParam);
		$title = $title ? $title : $object->getUrlTitle();
		
		// Create the url
		$urlString = $url($routeName, array('action' => $action), array(
			'query' => $params
		));
		
		// Prepare the class attribute
		if ($class !== NULL) {
			$classAttr = 'class="'. $class .'"';
		}
		else {
			$classAttr = '';
		}
		
		return '<a href="' . $urlString . '"' . $classAttr . '>'. $title .'</a> ';
	}
}