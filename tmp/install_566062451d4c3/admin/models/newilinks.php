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

class iJoomla_SeoModelNewilinks extends JModelLegacy{
	
	function getValue(){
		$id = JRequest::getVar("id");
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('*');
		$query->from('#__ijseo_ilinks');
		$query->where('id='.$id);
        $query = "SELECT * FROM #__ijseo_ilinks WHERE id = ". $id;
		$db->setQuery($query);
		$result	= $db->loadObjectList();
        $sql = "SELECT a.id, a.title
                    FROM `#__ijseo_ilinks_articles` AS ia
                    LEFT JOIN `#__content` AS a ON ia.article_id = a.id
                    WHERE ia.ilink_id = {$id}
                    ";
        $db->setQuery($sql);
        $result[0]->articles = $db->loadObjectList();
        
		return $result;
	}
	
	function getAllCategories(){		
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`name`, `id`');
		$query->from('#__ijseo_ilinks_category');
		$query->where('published=1');
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		return $result;		
	}
	
	function getAllMenus(){
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`menutype`, `title`');
		$query->from('#__menu_types');
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		return $result;	
	}
	
	function getAllMenuItems($menu_type){
		$db	= JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('`id`, `title`');
		$query->from('#__menu');
		$query->where("menutype='".addslashes(trim($menu_type))."'");
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		return $result;	
	}
	
	function save(){
		$db = JFactory::getDBO();
		$all_dates = JRequest::get('post',JREQUEST_ALLOWRAW);		
		$table = $this->getTable();
		$return = array();
        
		$menu_type = JRequest::getVar("menu_type", "");
		$loc_id = JRequest::getVar("loc_id", "");
		
		if($menu_type != "" && $loc_id != ""){
			$sql = "select m.title as menu_title, mt.title as menu_type_title from #__menu m, #__menu_types mt where mt.menutype='".trim($menu_type)."' and m.id=".intval($loc_id);
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
			$all_dates["location"] = $result["0"][menu_type_title]."->".$result["0"][menu_title];
		}
		
		$params = array();
		$enable_jomsocial = $all_dates["enable_jomsocial"];
		$enable_zoo = $all_dates["enable_zoo"];
		$enable_k2 = $all_dates["enable_k2"];
		$enable_kunena = $all_dates["enable_kunena"];
		$enable_easyblog = $all_dates["enable_easyblog"];
		
		$params["enable_jomsocial"] = $enable_jomsocial;
		$params["enable_zoo"] = $enable_zoo;
		$params["enable_k2"] = $enable_k2;
		$params["enable_kunena"] = $enable_kunena;
		$params["enable_easyblog"] = $enable_easyblog;
		
		$all_dates["params"] = json_encode($params);
		
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

        $sqlz = array();
        $sql = "DELETE FROM `#__ijseo_ilinks_articles` WHERE `ilink_id` = '" . $table->id . "' ";
        $db->setQuery($sql);
        $db->query();
        if (is_array($all_dates["selected_articles"]) && ($all_dates["include_in"] == "1")) {
            foreach ($all_dates["selected_articles"] as $element) {
                $sql = "INSERT INTO `#__ijseo_ilinks_articles` (`ilink_id`, `article_id`) VALUES ('" . $table->id . "', '" . $element . "');";
                $sqlz[] = $sql;
                $db->setQuery($sql);
                $db->query();
            }
        }
		return $return; 
	}
}

?>