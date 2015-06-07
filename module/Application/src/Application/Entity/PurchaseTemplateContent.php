<?php
/**
 * @file PurchaseTemplateStore.php
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
class PurchaseTemplateContent extends AbstractEntity
{
	/**
	 * The template which this template content belongs to.
	 *
	 * @var PurchaseTemplate
	 * @ORM\ManyToOne(targetEntity="Application\Entity\PurchaseTemplate", inversedBy="contents")
	 */
	protected $template;

	/**
	 * The store which this template content belongs to.
	 *
	 * @var PurchaseTemplateStore
	 * @ORM\ManyToOne(targetEntity="Application\Entity\PurchaseTemplateStore", inversedBy="contents")
	 */
	protected $store;

	/**
	 * The description
	 *
	 * @var String
     * @ORM\Column(type="string")
	 */
	protected $description;

	/**
	 * The price
	 *
	 * @var String
     * @ORM\Column(type="string")
	 */
	protected $price;

	public function getTemplate() {
		return $this->template;
	}

	public function getStore() {
		return $this->store;
	}

	public function getDescription() {
		return $this->description;
	}

	public function getPrice() {
		return $this->price;
	}
}
