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

class iJoomla_SeoModelNewredcategory extends JModelLegacy{
	
	function getValue(){
		$id = JRequest::getVar("id");
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`name`, `published`');
		$query->from('#__ijseo_redirect_category');
		$query->where('id='.$id);
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		return $result;
	}
	
	function save(){
		$all_dates = JRequest::get('post',JREQUEST_ALLOWRAW);
		$table = $this->getTable();
		$return = array();
		
		if(!$this->categoryExist(trim($all_dates["name"]))){		
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
		}
		else{
			$return["0"] = false;
			$return["1"] = 0;
		}
		return $return;
	}
	
	function categoryExist($name){
		$id = JRequest::getVar("id", "0");
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from('#__ijseo_redirect_category');
		$query->where("name='".addslashes(trim($name))."' and `id` <> ".intval($id));
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadColumn();
		$result = @$result["0"];
		if($result != "0"){
			return true;
		}
		return false;
	}
}

?>