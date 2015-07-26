<?php

namespace Application\Controller;

use SMCommon\Controller\AbstractActionController;

class IndexController extends AbstractActionController 
{
    public function indexAction()
    {
	// Check if we have unverified purchases.
	$repo = $this->em->getRepository('Application\Entity\Purchase');
	$purchases = $repo->findNotVerifiedPurchases();
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
