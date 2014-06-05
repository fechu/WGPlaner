<?php
/**
 * @file PurchaseListForm.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Form;

use SMCommon\Form\AbstractForm;
use Application\Form\Fieldset\AbstractBillingListFieldset;

class PurchaseListForm extends AbstractForm
{
	public function __construct()
	{
		parent::__construct('PurchaseList');
		
		$fieldset = new AbstractBillingListFieldset();
		$fieldset->setUseAsBaseFieldset(true);
		$this->add($fieldset);
	}
}