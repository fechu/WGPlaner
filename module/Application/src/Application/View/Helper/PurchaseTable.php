<?php
/**
 * @file PurchaseTable.php
 * @date July 9, 2014
 * @author Sandro Meier
 */

namespace Application\View\Helper;

use SMCommon\View\Helper\Table;
use Application\Entity\Purchase;
use SMCommon\View\Helper\FormatDate;

/**
 * A table with a list of purchases. 
 *
 * The purchases sorted by date. The table consists of the following columns:
 *
 * - Number
 * - Date
 * - Store
 *
 * Optionally you can use the "addUserColumn" method to include a column for each user. 
 * The content of the column will be the the amount if the purchase belongs to this user. 
 *
 * If you want an actions column, you need to add it to yourself.
 */
class PurchaseTable extends Table
{
    public function __construct()
    {
        parent::__construct();

        $this->preparePurchaseColumns();
    }

    /**
     * Adds all the default columns. 
     */
    public function preparePurchaseColumns()
    {
        // Date Column
        $this->addColumn(array(
            'headTitle'     => 'Datum',
            'dataMethod'    => function (Purchase $purchase) {
                return $this->view->formatDate($purchase->getDate(), FormatDate::FORMAT_DATE);
            }
        ));

        // Store Column
        $this->addColumn(array('Geschäft', 'getStore'));
    }

    public function addUserColumns()
    {
        // @TODO Fix this
        // Get the account from the first purchase. This is a little hacky, butt will
        // work at the moment as only purcheses of the same account are showed in a 
        // purchase table.
        $firstPurchase = isset($this->data[0]) ? $this->data[0] : NULL;

        if (!$firstPurchase) {
            // Nothing to list. There's no sence in adding user columns
            return;
        }

        // Get the account
        $account = $firstPurchase ? $firstPurchase->getAccount() : NULL;
    


        // Each user has its own column
        foreach ($account->getUsers() as $user) {
            $this->addColumn(array(
                'headTitle' 	=> $user->getFullname(),
                'dataMethod'	=> function(Purchase $purchase) use ($user) {
                    if ($purchase->getUser() == $user) {
                        return number_format($purchase->getAmount(), 2) . " CHF";
                    }
                },
                'footerData'	=> function($purchases) use ($user) {
                    // Show the total amount of that user in the footer row.
                    $totalAmount = 0;
                    foreach ($purchases as $purchase) {
                        if ($purchase->getUser() == $user) {
                            $totalAmount += $purchase->getAmount();
                        }
                    }

                    // Show the total amount bold.
                    return "<b>" . number_format($totalAmount, 2). " CHF</b>";
                }
            ));
        }
    }
}
