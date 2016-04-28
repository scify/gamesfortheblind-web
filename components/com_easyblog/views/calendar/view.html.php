<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewCalendar extends EasyBlogView
{
	/**
	 * Displays the calendar layout
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function display($tpl = null)
	{
		// Set the pathway
		$this->setPathway('COM_EASYBLOG_CALENDAR_BREADCRUMB');

		// Get the year and month if it's defined in the menu
		$year = $this->theme->params->get('calendar_year', 0);
		$month = $this->theme->params->get('calendar_month', 0);

		// Perhaps the year and month are being passed as query strings
		$year = $this->input->get('year', $year, 'default');
		$month = $this->input->get('month', $month, 'default');
		$day = $this->input->get('day', '01', 'default');
		$category = $this->input->get('category', array(), 'array');
		$category = implode(",",$category);

		$this->set('category', $category);

		// Get the Itemid
		$itemId = $this->input->get('Itemid', 0, 'int');

		// Try to generate timestamp if there's year and month provided
		$timestamp = '';

		if ($year && $month) {
			$timestamp = strtotime($year . '-' . $month . '-' . $day);
		}

		$this->set('timestamp', $timestamp);

		// get the calendar date.
		$date = EB::calendar()->getDateObject($timestamp);

		// meta, too late to add new meta id so we 'trick' the system to use the custom description.
		EB::setMeta('0', META_TYPE_VIEW, JText::_('COM_EASYBLOG_CALENDAR_PAGE_TITLE') . ' ' . $date->year . ', ' . $date->month);

		// Set the page title
		$title = EB::getPageTitle(JText::_('COM_EASYBLOG_CALENDAR_PAGE_TITLE') . ' ' . $date->year . ', ' . $date->month);

		parent::setPageTitle($title, false, $this->config->get('main_pagetitle_autoappend'));

		return parent::display('blogs/calendar/default');
	}
}
