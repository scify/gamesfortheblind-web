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

jimport('joomla.application.component.model');

class iJoomla_SeoModelNewredirect extends JModelLegacy{
	
	function getValue(){
		$id = JRequest::getVar("id");
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('ij.*');
		$query->from('#__ijseo as ij');
		$query->where('ij.id='.$id);
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		return $result;
	}
	
	function getAllCategories(){		
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`name`, `id`');
		$query->from('#__ijseo_redirect_category');
		$query->where('published=1');
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		return $result;		
	}
	
	function save(){
		$all_dates = JRequest::get('post',JREQUEST_ALLOWRAW);		
		$table = $this->getTable();
		$return = array();
		
		if (!$table->bind($all_dates)) {
			echo $this->_db->getErrorMsg(); exit;
			$this->setError($this->_db->getErrorMsg());
			$return["0"] = false;
		}
		if (!$table->check()) {
			echo $this->_db->getErrorMsg(); exit;		
			$this->setError($this->_db->getErrorMsg());
			$return["0"] = false;
		}
		if (!$table->store()) {
			echo $this->_db->getErrorMsg(); exit;
			$this->setError( $this->_db->getErrorMsg() );
			$return["0"] = false;
		}
		$return["0"] = true;
		$return["1"] = $table->id;
		return $return; 
	}
}

?>