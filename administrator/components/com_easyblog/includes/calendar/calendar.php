<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

if(!function_exists('cal_days_in_month'))
{

}

class EasyBlogCalendar
{
	/**
	 * Given the date object, prepare the data
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function prepare($date = null)
	{
		if (is_null($date)) {
			$date = $this->getDateObject();
		}

		$calendar = new stdClass();

		// Here we generate the first day of the month
		$calendar->first_day = mktime(0, 0, 0, $date->month, 1, $date->year);

		// This gets us the month name
		$calendar->title = date('F', $calendar->first_day);

		// Here we find out what day of the week the first day of the month falls on
		$calendar->day_of_week = date('D', $calendar->first_day) ;

		// Previous month
		$calendar->previous	= strtotime('-1 month', $calendar->first_day);

		// Next month
		$calendar->next = strtotime('+1 month', $calendar->first_day);

		//Once we know what day of the week it falls on, we know how many blank days occure before it. If the first day of the week is a Sunday then it would be zero
		switch($calendar->day_of_week)
		{
			case "Sun":
				$calendar->blank = 0;
				break;
			case "Mon":
				$calendar->blank = 1;
				break;
			case "Tue":
				$calendar->blank = 2;
				break;
			case "Wed":
				$calendar->blank = 3;
				break;
			case "Thu":
				$calendar->blank = 4;
				break;
			case "Fri":
				$calendar->blank = 5;
				break;
			case "Sat":
				$calendar->blank = 6;
				break;
		}

		// Determine how many days are there in the current month
		$calendar->days_in_month = $this->getDaysInMonth($date->year, $date->month);

		return $calendar;
	}

	/**
	 * 
	 *
	 * @since	1.3
	 * @access	public
	 * @param	string
	 * @return	
	 */
	private function getDaysInMonth($year, $month)
	{
        return date('t', mktime(0, 0, 0, $month, 1, $year));
	}

	/**
	 * Retrieves the date object
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getDateObject($timestamp = '')
	{
		//This gets today's date
		if (!$timestamp) {
			// Get the current date
			$date = EB::date();

			// Get the timestamp
			$timestamp = $date->toUnix();
		}

		//This puts the day, month, and year in seperate variables
		$result = new stdClass();

		$result->day = date('d', $timestamp);
		$result->month = date('m', $timestamp);
		$result->year = date('Y', $timestamp);
		$result->unix = $timestamp;

		return $result;
	}
}
