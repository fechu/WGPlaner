<?php

namespace SMCommonTest\View\Helper;

use SMCommon\View\Helper\PageHeader;
use PHPUnit_Framework_TestCase;

class PageHeaderTest extends PHPUnit_Framework_TestCase
{
    
    protected $pageHeader;
    
    public function setUp()
    {
	$this->pageHeader = new PageHeader();
    }
    
    public function testHasCorrectCSSClass()
    {
	$result = $this->pageHeader->__invoke("Title");
	
	$this->assertContains('class="page-header"', $result);
    }
    
    public function testTitleIsRendered()
    {
	$result = $this->pageHeader->__invoke('Title');
	
	$this->assertContains('Title', $result);
    }
    
    public function testSubtitleIsRendered()
    {
	$result = $this->pageHeader->__invoke('Title', 'Subtitle');
	
	$this->assertContains('Subtitle', $result);
    }
}