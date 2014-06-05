<?php

namespace SMCommon\Form\Collection;

use \Zend\Form\Element\Collection;

/**
 * A collection that contains a save button
 */
class ActionsCollection extends Collection
{
	public function __construct($submitButtonTitle = 'Speichern', $buttonType = 'primary')
	{
		parent::__construct('actions');
		
		// This is not a dynamic element
		$this->setAllowAdd(false);
		$this->setAllowRemove(false);
		
		// Set the class
		$this->setAttribute('class', 'form-actions');
		
		// Add the Save button
		$this->add(array(
			'name' => 'submit',
			'attributes' => array(
				'type' => 'submit',
				'value' => $submitButtonTitle,
				'class' => 'btn btn-' . $buttonType,
			),
			'options' => array(
				'bootstrap' => array(
					'style' => 'inline'
				)
			)
		));
	}
	
	/**
	 * Set the title of the submit button.
	 * 
	 * @param string $title The title you want to give to the button. 
	 */
	public function setSubmitButtonTitle($title)
	{
		$button = $this->get('submit');
		$button->setAttribute('value', $title);
	}
	
	/**
	 * Set the type of the submit button. 
	 * 
	 * @param string $type The type of the button. For example primary.'
	 * @see Twitter Bootstrap 2.x documentation about available button types.
	 */
	public function setSubmitButtonType($type) 
	{
		$button = $this->get('submit');
		$button->setAttribute('class', 'btn btn-' . $type);
	}
}
