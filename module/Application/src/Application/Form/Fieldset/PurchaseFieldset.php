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

class PurchaseFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
	{
		parent::__construct('PurchaseFieldset');
		
		// Purchase date
		$dateSelect = new DateSelect();
		$dateSelect->setLabel('Datum');
		$dateSelect->setName('date');
		$dateSelect->setDayAttributes(array(
			'class' => 'span1'
		));
		$dateSelect->setMonthAttributes(array(
			'class' => 'span2',
		));
		$dateSelect->setYearAttributes(array(
			'class' => 'span1'
		));
		$dateSelect->setMinYear(date('Y') - 1);
		$dateSelect->setMaxYear(date('Y') + 1);
		$this->add($dateSelect);
		
		// Hat Beleg?
		$this->add(array(
			'name' => 'hasSlip',
			'type' => 'Checkbox',
			'options' => array(	
				'label' => 'Beleg'
			),
			'attributes' => array(
				'id' => 'hasSlipCheckbox',
				'checked' => true,
			)
		));
		
		// Beleg nummer
		$this->add(array(
			'name' => 'slipNumber',
			'type' => 'Number',
			'options' => array(
				'label' => 'Belegnummer',
			),
			'attributes' => array(
				'id' => 'slipNumber',
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
			),
		);
	}
}