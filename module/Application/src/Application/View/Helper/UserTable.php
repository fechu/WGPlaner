<?php
/**
 * @file UserTable.php
 * @date Oct 15, 2013 
 * @author Sandro Meier
 */
 
namespace Application\View\Helper;

use SMCommon\View\Helper\Table;
use Application\Entity\User;

/** 
 * A basic user table.
 */
class UserTable extends Table
{
	public function __construct()
	{
		parent::__construct();
		
		$this->prepareUserColumns();
	}
	
	/**
	 * Adds all the default columns
	 */
	public function prepareUserColumns()
	{
		$this->addColumn(array('Name', 'getFullname'));
	}
	
	public function addUsernameColumn()
	{
		$this->addColumn(array('Benutzername', 'getUsername'));
	}
	
	public function addEmailColumn()
	{
		$this->addColumn(array(
			'headTitle' 	=> 'Email',
			'dataMethod'	=> function(User $user) {
				$email = $user->getEmailAddress();
				return $this->view->prettyprint()->email($email);
			}
		));
	}
}