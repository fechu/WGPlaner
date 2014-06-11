<?php

namespace SMCommon\View\Helper;

use Zend\View\Helper\AbstractHelper;

class FormatDate extends AbstractHelper
{
	/**
	 * The configuration of the ViewHelper.
	 * View the file config/autoload/formatdate.global.php for a detailed description
	 * of available options
	 */
	protected $config;

	const FORMAT_TIME = 'time';
	const FORMAT_DATE = 'date';
	const FORMAT_BOTH = 'both';

	/**
	 * @param $config The Configuration.
	 */
	public function __construct($config)
	{
		$this->config = $config;
	}

	/**
	 * @param DateTime $date
	 * @param string $parts One of the constants FORMAT_TIME, FORMAT_DATE, FORMAT_BOTH
	 * @return string The formatted date
	 */
	public function __invoke($date, $parts = self::FORMAT_BOTH)
	{
		return $this->formatDate($date, $parts);
	}

	public function formatDate($date, $parts = self::FORMAT_BOTH)
	{
		if (!($date instanceof \DateTime)) {
			return '';
		}

		$oldTimezone = $date->getTimezone();
		$displayTimeZone = new \DateTimeZone('Europe/Zurich');
		$date->setTimezone($displayTimeZone);

		$formattedDate = '';

		if ($parts == self::FORMAT_DATE || $parts == self::FORMAT_BOTH) {
			// Add the date part
			$formattedDate .= $date->format($this->getDateFormat());
		}

		if ($parts == self::FORMAT_TIME || $parts == self::FORMAT_BOTH) {

			if (strlen($formattedDate) > 0) {
				$formattedDate .= " ";
			}

			// Add the time part
			$formattedDate .= $date->format($this->getTimeFormat());
		}

		// Restore the timezon
		if ($oldTimezone) {
			$date->setTimezone($oldTimezone);
		}
		else {
			$date->setTimezone(NULL);
		}

		return $formattedDate;
	}

	/**
	 * Formats a date range. Have a look at the next few examples which should give you an idea
	 * about how it works
	 *
	 * startDate: 3.7.1992
	 * endDate: 4.7.1992
	 * dateFormat:FORMAT_BOTH
	 * Output: 3.7.1992 - 4.7.1992
	 *
	 * @return A string containing the formatted date range.
	 */
    public function formatRange($startDate, $endDate, $dateFormat = self::FORMAT_BOTH)
    {
		$result = '';

		// Add start date
		if ($startDate !== NULL) {
			$result .= $this->formatDate($startDate, $dateFormat);

            // Add the dash
			if ($endDate) {
				$result .= " - ";
			}
		}

		// Add end date
		if ($endDate !== NULL) {
			$result .= $this->formatDate($endDate, $dateFormat);
		}

		return $result;
    }

	/**
	 * Returns the date format that is used by the view helper
	 * Defaults to NULL if the date-format was not set in the config.
	 * @return string The format that describes how to format the date
	 */
	public function getDateFormat()
	{
		return isset($this->config['date-format']) ? $this->config['date-format'] : NULL;
	}

	/**
	 * Returns the time format that is used by the view helper.
	 * Defaults to NULL if the time-format was not set in the config.
	 * @return string The format that describes how to format the time
	 */
	public function getTimeFormat()
	{
		return isset($this->config['time-format']) ? $this->config['time-format'] : NULL;
	}
}