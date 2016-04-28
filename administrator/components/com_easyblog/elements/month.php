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

require_once(__DIR__ . '/abstract.php');

class JFormFieldMonth extends EasyBlogFormField
{
	protected $type = 'Month';

	/**
	 * Displays the category selection form
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */	
	protected function getInput()
	{
		$theme = EB::template();
		$theme->set('id', $this->id);
		$theme->set('name', $this->name);
		$theme->set('value', $this->value);

		$output = $theme->output('admin/elements/month');

		return $output;
	}
}
