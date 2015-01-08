<?php
/**
 * @file UserFieldset.php
 * @date Oct 3, 2013
 * @author Sandro Meier
 */

namespace SMUser\Form\Fieldset;
use Zend\Form\Fieldset;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\ElementInterface;
use Zend\Validator\StringLength;
use Zend\Form\Element\Password;

class UserFieldset extends Fieldset implements InputFilterProviderInterface
{

	public function __construct()
	{
		parent::__construct('user');

		$hydrator = new ClassMethods(false);
		$this->setHydrator($hydrator);

		// Username field
		$this->add(
			array(
				'name' => 'username',
				'type' => 'text',
				'options' => array(
					'label' => 'Username',
				),
				'attributes' => array(
					'required' => 'required'
				)
			)
		);

		// Full name field
		$this->add(array(
			'name' => 'fullname',
			'type' => 'text',
			'options' => array(
				'label' => 'Complete Name'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));

		// Email field
		$this->add(array(
			'name' => 'emailAddress',
			'type' => 'email',
			'options' => array(
				'label' => 'Email'
			),
		));
	}

	public function getInputFilterSpecification()
	{
		$filters =  array(
			'username' => array(
				'required' => true,
			),
			'fullname' => array(
				'required' => true,
			),
			'emailAddress' => array(
				'required' => false
			),

			'password' => array(
				'required' => true,
				'validators' => array(
					new StringLength(array('min' => 6)),
				)
			),

			'verify-password' => array(
				'required' => true,
				'validators' => array(
					array(
						'name' => 'Identical',
						'options' => array(
							'token' => 'password',
							'message' => array(
								\Zend\Validator\Identical::NOT_SAME => 'Die Passwörter stimmen nicht überein',
							)
						)
					)
				),
			)
		);

		/*
		 * This check is done because a filter failes silently if
		* there's no element for it.
		* @see Github (https://github.com/zendframework/zf2/issues/4017)
		*/
		$finalFilters = array();
		$keys = array_keys($filters);
		foreach ($keys as $key) {
			if ($this->has($key)) {
				$finalFilters[$key] = $filters[$key];
			}
		}
		return $finalFilters;
	}

	public function setShowPasswordField($show)
	{
		if (!$this->has('password') && $show) {
			// Password field
			$passwordField = new Password();
			$passwordField->setName('password');
			$passwordField->setOptions(array(
				'label' => 'Password'
			));
			$passwordField->setAttribute('required', true);
			$this->add($passwordField);
		}
		else if ($this->has('password') && !$show) {
			$this->remove('password');
		}
	}

	public function setShowVerifyPasswordField($show)
	{
		if (!$this->has('verify-password') && $show) {
			// Verify field (not added by default
			$verifyField = new Password();
			$verifyField->setName('verify-password');
			$verifyField->setOptions(array(
				'label' => 'Verify Password'
			));
			$this->add($verifyField);
		}
		else if ($this->has('verify-password') && $show) {
			$this->remove('verify-password');
		}
	}

}
