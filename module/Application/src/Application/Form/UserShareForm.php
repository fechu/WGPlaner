<?php
/**
 * @file UserShareForm.php
 * @date July 1, 2014
 * @author Sandro Meier
 */
namespace Application\Form;


use SMCommon\Form\AbstractForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\User;
use Application\Form\Fieldset\SelectUserFieldset;
use Zend\Form\Element\Text;

class UserShareForm extends AbstractForm
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
        parent::__construct('UserShareForm');

        // Add the user fieldset
        $this->selectUserFieldset = new SelectUserFieldset($em);
        $this->add($this->selectUserFieldset);

        // Add the share field
        $numberField = new Text();
        $numberField->setName("share");
        $numberField->setLabel("Share");
        $numberField->setValue("1");
        $this->add($numberField);
    }

    /**
     * @return User|Null The selected user.
     */
    public function getSelectedUser()
    {
        return $this->selectUserFieldset->getSelectedUser();
    }

    public function getShare()
    {
        $numberField = $this->get('share');
        return $numberField->getValue();
    }
}
