<?php
/**
 * @file PurchaseListFieldset.php
 * @date Oct 13, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Form\Fieldset;

use Zend\Form\Fieldset;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\Element\DateSelect;
use Zend\InputFilter\InputFilterProviderInterface;

class AbstractBillingListFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
	{
		parent::__construct('PurchaseList');
		
		$this->setHydrator(new ClassMethods(false));

		// Name
		$this->add(array(
			'name' => 'name',
			'type' => 'text',
			'options' => array(
				'label' => 'Name'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		// Start Datum
		$this->addDateSelect('startDate', 'Start Datum');
		
		// End Date
		$this->addDateSelect('endDate', 'End Datum');
		
	}
	
	protected function addDateSelect($name, $label) 
	{
		// Start Datum
		$dateSelect = new DateSelect();
		$dateSelect->setLabel($label);
		$dateSelect->setName($name);
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
	}
	
	public function getInputFilterSpecification()
	{
		return array(
			'name' => array(
				'required' => true,
			),
		);
	}
}