<?php
/**
 * @file PurchaseController.php
 * @date Nov 25, 2013
 * @author Sandro Meier
 */

namespace API\Controller;

use Zend\View\Model\JsonModel;
use Application\Form\PurchaseForm;
use Application\Entity\Purchase;

class PurchaseController extends AbstractRestfulController
{

	public function __construct()
	{
		$this->identifierName = "purchaseid";
	}

	/**
	 * Create a new purchase.
	 * @todo Deny adding of purchases to lists that do not belong to the user.
	 */
	public function create($data)
	{
		$user = $this->identity();
		$account = $this->getAccount();

		// Form for validating the data.
		$form = new PurchaseForm($this->em);

		$purchase = new Purchase();
		$form->bind($purchase);

		/* @var $request \Zend\Http\Request */
		$request = $this->getRequest();
		$form->setData(array('PurchaseFieldset' => $data));
		if ($form->isValid()) {
			// Set the logged in user user who did the purchase.
			$purchase->setUser($user);
			$purchase->setAccount($account);	// Add the purchase to this list.
			$purchase->setCreatedWithAPI(true);
			$this->em->persist($purchase);
			$this->em->flush();

			// Success!
			return $this->createdResponse();
		}
		else {
			return $this->badRequestResponse($form->getMessages());
		}

		return array(
			'form' => $form,
			'account' => $account,
		);
	}
}