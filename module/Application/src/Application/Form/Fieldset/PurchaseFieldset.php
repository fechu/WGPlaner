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
	public function __construct()
	{
		parent::__construct('PurchaseFieldset');
		
		$this->setHydrator(new ClassMethods(false));
		
		// Purchase date
		$dateSelect = new Date();
		$dateSelect->setLabel('Datum');
		$dateSelect->setName('date');
		$dateSelect->setFormat('d.m.Y');
		$dateSelect->setAttribute('class', 'datepicker');
		$this->add($dateSelect);
		
		// Hat Beleg?
		$this->add(array(
			'name' => 'hasSlip',
			'type' => 'Checkbox',
			'options' => array(	
				'label' => 'Beleg',
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
				'label' => 'Belegnummer',
			),
			'attributes' => array(
				'id' => 'slipNumber',
			)
		));
		
		// Store
		$this->add(array(
			'name' => 'store',
			'type' => 'Text',
			'options' => array(
				'label' => 'Geschäft'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		// Description
		$this->add(array(
			'name' => 'description',
			'type' => 'Text',
			'options' => array(
				'label' => 'Beschreibung'
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
				'label' => 'Betrag',
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
}