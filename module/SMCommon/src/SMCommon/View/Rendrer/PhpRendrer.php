<?php
/**
 * @file PhpRendrer.php
 * @date Aug 29, 2013 
 * @author Sandro Meier
 */
 
namespace SMCommon\View\Renderer;

/**
 * This class is only used for typehinting in ViewScripts
 * 
 * @method Application\View\Helper\ObjectUrl	objectUrl()
 * @method string								pageHeader($title, $subtitle = NULL)
 * @method string								formatDate($date, $parts = Application\View\Helper\FormatDate::FORMAT_BOTH)
 * @method Application\View\Helper\Table		table()								
 */
class PhpRenderer extends \Zend\View\Renderer\PhpRenderer {}