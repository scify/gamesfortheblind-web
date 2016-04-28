<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Joomla!
 * @subpackage	JUX_MegaMenu_Framework
 * @copyright	Copyright (C) 2008 - 2013 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3, SEE LICENSE.php
 */

// no direct access
defined('_JEXEC') or die('Restricted access');

/**
 * Radio List Element
 *
 * @since      Class available since Release 1.2.0
 */
class JFormFieldModules extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Modules';

	function getInput() {
		$db = JFactory::getDBO();
		$query = "SELECT e.extension_id, a.id, a.title, a.note, a.position, a.module, a.language,a.checked_out, a.checked_out_time, a.published, a.access, a.ordering, a.publish_up, a.publish_down,l.title AS language_title,uc.name AS editor,ag.title AS access_level,MIN(mm.menuid) AS pages,e.name AS name
					FROM `#__modules` AS a
					LEFT JOIN `#__languages` AS l ON l.lang_code = a.language
					LEFT JOIN #__users AS uc ON uc.id=a.checked_out
					LEFT JOIN #__viewlevels AS ag ON ag.id = a.access
					LEFT JOIN #__modules_menu AS mm ON mm.moduleid = a.id
					LEFT JOIN #__extensions AS e ON e.element = a.module
					WHERE (a.published IN (0, 1)) AND a.client_id = 0
					GROUP BY a.id";
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		$groupHTML = array();
		if ($groups && count ($groups)) {
			foreach ($groups as $v=>$t){
				$groupHTML[] = JHTML::_('select.option', $t->id, $t->title);
			}
		}
		$lists = JHTML::_('select.genericlist', $groupHTML, "{$this->name}[]", ' multiple="multiple"  size="10" ', 'value', 'text', $this->value);

		return $lists.'<div style="height:150px;"></div>';
	}
} 