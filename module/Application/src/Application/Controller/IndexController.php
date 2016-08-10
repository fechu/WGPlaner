<?php

namespace Application\Controller;

use SMCommon\Controller\AbstractActionController;

class IndexController extends AbstractActionController 
{
    public function indexAction()
    {
		// Check if we should show all not verified purchases or only the current users.
        $showAll = (bool)($this->params()->fromQuery('all', 0));
        $user = null;
        if ($showAll == false) {
            $user = $this->identity();
        }

		// Check if we have unverified purchases.
		$repo = $this->em->getRepository('Application\Entity\Purchase');
		$purchases = $repo->findNotVerifiedPurchases($user);
		if (count($purchases) == 0) {
			// Redirect to the accounts page.
			return $this->redirect()->toRoute('accounts/list-action');
		}

		// We want to show the unverified purchases for verification.
		return array(
			'purchases' => $purchases
		);
	}
}
