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
class JFormFieldPositions extends JFormField
{
	/**
	 * Element name
	 *
	 * @access	protected
	 * @var		string
	 */
	protected $type = 'Positions';

	function getInput( ) {
		$db = JFactory::getDBO();
		$query = "SELECT DISTINCT position FROM #__modules ORDER BY position ASC";
		$db->setQuery($query);
		$groups = $db->loadObjectList();

		$groupHTML = array();
		if ($groups && count ($groups)) {
			foreach ($groups as $v=>$t){
				$groupHTML[] = JHTML::_('select.option', $t->position, $t->position);
			}
		}
		$lists = JHTML::_('select.genericlist', $groupHTML, $this->name.'[]', ' multiple="multiple"  size="10" ', 'value', 'text', $this->value);

		return $lists.'<div style="height:150px;"></div>';
	}
} 