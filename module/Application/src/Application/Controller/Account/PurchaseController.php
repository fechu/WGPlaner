<?php
/**
 * @file PurchaseController.php
 * @date Oct 15, 2013
 * @author Sandro Meier
 */

namespace Application\Controller\Account;

use Application\Entity\Account;
use Application\Form\PurchaseForm;
use Application\Entity\Purchase;

class PurchaseController extends AbstractAccountController
{
    public function __construct()
    {
        $this->defaultId = 'purchase';
    }

    /**
     * Lists all purchases for of an account.
     * If a purchaseId is present in the route it will be forwarded to show that purchase.
     *
     * This action accepts additional GET parameters:
     *
     *  Both of the following 2 date parameters are optional. If both are not set, the current
     *  month will be taken as default. If both are set, the timespan is shown. And if
     *  only 1 is set, the other one will be ignored (it won't be pulled to the current month default).
     *
     * 	start-date: Will show only purchases after (and including) this day.
     * 	end-date:	Will show only purchases before (and including) this day.
     *
     */
    public function indexAction()
    {
        $purchaseId = $this->getId();
        $accountId = $this->getId('account');

        // If we have an ID we redirect to the showPurchase action.
        if ($purchaseId && $accountId) {
            return $this->forward()->dispatch('Application\Controller\Account\Purchase', array(
                '__NAMESPACE__'		=> 'Application\Controller\Account',
                'action' 			=> 'view',
                'accountid'			=> $accountId,
                'purchaseid'		=> $purchaseId,
            ));
        }

        $account = $this->getAccount();
        if (!$account) {
            $this->getResponse()->setStatusCode(404);

            return;
        }

        // Get start and end-date
        $startDate = $this->getDateFromRoute("start-date");
        $endDate = $this->getDateFromRoute("end-date");

        // If neither startDate nor endDate is set, we chose the
        // current month as a default.
        if (!$startDate && !$endDate) {
            $startDate = new \DateTime(date("Y-m-01"));
            $endDate = new \DateTime(date("Y-m-t"));
        }

        // Check if the dates are switched.
        if ($startDate > $endDate) {
            $temp = $startDate;
            $startDate = $endDate;
            $endDate = $temp;
        }

        // Get the purchases in the date range.
        /* @var $repository \Application\Entity\Repository\PurchaseRepository */
        $repository = $this->em->getRepository('Application\Entity\Purchase');
        $purchases = $repository->findInRange($startDate, $endDate, $account);

        return array(
            'account'	=> $account,
            'purchases'	=> $purchases,
            'startDate'	=> $startDate,
            'endDate'	=> $endDate,
        );
    }

    /**
     * Add a purchase to an account.
     */
    public function addAction()
    {
        $account = $this->getAccount();

        $form = new PurchaseForm($this->em);

        $purchase = new Purchase();
        $form->bind($purchase);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                // Set the logged in user user who did the purchase.
                $purchase->setUser($this->identity());
                $purchase->setAccount($account); 	// Add the purchase to this list.
                $this->em->persist($purchase);
                $this->em->flush();

                // Show all purchases of the account
                return $this->redirect()->toRoute('accounts/purchases', array(
                    'accountid' 	=> $account->getId(),
                ));
            }
        } else {
            // Set default slip number
            /* @var $repo \Application\Entity\Repository\PurchaseRepository */
            $repo = $this->em->getRepository('Application\Entity\Purchase');
            $form->setSlipNumber($repo->findNextSlipNumber($account));
        }

        return array(
            'form' 		=> $form,
            'account' 	=> $account,
        );
    }

    public function editAction()
    {
        $purchase = $this->getPurchase();
        $account = $this->getAccount();

        if ($purchase->getAccount() != $account) {
            // Purchase is not in this list.
            $this->getResponse()->setStatusCode(404);

            return;
        }

        $form = new PurchaseForm($this->em);
        $form->bind($purchase);

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                                var_dump($purchase);
                                die();
                // Got valid data.
                $this->em->flush();

                return $this->redirect()->toRoute('accounts/purchases', array('action' => NULL), array(), true);
            }
        }

        return array(
            'purchase' => $purchase,
            'form'	=> $form,
        );
    }

    /**
     * View a purchase
     */
    public function viewAction()
    {
        $account = $this->getAccount();
        $purchase = $this->getPurchase();

        if ($purchase->getAccount() != $account) {
            // Purchase is not in this purchase list!
            $this->getResponse()->setStatusCode(404);

            return;
        }

        return array(
            'purchase' => $purchase
        );
    }

}
