<?php
/**
 * @file DaterangeForm.php
 * @date June 20, 2014
 * @author Sandro Meier
 */

namespace Application\Form;

use SMCommon\Form\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\Element\Date;

class DaterangeForm extends AbstractForm
{
	public function __construct()
	{
		parent::__construct('Account');

		// Change the submit button title
		$this->actionCollection->setSubmitButtonTitle('Anzeigen');

		// Startdate
		$dateSelect = new Date();
		$dateSelect->setLabel('Start date');
		$dateSelect->setName('start-date');
		$dateSelect->setFormat('Y-m-d');
		$dateSelect->setAttribute('class', 'datepicker');
		$this->add($dateSelect);

        // Enddate
		$dateSelect = new Date();
		$dateSelect->setLabel('End date');
		$dateSelect->setName('end-date');
		$dateSelect->setFormat('Y-m-d');
		$dateSelect->setAttribute('class', 'datepicker');
		$this->add($dateSelect);
	}

	public function getInputFilterSpecification()
	{
		return array(
				'startDate' => array(
						'required' 		=> false,
						'allow_empty' 	=> true
				),
		);
	}

	public function getStartDate()
	{
		$dateString = $this->get('start-date')->getValue();
		$date = new \DateTime($dateString);
		return $date;
	}

	public function getEndDate()
	{
		$dateString = $this->get('end-date')->getValue();
		$date = new \DateTime($dateString);
		return $date;
	}
}