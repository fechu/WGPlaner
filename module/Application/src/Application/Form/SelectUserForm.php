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

class SelectUserForm extends AbstractForm
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
		parent::__construct('SelectUserForm');
		
		// Store the entity manager
		$this->em = $em;
		
		// Add the select form 
		$this->add(array(
			'name' => 'user',
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => array(	
				'object_manager'=> $em,
				'target_class'	=> 'Application\Entity\User',
				'property'		=> 'fullname',
				'is_method'		=> true,
				'label'			=> 'Benutzer',
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