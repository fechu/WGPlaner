<?php
/**
 * @file CountListController.php
 * @date Oct 27, 2013 
 * @author Sandro Meier
 */
 
namespace Application\Controller\CountList;

class CountListController extends AbstractActionController
{
	public function __construct()
	{
		$this->defaultId = 'countlist';
	}
	
	/**
	 * List all active lists for the current user.
	 */
	public function indexAction()
	{
		/* @var $repo \Application\Entity\Repository\ListRepository */
		$repo = $this->em->getRepository('Application\Entity\CountList');
		
		// Find all active lists.
		$lists = $repo->findActiveForUser(new \DateTime(), $this->identity());
		
		return array(
			'lists' => $lists,
		);
	}
}