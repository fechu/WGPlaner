<?php
/**
 * @file PurchaseForm.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Form;

use SMCommon\Form\AbstractForm;
use Application\Form\Fieldset\PurchaseFieldset;

class PurchaseForm extends AbstractForm
{
	protected $fieldset;
	
	/**
	 * Create the form
	 * @param The entity manager used to load autocomplete data.
	 */
	public function __construct($em)
	{
		parent::__construct();
		
		$fieldset = new PurchaseFieldset($em);
		$fieldset->setUseAsBaseFieldset(true);
		$this->fieldset = $fieldset;
		$this->add($fieldset);
	}
	
	/**
	 * Set the slip number
	 * @param int $number
	 */
	public function setSlipNumber($number)
	{
		// Forward to the fieldset.
		$this->fieldset->setSlipNumber($number);
	}
}