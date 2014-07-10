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
use Zend\Stdlib\Hydrator\ClassMethods;

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

		$this->setHydrator(new ClassMethods(false));

		// Initialize the action collection
		$this->actionCollection = new ActionsCollection();
	}

	public function prepare()
	{
		parent::prepare();

		// Add the action collection
		$this->add($this->actionCollection);
	}

	/**
	 * Get the action collection. This allows you to modify button titles ect...
	 */
	public function getActionCollection()
	{
		return $this->actionCollection;
	}
}