<?php
/**
 * @file Bill.php
 * @date Jun 18, 2014
 * @author Sandro Meier
 */

namespace Application\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;
use SMCommon\Entity\AbstractEntity;

/**
 * @ORM\Entity(repositoryClass="Application\Entity\Repository\BillRepository")
 */
class Bill extends AbstractEntity
{
    /**
     * The name of the bill
     * @ORM\Column(type="string")
     */
    protected $name;

    /**
     * The user(s) that have to pay or get money from this bill.
     * This are not directly user objects. UserBillShare objects are used to describe which
     * user has to pay what part of the amount.
     *
     * @var ArrayCollection
     *
     * @ORM\OneToMany(
                targetEntity="\Application\Entity\UserBillShare",
                mappedBy="bill",
                cascade={"persist", "remove"}
            )
     */
    protected $userShares;

    /**
     * The purchases which belong to this bill.
     *
     * This is actually a one to many unidirectional relationship. But doctrine handles this as
     * a many to many with a unique constraint in the jointable.
     *
     * @ORM\ManyToMany(targetEntity="Application\Entity\Purchase", inversedBy="bills")
     */
    protected $purchases;

    public function __construct()
    {
        parent::__construct();

        $this->purchases = new ArrayCollection();
        $this->userShares = new ArrayCollection();
    }

    /**
     * Add purchases to this bill.
     * @param array $purchases An array of Purchase objects.
     */
    public function addPurchases($purchases)
    {
        if (!is_array($purchases)) {
            // Put the object in an array
            $purchases = array($purchases);
        }

        /* @var $purchase \Application\Entity\Purchase */
        foreach ($purchases as $purchase) {
            $this->purchases[] = $purchase;

            // Check (and set if needed) the inverse side
           if (in_array($this, $purchase->getBills()) == false) {
               $purchase->addToBill($this);
           }

           // Add the user to this bill
            $this->addUser($purchase->getUser());
        }
    }

    /**
     * Get the purchases that are covered by this bill.
     *
     * @param User $user A user of which you want to get purchases. If NULL, the all purchases
     *                   of the bill will be returned.
     */
    public function getPurchases($user = NULL)
    {
        if ($user != NULL) {
            $callback = function (Purchase $purchase) use ($user) {
                return $purchase->getUser() == $user;
            };

            return array_filter($this->purchases->toArray(), $callback);
        } else {
            return $this->purchases->toArray();
        }
    }

    public function setName($name)
    {
        if (is_string($name)) {
            $this->name = $name;
        } else {
            throw new \InvalidArgumentException("Value is not a string. String required.");
        }
    }

    /**
     * Get the name of the bill.
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add a user to the account.
     *
     * @param \Application\Entity\User $user
     * @param float                    $share The share the user has to pay.
     */
    public function addUser($user, $share = 1)
    {
        $userShare = $this->getUserShare($user);

        if (!$userShare) {
            // Share does not yet exist. Create one.
            $userShare = new UserBillShare();
            $userShare->setUser($user);
            $this->userShares[] = $userShare;

            // Set inverse side
            $userShare->setBill($this);
        }

        // Set share
        $userShare->setShare($share);
    }

    /**
     * Get the users that have something to do with this bill.
     */
    public function getUsers()
    {
        // Get all the users out of the user shares
        return array_map(function (UserBillShare $userShare) {
            return $userShare->getUser();
        }, $this->getUserShares());
    }

    /**
     * Get all user shares
     */
    public function getUserShares()
    {
        return $this->userShares->toArray();
    }

    /**
     * Get the share for a specific user.
     *
     * @param  \Application\Entity\UserBillShare $user
     * @return \Application\Entity\UserBillShare
     */
    public function getUserShare($user)
    {
        // Search the share belonging to the user.
        foreach ($this->userShares as $share) {
            if ($share->getUser() == $user) {
                return $share;
            }
        }

        return NULL;
    }

    /**
     * Get the sum of all shares together.
     *
     * This is mostly used internally, but is public if you want easy access to this data.
     *
     * @return float The sum of all shares
     */
    public function getTotalUserShare()
    {
        $sum = 0;
        foreach ($this->userShares as $share) {
            $sum += $share->getShare();
        }

        return $sum;
    }

    /**
     * Get the amount which a user has to pay per share. 
     * This is nothing else then the total amount divided by the total share.
     */
    public function getAmountPerShare()
    {
        $totalAmount = $this->getAmount();
        $totalShares = $this->getTotalUserShare();

        if ($totalShares == 0) {
            return 0; // By definition
        }
        else {
            return $totalAmount / $totalShares;
        }
    }

    /**
     * Get the total amount of all purchases or the amount of the purchases
     * of a user.
     *
     * @param $user An user of which you want to get the total amount. If NULL, the total
     *              amount of all users will be returned.
     * @return float
     *
     * @author Sandro Meier
     **/
    public function getAmount($user = NULL)
    {
        $purchases = $this->getPurchases($user);

        // Sum them up
        $sum = 0;
        foreach ($purchases as $purchase) {
            /* @var $purchase Purchase */
            $sum += $purchase->getAmount();
        }

        return $sum;
    }

    /**
     * Get amount a user has to pay.
     *
     * This method takes the user shares into consideration when calculating
     * the amounts a user has to pay.
     *
     * @param User $user The user for which you want to get the amount he has to pay. If
     *                   this is NULL, the method will return the total amount as
     *                   getAmount does.
     *
     * @return float
     */
    public function getBillableAmount($user = NULL)
    {
        if ($user === NULL) {
            // By definition we return the total amount
            return $this->getAmount();
        } else {
            // We need to do the actual calculation

            // Get the value per share
            $shareValue = $this->getAmountPerShare();

            // Get the share object for the requested user.
            $share = $this->getUserShare($user);

            return $shareValue * $share->getShare();
        }
    }

}
