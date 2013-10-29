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
use Application\Entity\PurchaseList;

class SelectPurchaseListForm extends AbstractForm
{
	/**
	 * The entity manager
	 * @var EntityManager
	 */
	protected $em;
	
	/**
	 * @param EntityManager $em 
	 * @param User			$user 	Show only lists that have something todo with this user.
	 */
	public function __construct(EntityManager $em, $user = NULL)
	{
		parent::__construct('SelectPurchaseList');
		
		// Store the entity manager
		$this->em = $em;
		
		
		
		// The select element
		$options = array(	
				'object_manager'=> $em,
				'target_class'	=> 'Application\Entity\PurchaseList',
				'property'		=> 'name',
				'is_method'		=> true,
				'label'			=> 'Einkaufsliste',
				'empty_option'	=> 'Keine Vorlage',
		);
		// Should we restrict for a user?
		if ($user) {
			$options['find_method'] = array(
				'name'	=> 'findForUser',
				'params'=> array(
					'user' => $user
				)
			);
		}
		// Finally add the element
		$this->add(array(
			'name' => 'purchaseList',
			'type' => 'DoctrineModule\Form\Element\ObjectSelect',
			'options' => $options,
		));
	}
	
	/**
	 * @return PurchaseList|Null The selected PurchaseList. 
	 */
	public function getSelectedPurchaseList()
	{
		$element = $this->get('purchaseList');
		$id = $element->getValue();
		
		return $this->em->find('Application\Entity\PurchaseList', $id);
	}
}