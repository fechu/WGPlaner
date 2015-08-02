<?php

namespace API\View\Helper;

use Zend\View\Helper\AbstractHelper;

class ReceiptImage extends AbstractHelper {
    
    /**
     * Generates the URL to a receipt image.
     * 
     * @param Application\Entity\Purchase $purchase
     */
    public function __invoke($purchase) {
	$urlPlugin = $this->getView()->plugin('url');
	$params = array(
	    'accountid' => $purchase->getAccount()->getId(),
	    'purchaseid' => $purchase->getId(),
	    'action' => 'receipt'
	    );
	$receiptURL = $urlPlugin('api/accounts/purchase/actions',$params);
	return $receiptURL;
    }
    
}

