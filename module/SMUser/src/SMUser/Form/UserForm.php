<?php
namespace SMUser\Form;

use SMUser\Form\Fieldset\UserFieldset;
use SMCommon\Form\AbstractForm;

class UserForm extends AbstractForm
{
	/**
	 * The user fieldset used in the form.
	 */
	protected $userFieldset;
	
	public function __construct()
	{
		parent::__construct('userForm');
		
		// Add the user fieldset
		$userFieldset = new UserFieldset();
		$userFieldset->setUseAsBaseFieldset(true);		// Base
		$this->userFieldset = $userFieldset;
		$this->add($userFieldset);
	}
	
	/**
	 * Show/Hide the password and verify password field.
	 * They are hidden by default.
	 */
	public function setShowPasswordFields($show)
	{
		// Forward to the fieldset.
		$this->userFieldset->setShowPasswordField($show);
		$this->userFieldset->setShowVerifyPasswordField($show);
	}
}
