<?php
/**
 * @file BillController
 * @date June 26, 2014
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use Application\Entity\Account;
use Application\Entity\Purchase;

class BillController extends AbstractAccountController
{
	public function __construct()
	{
		$this->defaultId = 'bill';
	}

	public function indexAction()
	{

	}
}