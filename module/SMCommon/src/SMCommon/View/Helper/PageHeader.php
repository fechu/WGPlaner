<?php

namespace SMCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;

/**
 * Create a Twitter Bootstrap styled Page header and returns it.
 **/
class PageHeader extends AbstractHelper
{
    /**
     * Renders a page header
     */
    public function __invoke($title, $subtitle = NULL) {
		$output = '<div class="page-header">';
		$output .= '<h1>' . $title;
		if ($subtitle) {
		    $output .= '<small>&nbsp;' . $subtitle . '</small>';
		}
		$output .= '</h1>';
		$output .= '</div>';
		
		return $output;
    }
}