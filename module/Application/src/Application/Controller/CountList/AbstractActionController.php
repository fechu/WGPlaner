<?php
/**
 * @file AbstractActionController.php
 * @date Oct 27, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\CountList;

use SMCommon\Controller\AbstractActionController as ActionController;

/**
 * Contains a few basic methods for all Controllers for CountLists.
 */
class AbstractActionController extends ActionController
{
	public function getCountList()
	{
		$id = $this->getId('countlist');
		
		/* @var $repo \Application\Entity\Repository\CountListRepository */
		$repo = $this->em->getRepository('Application\Entity\CountList');
		
		return $repo->find($id);
	}
}