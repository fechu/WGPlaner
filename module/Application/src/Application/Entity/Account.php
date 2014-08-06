<?php
/**
 * @file Account.php
 * @date Oct 13, 2013
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SMCommon\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\AccountRepository")
 */
class Account extends AbstractEntity
{
    /**
     * The name of the account
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * Flag if the account is archived.
     * This is especially handy for accounts that are only temporary or for
     * special events.
     *
     * @ORM\Column(type="boolean")
     */
    protected $archived;

    /**
     * The user(s) that can add/remove/edit purchases in this list..
     *
     * @var ArrayCollection
     *
     * @ORM\ManyToMany(targetEntity="\Application\Entity\User", inversedBy="accounts")
     */
    protected $users;

    /**
     * The purchases.
     *
     * This is actually a one to many unidirectional relationship. But doctrine handles this as
     * a many to many with a unique constraint in the jointable.
     *
     * @ORM\OneToMany(targetEntity="Application\Entity\Purchase", mappedBy="account")
     * @ORM\OrderBy({"date" = "ASC"})
     */
    protected $purchases;

    public function __construct()
    {
        parent::__construct();

        $this->purchases = new ArrayCollection();
        $this->users = new ArrayCollection();
        $this->archived = false;
    }

    /**
     * Add a purchase to this account.
     * @param \Application\Entity\Purchase $purchase
     * @warning Use the opposite side to set an account. Purchase#setAccount()
     */
    public function addPurchase($purchase)
    {
        $this->purchases[] = $purchase;
    }

    public function getPurchases()
    {
        return $this->purchases->toArray();
    }

    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            throw new \InvalidArgumentException("Value is not a string. String required.");
        }
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * Add a user to the account.
     *
     * @param Application\Entity\User $user
     */
    public function addUser($user)
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addAccount($this);
        }
    }

    /**
     * Remove a user from the account
     *
     * @param Application\Entity\User
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    public function getUsers()
    {
        return $this->users->toArray();
    }

    /**
     * Checks if the user is associated with this account.
     */
    public function hasUser($user)
    {
        return in_array($user, $this->users->toArray());
    }

    ////////////////////////////////////////////////////////////////////////
    // Archiving
    ////////////////////////////////////////////////////////////////////////

    /**
     * @return True if the account is archived, false otherwise.
     */
    public function isArchived()
    {
        return $this->archived;
    }

    /**
     * Synonim for isArchived. 
     * 
     * Exists only for naming consistency.
     */
    public function getArchived()
    {
        return $this->isArchived();
    }
    

    /**
     * Archive this account.
     */
    public function archive()
    {
        $this->setArchived(true);
    }

    /**
     * Set the archived flag.
     */
    public function setArchived($flag)
    {
        $this->archived = (bool) $flag;
    }

}
