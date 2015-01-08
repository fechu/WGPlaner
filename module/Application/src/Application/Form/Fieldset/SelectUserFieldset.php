<?php
/**
 * @file SelectUserFieldset.php
 * @date July 1, 2014
 * @author Sandro Meier
 */

namespace Application\Form\Fieldset;

use SMCommon\Form\AbstractForm;
use Doctrine\ORM\EntityManager;
use Application\Entity\User;
use Zend\Form\Fieldset;

class SelectUserFieldset extends Fieldset
{
	/**
	 * The entity manager
	 * @var EntityManager
	 */
	protected $em;

	/**
	 * @param EntityManager $em
	 */
	public function __construct(EntityManager $em)
	{
		parent::__construct('SelectUserFieldset');

		// Store the entity manager
		$this->em = $em;

		// Add the select form
		$this->add(array(
			'name' => 'user',
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(
				'object_manager'=> $em,
				'target_class'	=> 'Application\Entity\User',
				'property'	=> 'fullname',
				'is_method'	=> true,
				'label'		=> 'User',
			),
			'attributes' => array(
				'required' => 'required'
			)
		));
	}

	/**
	 * @return User|Null The selected user.
	 */
	public function getSelectedUser()
	{
		$userElement = $this->get('user');
		$id = $userElement->getValue();

		return $this->em->find('Application\Entity\User', $id);
	}
}
