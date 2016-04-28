<?php
/**
 * @version		$Id$
 * @author		JoomlaUX!
 * @package		Joomla.Site
 * @subpackage	mod_jux_megamenu
 * @copyright	Copyright (C) 2008 - 2013 by JoomlaUX. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

if(!class_exists('JFormFieldList')) {
	JFormHelper::loadFieldClass('list');
}
jimport('joomla.filesystem.folder');

/**
 * Form Field class for the Joomla Framework.
 *
 * @package		Joomla.Framework
 * @subpackage	Form
 * @since		1.6
 */
class JFormFieldLayout extends JFormFieldList
{
	/**
	 * The form field type.
	 *
	 * @var		string
	 * @since	1.6
	 */
	public $type = 'layout';

	/**
	 * fetch Element
	 */
	function getInput(){
		$db = JFactory::getDBO();

		$files	= JFolder::files(JPATH_ROOT.'/modules/mod_jux_megamenu/assets/css/style');
		$options = array ();
		foreach ($files as $file) {
			// check if is a CSS file
			if (substr($file, -4) == '.css') {
				$filename	= substr($file, 0, -4);
				$options[] = JHTML::_('select.option', $filename, $filename);
			}
		}

		return JHTML::_('select.genericlist', $options, $this->name, 'class="inputbox"', 'value', 'text', $this->value, $this->id);
	}
}