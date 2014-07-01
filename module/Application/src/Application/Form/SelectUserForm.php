<?php
/**
 * @file SelectUserForm.php
 * @date Oct 15, 2013
 * @author Sandro Meier
 */

namespace Application\Form;

use SMCommon\Form\AbstractForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\User;
use Application\Form\Fieldset\SelectUserFieldset;

class SelectUserForm extends AbstractForm
{
    /**
     * The actual fieldset which contains the form elements
     * @var SelectUserFieldset
     */
    protected $selectUserFieldset;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        parent::__construct('SelectUserForm');

        // Store the entity manager
        $this->em = $em;

        // Add the fieldset
        $this->selectUserFieldset = new SelectUserFieldset($em);
        $this->add($this->selectUserFieldset);
    }

    /**
     * @return User|Null The selected user.
     */
    public function getSelectedUser()
    {
        return $this->selectUserFieldset->getSelectedUser();
    }
}
