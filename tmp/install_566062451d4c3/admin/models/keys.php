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
jimport( 'joomla.utilities.date' );

class iJoomla_SeoModelKeys extends JModelLegacy{

	protected $context = 'com_ijoomla_seo.keys';
	var $_total = 0;
	var $_pagination = null;

	function __construct () {
		parent::__construct();
		global $option;
		$app = JFactory::getApplication('administrator');
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
		if(JRequest::getVar("limitstart") == JRequest::getVar("old_limit")){
			JRequest::setVar("limitstart", "0");		
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int');
		}
		
		$this->setState('limit', $limit); // Set the limit variable for query later on
		$this->setState('limitstart', $limitstart);
	}

	function getPagination(){
		// Lets load the content if it doesn't already exist
		if (empty($this->_pagination))	{
			jimport('joomla.html.pagination');
			if (!$this->_total) $this->getItems();
			$this->_pagination = new JPagination( $this->_total, $this->getState('limitstart'), $this->getState('limit') );
		}
		return $this->_pagination;
	}
	
	function getItems(){		
		$config = new JConfig();
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->list_limit);
		$params 	= $this->getComponentParams();
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		
		$query = $this->getListQuery();
		
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->_total=count($result);
		
		$db->setQuery($query,$limistart,$limit);
		$db->query();
		$result	= $db->loadAssocList();	
		
		$col = JRequest::getVar("col", "");

		if($col != ""){
			$sort_1 = JRequest::getVar("sort1", "");
			$sort_2 = JRequest::getVar("sort2", "");
			$sort_3 = JRequest::getVar("sort3", "");
			$sort_4 = JRequest::getVar("sort4", "");
			$sort_5 = JRequest::getVar("sort5", "");
			$sort_6 = JRequest::getVar("sort6", "");
			$sort_type = "asc";
			
			switch($col){
				case "title":
					$sort_type = $sort_1;
					break;
				case "rank":
					$sort_type = $sort_2;
					break;
				case "rchange":
					$sort_type = $sort_3;
					break;
				case "checkdate":
					$sort_type = $sort_4;
					break;
				case "sticky":
					$sort_type = $sort_5;
					break;	
			}			
			$sortArray = array();
			
			foreach($result as $res){
				foreach($res as $key=>$value){
					if(!isset($sortArray[$key])){
						$sortArray[$key] = array();
					}
					$sortArray[$key][] = $value;
				}
			}
			$orderby = $col;
			if($sort_type == "asc"){
				@array_multisort($sortArray[$orderby], SORT_ASC, $result);	
			}
			else{
				@array_multisort($sortArray[$orderby], SORT_DESC, $result);
			}
			
			return $result;	
		}
		else{				
			return $result;
		}
	}
	
	function getListQuery(){		
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_sticky = $app->getUserStateFromRequest($this->context.'.filter.sticky', 'sticky','','string');
		$this->setState('filter.sticky', $filter_sticky, 'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search','','string');
		$this->setState('filter.search', $filter_search, 'string');
		
		$where = " 1=1 ";
				
		if($filter_sticky != "" && $filter_sticky != "0"){
			$where.= " and k.sticky=1 ";
		}
		
		if(trim($filter_search) != ""){
			$where.= " and k.`title` like '%".addslashes(trim($filter_search))."%'";
		}
		
		$cpanl = Jrequest::getVar("cpanl", "");
		if($cpanl != ""){
			if($cpanl == "up"){
				$where.= " and k.`mode`=1";
			}
			elseif($cpanl == "down"){
				$where.= " and k.`mode`=0";
			}
			elseif($cpanl == "same"){
				$where.= " and k.`mode`=-1";
			}
		}
		
		$query->select('*');
		$query->from('#__ijseo_keys k');
		$query->where($where);
        $query->order("CASE k.rank WHEN 0 THEN 9999 ELSE k.rank END");
        
		return $query;
	}
	
	function getComponentParams(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('params');
		$query->from('#__ijseo_config');
		$db->setQuery($query);
		$db->query();
		$result_string = $db->loadColumn();
		$result_string = @$result_string["0"];
		$result = json_decode($result_string);
		return $result;
	}
	
	function getStickyUnsticky(){
		$params = $this->getComponentParams();
		$ids = JRequest::getVar("cid", array());		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$value = "";
		$task = JRequest::getVar("task", "");
		if($task == "sticky"){
			$value = "1";
		}
		else{
			$value = "0";
		}
		foreach($ids as $key=>$id){		
			$query->clear();
			$query->update('#__ijseo_keys');
			$query->set("sticky=".$value);
			$query->where('id='.$id);			
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function save(){
		$db = JFactory::getDBO();
		$keys_title = JRequest::getVar("keys_title", "");
		
		if(trim($keys_title) != ""){
			$keys_title = explode(",", $keys_title);
			if(is_array($keys_title) && count($keys_title) > 0){
				
				$sql = "select count(*) from #__ijseo_keys";
				$db->setQuery($sql);
				$db->query();
				$nr_count = $db->loadColumn();
				$nr_count = @$nr_count["0"];
				
				foreach($keys_title as $key=>$title){
					if(trim($title) != ""){
						if($nr_count == 5){
							return true;
						}
						
						$sql = "SELECT count(*) FROM `#__ijseo_keys` WHERE `title`='".addslashes(trim($title))."'";
						$db->setQuery($sql);
						$db->query();
						$count = $db->loadColumn();
						$count = $count["0"];
						if(intval($count) == 0){
							$sql = "insert into #__ijseo_keys (`title`, `checkdate`) values ('".addslashes(trim($title))."', '".date("Y-m-d H:i:s")."')";
							$db->setQuery($sql);
							if(!$db->query()){
								return false;
							}
						}
						$nr_count ++;
					}
				}
			}
		}
		return true;
	}
	
	function delete(){
		$db = JFactory::getDBO();
		$cid = JRequest::getVar("cid", array(), "post");
		if(isset($cid) && is_array($cid) && count($cid) > 0){
			foreach($cid as $key=>$id){
				$sql = "delete from #__ijseo_keys where `id`=".intval($id);
				$db->setQuery($sql);
				if(!$db->query()){
					return false;
				}
			}
		}
		return true;
	}
}

?>