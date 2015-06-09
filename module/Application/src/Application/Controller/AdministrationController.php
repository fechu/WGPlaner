<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Application\Controller;

use Zend\View\Model\ViewModel;
use SMCommon\Controller\AbstractActionController;
use Application\Form\MergeStoresForm;
use Application\Administration\StoreAdministrator;
use Zend\Form\FormInterface;

class AdministrationController extends AbstractActionController
{
    public function indexAction()
    {
        return new ViewModel();
    }

    public function mergeStoresAction()
    {
    	$form = new MergeStoresForm($this->em);

    	$request = $this->getRequest();
    	if ($request->isPost()) {
    		$form->setData($request->getPost());
    		if ($form->isValid()) {
				// The entered data is valid.
				$data = $form->getData();
				$toMerge = $data['toMerge'];
				$target = $data['mergeTarget'];

				$administrator = new StoreAdministrator($this->em);
				$administrator->rename($toMerge, $target);
				$this->logger->info("Merged store "  . $toMerge . " into " . $target);
    		}
    	}
    	return array(
    		'form' => $form
    	);
    }
}
