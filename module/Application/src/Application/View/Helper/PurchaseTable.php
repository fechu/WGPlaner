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
            'headTitle'     => 'Date',
            'dataMethod'    => function (Purchase $purchase) {
                return $this->view->formatDate($purchase->getDate(), FormatDate::FORMAT_DATE);
            }
        ));

        // Store Column
        $this->addColumn(array('Store', 'getStore'));
    }

    /**
     * Add a column for each user in the $users array.
     *
     * The column contains the amount of the purchase if the purchase belongs to that users
     * column. Otherwise it is just empty.
     *
     * @param $users    An array containing users. For each user a column will be added.
     */
    public function addUserColumns($users)
    {
        // Add a column for each user
        foreach ($users as $user) {
            $this->addColumn(array(
                'headTitle' 	=> $user->getFullname(),
                'dataMethod'	=> function(Purchase $purchase) use ($user) {
                    if ($purchase->getUser() == $user) {
                        $currency = $purchase->getAccount()->getCurrency();
                        $amount = $this->view->currency($purchase->getAmount(), false, $currency);
                        return $amount;
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
                    if (count($purchases) > 0) {
                        /** @var Purchase $purchase */
                        $purchase = $purchases[0];
                        $currency = $purchase->getAccount()->getCurrency();
                        return "<b>" . $this->view->currency($totalAmount, false, $currency);
                    }
                    else {
                        return "<b>-</b>";
                    }
                }
            ));
        }
    }
}
