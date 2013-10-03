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

class UserFieldset extends Fieldset implements InputFilterProviderInterface
{
	public function __construct()
	{
		parent::__construct('user');
		
		$this->setHydrator(new ClassMethods(false));
		
		// Username field
		$this->add(
			array(
				'name' => 'username',
				'type' => 'text',
				'options' => array(
					'label' => 'Benutzername',
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
				'label' => 'Vollständiger Name'
			),
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		// Email field
		$this->add(array(
			'name' => 'email',
			'type' => 'email',
			'options' => array(
				'label' => 'Email'
			),
		));
		
		// Passwort
		$this->add(array(
			'name' => 'password',
			'type' => 'password',
			'options' => array(
				'label' => 'Passwort',
			),
			'attributes' => array(
				'required' => 'required',
			)
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
			'email' => array(
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
	

	/**
	 * If this is set to true, A second field for the password will be added that has to match
	 * the first password field.
	 * @param $flag boolean
	 */
	public function showVerifyPasswordField($flag)
	{
		if ($flag == true && (! $this->has('verify-password'))) {
			$this->add(array(
				'name' => 'verify-password',
				'type' => 'password',
				'options' => array(
					'label' => 'Password verifizieren',
				)
			));
		}
		else if ($flag == false && $this->has('verify-password')) {
			$this->remove('verify-password');
		}
	}
	
}
