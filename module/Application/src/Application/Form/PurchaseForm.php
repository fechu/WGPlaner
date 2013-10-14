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
	
	public function __construct()
	{
		parent::__construct();
		
		$fieldset = new PurchaseFieldset();
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