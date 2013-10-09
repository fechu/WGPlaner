<?php
/**
 * @file PasswordFieldset.php
 * @date Oct 4, 2013 
 * @author Sandro Meier
 */

namespace SMUser\Form;
use Zend\InputFilter\InputFilterProviderInterface;
use Zend\Form\Element\Password;
use Zend\Validator\StringLength;
use Zend\Stdlib\Hydrator\ClassMethods;
use Zend\Form\Form;

/**
 * Form that contains password and verify password fieldset. 
 */
class PasswordForm extends Form implements InputFilterProviderInterface
{
	/**
	 * Password field.
	 * 
	 * @var Password
	 */
	protected $passwordField;
	
	/**
	 * Verify password field
	 * 
	 * @var Password
	 */
	protected $verifyPasswordField;
	
	public function __construct()
	{
		parent::__construct('password');
		
		// Password field
		$passwordField = new Password();
		$passwordField->setName('password');
		$passwordField->setOptions(array(
			'label' => 'Passwort'
		));
		$passwordField->setAttribute('required', true);
		$this->passwordField = $passwordField;
		$this->add($passwordField);
		
		// Verify field (not added by default
		$verifyField = new Password();
		$verifyField->setName('verify-password');
		$verifyField->setOptions(array(
			'label' => 'Passwort verifizieren'
		));
		$this->verifyPasswordField = $verifyField;
		$this->add($verifyField);
		
	}
	
	/**
	 * Show/Hide the verify password field.
	 */
	public function showVerifyPasswordField($value)
	{
		if ($value && !$this->has('verify-password')) {
			$this->add($this->verifyPasswordField);
		}
		else if (!$value && $this->has('verify-password')) {
			$this->remove('verify-password');
		}
	}
	
	public function getInputFilterSpecification()
	{
		$filters =  array(
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
	
}
