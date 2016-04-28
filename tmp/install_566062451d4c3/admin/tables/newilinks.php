<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined('_JEXEC') or die('Restricted access');
jimport('joomla.database.table');

class TableNewilinks extends JTable{

	var $id = null;
	var $name = null;
    var $title = null;
	var $published = null;	
	var $type = null;
	var $location = null;
	var $target = null;
	var $articleId = null;
	var $location2 = null;
	var $menu_type = null;
	var $loc_id = null;
	var $location1 = null;
	var $catid = null;
	var $other_phrases = null;
    var $include_in = null;
    var $activate_for_some = null;
	var $params = null;
	
	function TableNewilinks(&$db) {
		parent::__construct('#__ijseo_ilinks', 'id', $db);
	}
}

?>

