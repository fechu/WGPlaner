<?php
/**
 * @file PurchaseTableTest.php
 * @date July 9, 2014
 * @author Sandro Meier
 */

namespace ApplicationTest\View\Helper;

use Application\Entity\Purchase;
use Application\View\Helper\PurchaseTable;

class PurchaseTableTest extends \PHPUnit_Framework_TestCase
{
    protected $purchaseTable;

    public function setUp()
    {
        $this->purchaseTable = new PurchaseTable();
    }

    public function testPurchaseTableIsCreatedSuccessfully()
    {
        $this->assertNotNull($this->purchaseTable, 'Purchase table is not created!');
    }
}
