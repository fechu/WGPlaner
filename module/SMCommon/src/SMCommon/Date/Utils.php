<?php
/**
 * @file Utils.php
 * @date June 10, 2015
 * @author Sandro Meier
 */

namespace SMCommon\Date;

/**
 * Date utils.
 *
 * Contains helper functions which are useful when working with dates.
 */
class Utils
{

	const DATE_FORMAT_DATE_ONLY = 'd-m-Y';

	/**
	 * Returns the current month in an associative array of the form:
	 *  { startdate => DateTime, enddate => DateTime }
	 *
	 *  @param bool $formated Should the date be formatted as a string or should the DateTime
	 *  objects be returned?
	 */
	static public function currentMonth($formated = false) {
		$startdate = new \DateTime(date('Y-m-01'));
		$enddate = new \DateTime(date('Y-m-t'));

		if ($formated === true) {
			$startdate = $startdate->format(self::DATE_FORMAT_DATE_ONLY);
			$enddate = $enddate->format(self::DATE_FORMAT_DATE_ONLY);
		}

		return array(
			'startdate' => $startdate,
			'enddate' => $enddate
		);

		/*
		// Prepare links for intervals
		$queryThisMonth = array(
			'startdate' => date('01-m-Y'),
			'enddate' => date('t-m-Y')
		);
		$arguments = array(
			'accountid' => $this->account->getId(),
		);
		$urlThisMonth = $this->url('accounts/purchases', $arguments, array('query' => $queryThisMonth));

		$lastMonth = new \DateTime(date('Y-m-01'));
		$lastMonth->sub(new \DateInterval("P1D"));
		$queryLastMonth = array(
			'startdate' => $lastMonth->format('01-m-Y'),
			'enddate' => $lastMonth->format('t-m-Y'),
		);
		$urlLastMonth = $this->url('accounts/purchases', $arguments, array('query' => $queryLastMonth));
		*/
	}

    /**
	 * Returns the next month in an associative array of the form:
	 *  { startdate => DateTime, enddate => DateTime }
	 */
	static public function nextMonth() {

	}

	/**
	 * Returns the previous month in an associative array of the form:
	 *  { startdate => DateTime, enddate => DateTime }
	 */
	static public function previousMonth() {

	}
}