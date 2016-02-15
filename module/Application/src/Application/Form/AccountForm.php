<?php
/**
 * @file AccountForm.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace Application\Form;

use SMCommon\Form\AbstractForm;
use Zend\Stdlib\Hydrator\ClassMethods;

class AccountForm extends AbstractForm
{
    public function __construct()
    {
        parent::__construct('Account');

        $this->setHydrator(new ClassMethods(false));

        // Name
        $this->add(array(
            'name' => 'name',
            'type' => 'text',
            'options' => array(
                'label' => 'Name'
            ),
            'attributes' => array(
                'required' => 'required'
            )
        ));

        // Currency selection
        $this->add([
            'name' => 'currency',
            'type' => 'text',
            'options' => [
                'label' => 'Currency'
            ],
            'attributes' => [
                'required' => 'required'
            ]
        ]);

        // Slips enabled by default when adding new purchases.
        $this->add(array(
            'name' => 'slipEnabledDefault',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Enable receipt number by default',
            ),
        ));

        // Archive
        $this->add(array(
            'name' => 'archived',
            'type' => 'checkbox',
            'options' => array(
                'label' => 'Archived'
            ),
        ));
    }

    public function getInputFilterSpecification()
    {
        return array(
            'name' => array(
                'required' => true,
            ),
            'currency' => array(
                'required' => true,
            ),
        );
    }
}
