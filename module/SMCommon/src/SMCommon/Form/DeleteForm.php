<?php
/**
 * @file RemoveForm.php
 * @date Aug 13, 2013 
 * @author Sandro Meier
 */
namespace SMCommon\Form;


use SMCommon\Form\AbstractForm;
/**
 * A form that can be used to get a confirmation from the user before 
 * deleting an object. 
 */
class DeleteForm extends AbstractForm
{
	/**
	 * Creates a new RemoveForm
	 * @param string $confirmButtonTitle	The label of the confirm button
	 */
	public function __construct($confirmButtonTitle = "Löschen")
	{
		// Construct the form
		parent::__construct('DeleteForm');
		
		// Set the method
		$this->setAttribute('method', 'post');
		
		$this->actionCollection->setSubmitButtonTitle($confirmButtonTitle);
		$this->actionCollection->setSubmitButtonType('danger');
	}	
} 