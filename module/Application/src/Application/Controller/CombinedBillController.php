<?php
/**
 * @file BillController
 * @date Dec 16, 2015
 * @author Sandro Meier
 */

namespace Application\Controller;

use Application\Entity\Bill;
use Application\Entity\CombinedBill;
use Application\Form\BillForm;
use Application\Form\DaterangeForm;
use Application\Form\SelectBillForm;
use Application\Form\UserShareForm;
use SMCommon\Controller\AbstractActionController;
use SMCommon\Form\DeleteForm;
use SMCommon\Mail\Mail;
use SMCommon\Mail\Mailer;
use Zend\View\Renderer\RendererInterface;

class CombinedBillController extends AbstractActionController
{
    public function __construct()
    {
        $this->defaultId = 'bill';
    }

    /**
     * @return CombinedBill|null
     */
    public function getCombinedBill()
    {
        $id = $this->getId();
        return $this->em->find('Application\Entity\CombinedBill', $id);
    }

    public function indexAction()
    {
        // If we have an account ID we redirect to the view action.
		$id = $this->getId();
        if ($id !== 0) {
            return $this->forward()->dispatch('Application\Controller\BillController', array(
                    '__NAMESPACE__' => 'Application\Controller',
                    'action' 	    => 'view',
                    'billid'	    => $id,
            ));
        }

		// List all bills for the logged in user.
        /* @var $billRepo \Application\Entity\Repository\CombinedBillRepository */
        $billRepo = $this->em->getRepository('Application\Entity\CombinedBill');
        $bills = $billRepo->findForUser($this->identity());

        return array(
            'bills'	=> $bills,
        );
    }


    public function createAction()
    {
        // Create form and bind new bill to the form.
        $form = new BillForm();

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {

                // Create the new bill
                $name = $form->get('name')->getValue();
                $bill = new CombinedBill();
                $bill->setName($name);

                $this->em->persist($bill);
                $this->em->flush();

                $parameters = array(
                    'billid'	=> $bill->getId(),
                    'action'	=> 'add-bill',
                );
                return $this->redirect()->toRoute('bills/list-action', $parameters);
            }
        }

        return array(
            'form' => $form,
        );
    }

    /**
     * Add an user to a bill.
     */
    public function addBillAction()
    {
        $form = new SelectBillForm($this->em);
        $form->getActionCollection()->setSubmitButtonTitle("Add");

        $bill = $this->getCombinedBill();
        if (!$bill) {
            $this->getRequest()->setStatusCode(404);
            return;
        }

        /* @var $request \Zend\Http\Request */
        $request = $this->getRequest();

        if ($request->isPost()) {
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $selectedBill = $form->getSelectedBill();

                // Add the bill to the combined bill
                $bill->addBill($selectedBill);
                $this->em->flush();

                // Redirect to the users list
                return $this->redirect()->toRoute('bills');
            }
        }

        return array(
                'form'	    => $form,
                'bill'      => $bill,
        );
    }

    /**
     * Sends an email containing the invoice to all users.
     */
    public function sendInvoiceEmailAction()
    {
        $bill = $this->getCombinedBill();
        $recipients = $bill->getUsers();

        /** @var RendererInterface $renderer */
        $renderer = $this->getServiceLocator()->get('ViewRenderer');
        /** @var Mailer $mailer */
        $mailer = $this->getServiceLocator()->get('smcommon.mailer');

        // Send an email to each recipient.
        foreach ($recipients as $recipient) {
            $html = $renderer->render('email/invoice.phtml', [
                'recipient' => $recipient,
                'combinedBill' => $bill,
            ]);

            $mail = new Mail();
            $mail->setContent($html);
            $mail->setSubject('New Invoice - ' . $bill->getName());
            $mail->setRecipients([$recipient->getEmailAddress()]);

            $mailer->send($mail);
        }

        return $this->redirect()->toRoute('bills');
    }

}
