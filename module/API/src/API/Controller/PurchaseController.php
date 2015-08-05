<?php

/**
 * @file PurchaseController.php
 * @date Nov 25, 2013
 * @author Sandro Meier
 */

namespace API\Controller;

use Application\Form\PurchaseForm;
use Application\Entity\Purchase;

class PurchaseController extends AbstractRestfulController {

    public function __construct() {
	$this->identifierName = "purchaseid";
    }

    /**
     * Create a new purchase.
     * @todo Deny adding of purchases to lists that do not belong to the user.
     */
    public function create($data) {
	$user = $this->identity();
	$account = $this->getAccount();

	// Form for validating the data.
	$form = new PurchaseForm($this->em);
	$form->includeVerifiedField(true);

	$purchase = new Purchase();
	$form->bind($purchase);

	/* @var $request \Zend\Http\Request */
	$request = $this->getRequest();
	$form->setData(array('PurchaseFieldset' => $data));
	if ($form->isValid()) {
	    // Set the logged in user user who did the purchase.
	    $purchase->setUser($user);
	    $purchase->setAccount($account); // Add the purchase to this list.
	    $purchase->setCreatedWithAPI(true);
	    $this->em->persist($purchase);
	    $this->em->flush();

	    // Success!
	    return $this->createdResponse($purchase->getId());
	} else {
	    return $this->badRequestResponse($form->getMessages());
	}
    }

    /**
     * Add a receipt to a purchase. 
     * 
     * This method will overwrite any previous purchases without warning. 
     */
    public function receiptAction() {
	// Check if the given purchase exists
	$purchase = $this->getPurchase();
	if ($purchase == NULL) {
	    return $this->badRequestResponse("Purchase does not exist.");
	}

	// If it is a GET request, we just return the image.
	$request = $this->getRequest();
	if ($request->isGet()) {
	    $imagePath = './data/receipts/' . $purchase->getId() . '.jpg';
	    $response = $this->getResponse();
	    $response->getHeaders()->addHeaders(array(
		'Content-Type' => 'image/jpg'
	    ));
	    $response->setContent(file_get_contents($imagePath));
	    return $response;
	}

	$form = new \SMCommon\Form\UploadForm('upload-form', 'receipt');

	if ($request->isPost()) {
	    // Merge data and files
	    $post = array_merge_recursive(
		    $request->getPost()->toArray(), $request->getFiles()->toArray()
	    );

	    $form->setData($post);
	    if ($form->isValid()) {
		$data = $form->getData();
		$file = $data['receipt'];

		if ($file == NULL) {
		    return $this->badRequestResponse('Received file is NULL.');
		}

		// Move the file to the right folder
		$dest = './data/receipts/' . $purchase->getId() . '.jpg';
		move_uploaded_file($file['tmp_name'], $dest);

		// Save that the purchase has a receipt now.
		$purchase->setHasReceipt(true);
		$this->em->flush();

		return $this->createdResponse();
	    }

	    return $this->badRequestResponse($form->getMessages());
	}
    }
}
