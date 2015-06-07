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
class PurchaseTemplateStore extends AbstractEntity
{
	/**
	 * The template which this template store belongs to.
	 *
	 * @var PurchaseTemplate
	 * @ORM\ManyToOne(targetEntity="Application\Entity\PurchaseTemplate", inversedBy="stores")
	 */
	protected $template;

	/**
	 * The contents which belong to this template store.
	 *
	 * @var ArrayCollection
	 * @ORM\OneToMany(targetEntity="Application\Entity\PurchaseTemplateContent", mappedBy="store")
	 */
	protected $contents;

	/**
	 * The name of the store
	 *
	 * @var String
     * @ORM\Column(type="string")
	 */
	protected $name;


	public function getTemplate() {
		return $this->template;
	}

	public function getContents() {
		return $this->contents;
	}

	public function getName() {
		return $this->name;
	}

}
