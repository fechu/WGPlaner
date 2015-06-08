<?php
/**
 * @file PurchaseTemplate.php
 * @date June 6, 2015
 * @author Sandro Meier
 */

namespace Application\Entity;

use SMCommon\Entity\AbstractEntity;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Console\Application;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity
 */
class PurchaseTemplate extends AbstractEntity
{

	/**
	 * The name of the template
	 *
	 * @var String
     * @ORM\Column(type="string")
	 */
	protected $name;

	/**
	 * Name of the store where you bought the things.
	 *
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Application\Entity\PurchaseTemplateStore", mappedBy="template")
	 */
	protected $stores;

	/**
	 * The contents which directly belong to this purchase
	 *
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Application\Entity\PurchaseTemplateContent", mappedBy="template")
	 */
	protected $contents;

	/**
	 * Name of the default store.
	 * This is used if a TemplatePreset is selected directly without going over a store and using
	 * its TemplatePresets.
	 *
	 * @var String
     * @ORM\Column(type="string")
	 */
	protected $defaultStore;

	/**
	 * The user who owns the template
	 *
	 * @ORM\ManyToOne(targetEntity="Application\Entity\User", inversedBy="templates")
	 */
	protected $user;

	public function __construct()
	{
		parent::__construct();

		$this->stores = new ArrayCollection();
	}

	public function getStores() {
		return $this->stores;
	}

	public function getContents() {
		return $this->contents;
	}

	public function getDefaultStore() {
		return $this->defaultStore;
	}

	public function getUser() {
		return $this->user;
	}

	public function getName() {
		return $this->name;
	}
}
