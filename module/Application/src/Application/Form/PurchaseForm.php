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
        $this->setAttribute("autocomplete", "off");
        $this->setAttribute("class", "form-horizontal");
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

    /**
     * Get the value of the hasSlip checkbox.
     */
    public function hasSlip()
    {
        return $this->fieldset->hasSlip();
    }

    /**
     * Sets the default value of the hasSlip checkbox.
     */
    public function setHasSlip($hasSlip)
    {
        $this->fieldset->setHasSlip($hasSlip);
    }

    /**
     * Set if the verified field should be included in the form.
     * @param boolean $include
     */
    public function includeVerifiedField($include)
    {
        $this->fieldset->includeVerifiedField($include);
    }

    public function setCurrency($currency)
    {
        $this->fieldset->setCurrency($currency);
    }
}
