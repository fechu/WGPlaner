<?php
/**
 * @file ObjectUrlDataProvider.php
 * @date Aug 29, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\View\Helper;

interface ObjectUrlProvider
{
	/**
	 * This method should return the route name that is used to show the object.
	 * @return string The route name
	 */
	public function getObjectRoute();
	
	/**
	 * Returns the ID of the object. 
	 * This ID will added to the url as GET paramter like this:
	 * 	
	 * 		/route/to/object?id=4
	 * 
	 */
	public function getId();
	
	/**
	 * Returns the human readable url title
	 */
	public function getUrlTitle();
}