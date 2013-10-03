<?php
namespace SMUser\Form;

use Zend\Form\Form;
use SMUser\Form\Fieldset\UserFieldset;

class UserForm extends Form
{
	public function __construct()
	{
		parent::__construct('userForm');
		
		// Add the user fieldset
		$userFieldset = new UserFieldset();
		$userFieldset->showVerifyPasswordField(true);	
		$userFieldset->setUseAsBaseFieldset(true);		// Base
		$this->add($userFieldset);
	}
}
