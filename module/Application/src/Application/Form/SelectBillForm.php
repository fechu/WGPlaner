<?php
/**
 * @file SelectBillForm
 * @date Dec 16, 2015
 * @author Sandro Meier
 */
namespace Application\Form;


use Application\Entity\Bill;
use SMCommon\Form\AbstractForm;
use Doctrine\ORM\EntityManager;

class SelectBillForm extends AbstractForm
{
    protected $em;
    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct('SelectBillForm');
        $this->em = $em;

        // Add the select form
        $this->add(array(
            'name' => 'bill',
            'type' => 'DoctrineModule\Form\Element\ObjectSelect',
            'options' => array(
                'object_manager'=> $em,
                'target_class'	=> 'Application\Entity\Bill',
                'property'	=> 'name',
                'label_generator' => function($bill) {
                    /** @var Bill $bill */
                    return $bill->getName() . '(' . $bill->getAccount()->getName() .')';
                },
                'label'		=> 'Invoice',
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));
    }

    /**
     * @return Bill|Null The selected bill.
     */
    public function getSelectedBill()
    {
        $billElement = $this->get('bill');
        $id = $billElement->getValue();

        return $this->em->find('Application\Entity\Bill', $id);
    }
}
