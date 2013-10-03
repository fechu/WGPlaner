<?php
/**
 * @file AbstractForm.php
 * @date Oct 3, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\Form;

use Zend\Form\Form;
use Zend\Form\Annotation\InputFilter;
use SMCommon\Form\Collection\ActionsCollection;

abstract class AbstractForm extends Form
{
	/**
	 * The action collection that will be added at the end. 
	 */
	protected $actionCollection;
	
	public function __construct($name=null, $options = array())
	{
		parent::__construct($name, $options);
		
		$this->setAttribute('method', 'post');
		
		// Initialize the action collection
		$this->actionCollection = new ActionsCollection();
	}	
	
	public function prepare()
	{
		parent::prepare();
		
		// Add the action collection
		$this->add($this->actionCollection);
	}
}