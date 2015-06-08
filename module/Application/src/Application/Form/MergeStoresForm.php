<?php
/**
 * @file MergeStoresForm.php
 * @date June 8, 2015
 * @author Sandro Meier
 */

namespace Application\Form;

use SMCommon\Form\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods;
use SMCommon\Form\Collection\ActionsCollection;

class MergeStoresForm extends AbstractForm
{
	public function __construct($em)
	{
		parent::__construct('MergeStores');

		$this->setHydrator(new ClassMethods(false));

		// First store field
		$repo = $em->getRepository('Application\Entity\Purchase');
		$uniqueStores = $repo->findUniqueStores();
		$this->add(array(
			'name' => 'toMerge',
			'type' => 'Text',
			'attributes' => array(
				'class'  => 'span5',
				'required' => 'required',
				'data-provide' => 'typeahead',
				'data-source' => json_encode($uniqueStores),
			)
		));

		$this->add(array(
			'name' => 'mergeTarget',
			'type' => 'Text',
			'attributes' => array(
				'class'  => 'span5',
				'required' => 'required',
				'data-provide' => 'typeahead',
				'data-source' => json_encode($uniqueStores),
			)
		));

		$actions = new ActionsCollection('Merge');
		$this->add($actions);
	}

	public function getInputFilterSpecification()
	{
		return array(
			'toMerge' => array(
				'required' => true,
			),
			'mergeTarget' => array(
				'required' => true,
			),
		);
	}
}