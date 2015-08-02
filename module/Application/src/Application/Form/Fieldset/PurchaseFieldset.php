<?php
/**
 * @file PurchaseFieldset.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\Form\Element\DateSelect;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\Element\Date;

class PurchaseFieldset extends Fieldset implements InputFilterProviderInterface
{
	/**
	 * Create a PurchaseFieldset.
	 * @param $em The entity manager used to load the autocomplete stuff.
	 */
	public function __construct($em)
	{
		parent::__construct('PurchaseFieldset');

		$this->setHydrator(new ClassMethods(false));

		// Purchase date
		$dateSelect = new Date();
		$dateSelect->setLabel('Date');
		$dateSelect->setName('date');
		$dateSelect->setFormat('Y-m-d');
		$dateSelect->setAttribute('class', 'datepicker');
		$this->add($dateSelect);

		// Hat Beleg?
		$this->add(array(
			'name' => 'hasSlip',
			'type' => 'Checkbox',
			'options' => array(
				'label' => 'Receipt',
			),
			'attributes' => array(
				'id' => 'hasSlipCheckbox',
				'value' => 1
			)
		));

		// Beleg nummer
		$this->add(array(
			'name' => 'slipNumber',
			'type' => 'Text',
			'options' => array(
				'label' => 'Receiptnumber',
			),
			'attributes' => array(
				'id' => 'slipNumber',
			)
		));

		// Store
		// @todo Make the autocompletion with ajax calls. Especially if there are a lot stores in the future.
		/* @var $repo \Application\Entity\Repository\PurchaseRepository */
		$repo = $em->getRepository('Application\Entity\Purchase');
		$uniqueStores = $repo->findUniqueStores();
		$this->add(array(
			'name' => 'store',
			'type' => 'Text',
			'options' => array(
				'label' => 'Store'
			),
			'attributes' => array(
				'required' => 'required',
				'data-provide' => 'typeahead',
				'data-source' => json_encode($uniqueStores),
			)
		));

		// Description
		$this->add(array(
			'name' => 'description',
			'type' => 'Text',
			'options' => array(
				'label' => 'Description'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));

		// Amount
		$this->add(array(
			'name' => 'amount',
			'type' => 'Text',
			'options' => array(
				'label' => 'Amount',
				'bootstrap' => array(
					'append' => 'CHF',
				)
			),
			'attributes' => array(
				'required' => 'required'
			)
		));

	}

	public function getInputFilterSpecification()
	{
		return array(
			'hasSlip' => array(
				'required' 		=> false,
				'allow_empty' 	=> true
			),
		    	'verified' => array(
			    'requierd' => false, 
			    'allow_empty' => true
			),
			'slipNumber' => array(
				'required' 	=> false,
				'allow_empty' => true,
				'validators' => array(
					array('name' => 'Int'),
				)
			),
			'store' => array(
				'required' => true,
			),
			'description' => array(
				'required' => true,
			),
			'amount' => array(
				'required' => true,
				'validators' => array(
					array('name' => 'Float'),
				)
			),
		);
	}

	/**
	 * Set the value of the slipNumber element
	 * @param int $number
	 */
	public function setSlipNumber($number)
	{
		/* @var $element \Zend\Form\Element\Text */
		$element = $this->get('slipNumber');
		$element->setValue($number);
	}

        /**
         * Get the value of the hasSlip checkbox.
         */
        public function hasSlip()
        {
            $element = $this->get('hasSlip');
            return $element->getValue();
        }


        /**
         * Sets the default value of the hasSlip checkbox.
         */
        public function setHasSlip($hasSlip)
        {
            $element = $this->get('hasSlip');
            $element->setAttribute('value', (bool)$hasSlip);
        }

	public function includeVerifiedField($include) 
	{
	    if ($include) {
		// Verified
		$this->add(array(
		    'name' => 'verified',
		    'type' => 'Checkbox', 
		    'options' => array(
			'label' => 'Verified'
		    )
		));
	    }
	    else {
		$this->remove('verified');
	    }
	}
}
