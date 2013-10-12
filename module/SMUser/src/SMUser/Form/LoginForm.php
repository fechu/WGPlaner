<?php
/**
 * @file LoginForm.php
 * @date Oct 12, 2013 
 * @author Sandro Meier
 */
 
namespace SMUser\Form;

use Zend\Form\Form;
use Zend\InputFilter\InputFilterProviderInterface;

/**
 * The login form. 
 * @warning This form is not rendered. It is only used for filtering 
 * 			the data.
 */
class LoginForm extends Form implements InputFilterProviderInterface
{
	public function __construct()
	{
		parent::__construct('login');
		
		// Password
		$this->add(array(
			'name' => 'username',
			'type' => 'Text',
			'attributes' => array(
				'required' => 'required'
			)
		));
		
		// Password
		$this->add(array(
			'name' => 'password',
			'type' => 'Password',
			'attributes' => array(
				'required' => 'required'
			)
		));
	}
	
	public function getInputFilterSpecification()
	{
		return array(
			'username' => array(
				'required' 	=> true,
				'filters'	=> array(
					array('name' => 'StringTrim'),
				)
			),
			'password' => array(
				'required'	=> true,
				'filters' 	=> array(
					array('name' => 'StringTrim'),
				)
			),
		);
	}
}