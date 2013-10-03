<?php
namespace SMUser\Form;

use SMUser\Form\Fieldset\UserFieldset;
use SMCommon\Form\AbstractForm;

class UserForm extends AbstractForm
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
