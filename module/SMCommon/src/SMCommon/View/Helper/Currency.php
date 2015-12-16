<?php

namespace SMCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;

class Currency extends AbstractHelper
{
	const COLOR_POSITIVE = 'green';
	const COLOR_NEGATIVE = 'red';

	/**
	 * @param float $amount The amount to foramt
	 * @param bool $color A color if the amount should be colored. Otherwise false.
	 * @return string The formatted amount.
	 */
	public function __invoke($amount, $color=false)
	{
		$amountString = number_format($amount, 2) . ' CHF';
		if ($color !== false) {
			return '<span style="color: '. $color .'">' . $amountString . '</span>';
		}
		else {
			return $amountString;
		}
	}
}