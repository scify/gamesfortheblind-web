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
jimport( 'joomla.utilities.date' );

jimport('joomla.application.component.model');

class iJoomla_SeoModelZoo extends JModelLegacy{

	protected $context = 'com_ijoomla_seo.zoo';
	var $_total = 0;
	var $_pagination = null;

	function __construct () {
		parent::__construct();
		global $option;
		$app = JFactory::getApplication('administrator');
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
		/*if(JRequest::getVar("limitstart") == JRequest::getVar("old_limit")){
			JRequest::setVar("limitstart", "0");		
			$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
			$limitstart = $app->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int');
		}*/
		
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

	function existsZoo() {
		$db = JFactory::getDBO();
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$tables = $db->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "zoo_category", $tables)) { return false; } else { return true; }
	}	
	
	function getItems(){
		$config = new JConfig();
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->list_limit);
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$zoo_option = JRequest::getVar("zoo", "1");
		if($zoo_option == "1"){		
			$query = $this->getListQueryItems();			
		}
		else{
			$query = $this->getListQueryCategory();
		}
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->_total = count($result);
		
		$db->setQuery($query, $limistart, $limit);
		$db->query();
		$result	= $db->loadObjectList();			
		return $result;
	}
	
	function getCategs() {
		$db = JFactory::getDBO();
		$search = JRequest::getVar("zoo");
		// only query the db if we select "items"
		if ($search == "1") {
			$sql = "SELECT id, name FROM #__zoo_category";
			$db->setQuery($sql);
			return $db->loadObjectList();
		}
		return false;
	}	
	
	function getListQueryItems(){		
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
				
		$filter_missing = $app->getUserStateFromRequest($this->context.'.filter.missing', 'atype','any','string');
		$this->setState('filter.missing', $filter_missing,'string');
		
		$filter_state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state','','string');
		$this->setState('filter.published', $filter_state,'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search','','string');
		$this->setState('filter.search', $filter_search, 'string');
					
		$filter = JRequest::getVar("filter", "", "get");
		if($filter != ""){
			$filter_state = "";
			$filter_search = "";		
			$filter_missing = JRequest::getVar("value", "", "get");
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}	
							
		$where=" 1=1 ";		
				
		switch ($filter_missing){
			case "1":
				$where.= ' and l.params like (\'%"metadata.title":""%\') ';
				break;
			case "2":
				$where.= ' and l.params like (\'%"metadata.keywords":""%\') ';
				break;
			case "3":
				$where.= ' and l.params like (\'%"metadata.description":""%\') ';				
				break;
			case "4":
				$where.= ' and (l.params like (\'%"metadata.title":""%\') or l.params like (\'%"metadata.keywords":""%\') or l.params like (\'%"metadata.description":""%\') ) ';
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and l.state =1 ";
				break;
			case "2":
				$where.=" and l.state =0 ";
				break;				
			default:
				break;
		}
		
		if($filter_search != ""){ 
			$where.=" and (l.name like '%".addslashes($filter_search)."%' or l.params like '%\"".addslashes($filter_search)."\"%') ";
		}
		
		if ((JRequest::getInt('zoo') == 1) && JRequest::getInt('itemcats_zoo') > 0) {
			$where .= " AND k.category_id = '" . JRequest::getInt('itemcats_zoo') . "' ";
		}		
				
		$query = "
			SELECT l.`id`, l.`name`, l.`params`
			FROM #__zoo_item AS l
			LEFT JOIN `#__zoo_category_item` AS k ON l.id = k.item_id
			WHERE {$where}
			group by l.id";
		return $query;
	}
	
	function getListQueryCategory(){		
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_missing = $app->getUserStateFromRequest($this->context.'.filter.missing', 'atype','any','string');
		$this->setState('filter.missing', $filter_missing,'string');
		
		$filter_state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state','','string');
		$this->setState('filter.published', $filter_state,'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search','','string');
		$this->setState('filter.search', $filter_search, 'string');
					
		$filter = JRequest::getVar("filter", "", "get");
		if($filter != ""){
			$filter_state = "";
			$filter_search = "";
			$filter_missing = JRequest::getVar("value", "", "get");
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}	
							
		$where=" 1=1 ";		
					
		switch ($filter_missing){
			case "1":
				$where.= ' and c.params like (\'%"metadata.title":""%\') ';
				break;
			case "2":
				$where.= ' and c.params like (\'%"metadata.keywords":""%\') ';
				break;
			case "3":
				$where.= ' and c.params like (\'%"metadata.description":""%\') ';				
				break;
			case "4":
				$where.= ' and (c.params like (\'%"metadata.title":""%\') or c.params like (\'%"metadata.keywords":""%\') or c.params like (\'%"metadata.description":""%\') ) ';
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and c.published=1 ";
				break;
			case "2":
				$where.=" and c.published=0 ";
				break;				
			default:
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (mt.name like '%".addslashes($filter_search)."%' or mt.titletag like '%".addslashes($filter_search)."%' or mt.metakey like '%".addslashes($filter_search)."%' or mt.metadesc like '%".addslashes($filter_search)."%') ";
		}		
		$query->clear();
		$query->select('c.`id`, c.`name`, c.`params`');
		$query->from('#__zoo_category c');
		$query->where($where);
		return $query;
	}
		
	function save(){
		$zoo = JRequest::getVar("zoo", "");
		if($zoo == "1"){
			if($this->saveZooItems()){
				return true;
			}
			return false;
		}
		elseif($zoo == "2"){
			if($this->saveZooCategories()){
				return true;
			}
			return false;
		}
	}
	
	function saveZooItems(){		
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		$all_items_key_desc = $this->getAllItemsKeysDesc();
		
		foreach($ids as $key=>$id){
			$metatitle_string = trim($page_title[$id]);
			$metakey_string = trim($metakey[$id]);
			$metadesc_string = trim($metadesc[$id]);				
			
			$params = json_decode($all_items_key_desc[$id]["params"], true);
			$params["metadata.title"] = trim($metatitle_string);
			$params["metadata.keywords"] = trim($metakey_string);
			$params["metadata.description"] = trim($metadesc_string);
			$params = json_encode($params);
			
			$sql = "update #__zoo_item set `params`='".addslashes(trim($params))."' where `id`=".intval($id);				
			$db->setQuery($sql);
			$db->query();
		}
		return true;	
	}
	
	function saveZooCategories(){		
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$sql = "select title from #__ijseo_keys";
		$db->setQuery($sql);
		$db->query();
		$all_keys = $db->loadColumn();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		$all_categs_key_desc = $this->getAllCategsKeysDesc();
		
		foreach($ids as $key=>$id){			
			$metatitle_string = trim($page_title[$id]);
			$metakey_string = trim($metakey[$id]);
			$metadesc_string = trim($metadesc[$id]);				
			
			$params = json_decode($all_categs_key_desc[$id]["params"], true);
			$params["metadata.title"] = trim($metatitle_string);
			$params["metadata.keywords"] = trim($metakey_string);
			$params["metadata.description"] = trim($metadesc_string);
			$params = json_encode($params);
			
			$sql = "update #__zoo_category set `params`='".addslashes(trim($params))."' where `id`=".intval($id);				
			$db->setQuery($sql);
			$db->query();
		}
		return true;
	}
	
	function getKeysForCurrentCat($id){
		$db = JFactory::getDBO();
		$sql = "select keyword from #__ijseo_keys_id where type='zoo_cats' and type_id=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	
	function getAllItemsKeysDesc(){
		$db = JFactory::getDBO();
		$sql = "select id, params from #__zoo_item";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllCategsKeysDesc(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `params` from #__zoo_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllTitleKeysItems(){
		$db = JFactory::getDBO();
		$sql = "select title from #__ijseo_titlekeys where type='zoo_items'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	
	function getAllTitleKeysCategories(){
		$db = JFactory::getDBO();
		$sql = "select title from #__ijseo_titlekeys where type='zoo_cats'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	
	function getAllMetagsItems(){
		$db = JFactory::getDBO();
		$sql = "select id, titletag from #__ijseo_metags where mtype = 'zoo_items'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllMetagsCategories(){
		$db = JFactory::getDBO();
		$sql = "select id, titletag from #__ijseo_metags where mtype = 'zoo_cats'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllItemsName(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__zoo_item";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllCategoriesName(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__zoo_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllDesc(){
		$db = JFactory::getDBO();
		$zoo = JRequest::getVar("zoo", "0");
		$sql = "";
		$result = "";
		if($zoo == "1"){
			$sql = "select id, params from #__zoo_item";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
			if(isset($result) && count($result) > 0){
				foreach($result as $key=>$value){
					$params = json_decode($value["params"], true);
					$desc = $params["metadata.description"];
					$result[$key]["description"] = $desc;
				}
			}
		}
		elseif($zoo == "2"){
			$sql = "select `id`, `description` from #__zoo_category";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		return $result;
	}
	
	function getAllTitles(){
		$db = JFactory::getDBO();
		$zoo = JRequest::getVar("zoo", "0");
		$sql = "";
		$result = "";
		if($zoo == "1"){
			$sql = "select id, name as title from #__zoo_item";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		elseif($zoo == "2"){
			$sql = "select id, name as title from #__zoo_category";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		return $result;
	}
	
	function getAllTitlesTag(){
		$db = JFactory::getDBO();
		$zoo = JRequest::getVar("zoo", "0");
		$sql = "";
		$result = "";
		if($zoo == "1"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='zoo_items'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		elseif($zoo == "2"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='zoo_cats'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		return $result;
	}
	
	function getAllKeywords(){
		$db = JFactory::getDBO();
		$zoo = JRequest::getVar("zoo", "0");
		$sql = "";
		$result = "";
		if($zoo == "1"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='zoo_items'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		elseif($zoo == "2"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='zoo_cats'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		return $result;
	}
	
	function genMetadesc(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$all_desc = $this->getAllDesc();		
		$zoo= JRequest::getVar("zoo", "0");
		foreach($ids as $key=>$id){			
			$desc = "";
			if(isset($all_desc) && count($all_desc) > 0){
				$params = $this->getComponentParams();
				if($params->ijseo_type_desc == "Words"){
					$exclude_key = $params->exclude_key;
					$temp1 = "";
					foreach($exclude_key as $e_key=>$e_value){					
						$temp1 = str_replace($e_value, " ", strip_tags($all_desc[$id]["description"]));
					}
					$temp2 = explode(" ", $temp1);
					$delete = array_splice($temp2, 0, $params->ijseo_allow_no_desc);					
					$desc = implode(" ", $delete);					
				}
				else{
					if(isset($all_desc[$id]["description"])){
						$exclude_key = $params->exclude_key;
						$temp1 = "";
						foreach($exclude_key as $e_key=>$e_value){					
							$temp1 = str_replace($e_value, " ", strip_tags($all_desc[$id]["description"]));
							$all_desc[$id]["description"] = $temp1;
						}					
						$temp1 = str_replace($exclude_key, " ", strip_tags($all_desc[$id]["description"]));
						$desc = mb_substr($temp1, 0, $params->ijseo_allow_no_desc);
					}
				}
			}
			
			if($zoo == "1"){
				$sql = "select `params` from #__zoo_item where `id`=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$params = $db->loadColumn();
				$params = $params["0"];
				
				if(isset($params) && trim($params) != ""){
					$params = json_decode($params, true);
					$params["metadata.description"] = trim($desc);
					$params = json_encode($params);
					$sql = "update #__zoo_item set params = '".$params."' where id=".$id;
					$db->setQuery($sql);
					$db->query();
					
					$sql = "update #__ijseo_metags set metadesc = '".trim($desc)."' where mtype='zoo_items' and id=".$id;
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
			}
			elseif($zoo == "2"){
				$sql = "update #__ijseo_metags set metadesc = '".trim($desc)."' where mtype='zoo_cats' and id=".$id;
				$db->setQuery($sql);
				if(!$db->query()){
					return false;
				}
			}	
		}
		return true;
	}
	
	function copyArticleToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$all_titles = $this->getAllTitles();
		$zoo = JRequest::getVar("zoo", "0");
		$type = "";
		if($zoo == "1"){
			$type = "zoo_items";
		}
		elseif($zoo == "2"){
			$type = "zoo_cats";
		}
		foreach($ids as $key=>$id){
			$title = $all_titles[$id]["title"];
			$query->clear();
			$query->update('#__ijseo_metags');
			$query->set("`titletag`='".addslashes(trim($title))."'");
			$query->where('id='.$id." and mtype='".$type."'");
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function copyArticleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$all_titles = $this->getAllTitles();
		$zoo = JRequest::getVar("zoo", "0");
		$type = "";
		$table = "";
		$col_id = "";
		if($zoo == "1"){
			foreach($ids as $key=>$id){
				$title = $all_titles[$id]["title"];
				$query->clear();
				$query->update('#__ijseo_metags');
				$query->set("`metakey`='".addslashes(trim($title))."'");
				$query->where('id='.$id." and mtype='zoo_items'");
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
				
				$sql = "select params from #__zoo_item where id=".$id;
				$db->setQuery($sql);
				$db->query();
				$params = $db->loadColumn();
				$params = $params["0"];
				$params = json_decode($params, true);
				$params["metadata.keywords"] = trim($title);
				
				$params_encode = json_encode($params);
					
				$query->clear();
				$query->update('#__zoo_item');
				$query->set("`params`='".$params_encode."'");
				$query->where('id='.$id);
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
			}
		}
		elseif($zoo == "2"){
			foreach($ids as $key=>$id){
				$sql = "select keyword from #__ijseo_keys_id where type='zoo_cats' and type_id=".$id;
				$db->setQuery($sql);
				$db->query();
				$old_keys = $db->loadColumn();				
				
				$title = $all_titles[$id]["title"];
				$query->clear();
				$query->update('#__ijseo_metags');
				$query->set("`metakey`='".addslashes(trim($title))."'");
				$query->where('id='.$id." and mtype='zoo_cats'");
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
				
				$jnow = JFactory::getDate();
				$date_time = $jnow->toSQL();
				
				$new_keys = explode(",", $title);
				if(isset($new_keys) && is_array($new_keys) && count($new_keys) > 0){
					foreach($new_keys as $k_key=>$k_value){
						$sql = "insert into #__ijseo_keys_id(`keyword`, `type`, `type_id`) values ('".addslashes(trim($k_value))."', 'zoo_cats', ".$id.")";
						$db->setQuery($sql);
						$db->query();
						
						$sql = "insert into #__ijseo_keys(`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`) values ('".addslashes(trim($k_value))."', 0, 0, -1, '".$date_time."', 0)";						
						$db->setQuery($sql);
						$db->query();
					}
				}
				
				if(isset($old_keys) && is_array($old_keys) && count($old_keys) > 0){
					foreach($old_keys as $o_key=>$o_value){
						$sql = "delete from #__ijseo_keys where title = '".addslashes(trim($o_value))."'";
						$db->setQuery($sql);
						$db->query();
						
						$sql = "delete from #__ijseo_keys_id where keyword = '".addslashes(trim($o_value))."' and type='zoo_cats'";
						$db->setQuery($sql);
						$db->query();
					}
				}
			}
		}
		
		return true;
	}
	
	function copyTitleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$all_titles = $this->getAllTitlesTag();
		$zoo = JRequest::getVar("zoo", "0");
		$type = "";
		$table = "";
		$col_id = "";
		if($zoo == "1"){
			foreach($ids as $key=>$id){
				$title = $all_titles[$id]["title"];
				$query->clear();
				$query->update('#__ijseo_metags');
				$query->set("`metakey`='".addslashes(trim($title))."'");
				$query->where('id='.$id." and mtype='zoo_items'");
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
				
				$sql = "select params from #__zoo_item where id=".$id;
				$db->setQuery($sql);
				$db->query();
				$params = $db->loadColumn();
				$params = $params["0"];
				$params = json_decode($params, true);
				$params["metadata.keywords"] = trim($title);
				
				$params_encode = json_encode($params);
					
				$query->clear();
				$query->update('#__zoo_item');
				$query->set("`params`='".$params_encode."'");
				$query->where('id='.$id);
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
			}
		}
		elseif($zoo == "2"){
			foreach($ids as $key=>$id){
				$sql = "select keyword from #__ijseo_keys_id where type='zoo_cats' and type_id=".$id;
				$db->setQuery($sql);
				$db->query();
				$old_keys = $db->loadColumn();				
				
				$title = $all_titles[$id]["title"];
				$query->clear();
				$query->update('#__ijseo_metags');
				$query->set("`metakey`='".addslashes(trim($title))."'");
				$query->where('id='.$id." and mtype='zoo_cats'");
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
				
				$jnow = JFactory::getDate();
				$date_time = $jnow->toSQL();
				
				$new_keys = explode(",", $title);
				if(isset($new_keys) && is_array($new_keys) && count($new_keys) > 0){
					foreach($new_keys as $k_key=>$k_value){
						$sql = "insert into #__ijseo_keys_id(`keyword`, `type`, `type_id`) values ('".addslashes(trim($k_value))."', 'zoo_cats', ".$id.")";
						$db->setQuery($sql);
						$db->query();
						
						$sql = "insert into #__ijseo_keys(`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`) values ('".addslashes(trim($k_value))."', 0, 0, -1, '".$date_time."', 0)";						
						$db->setQuery($sql);
						$db->query();
					}
				}
				
				if(isset($old_keys) && is_array($old_keys) && count($old_keys) > 0){
					foreach($old_keys as $o_key=>$o_value){
						$sql = "delete from #__ijseo_keys where title = '".addslashes(trim($o_value))."'";
						$db->setQuery($sql);
						$db->query();
						
						$sql = "delete from #__ijseo_keys_id where keyword = '".addslashes(trim($o_value))."' and type='zoo_cats'";
						$db->setQuery($sql);
						$db->query();
					}
				}
			}
		}
		
		return true;
	}
	
	function copyKeyToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$zoo = JRequest::getVar("zoo", "0");
		$type = "";				
		if($zoo == "1"){
			$type = "zoo_items";
		}
		elseif($zoo == "2"){
			$type = "zoo_cats";
		}
		$all_keywords = $this->getAllKeywords();
		
		foreach($ids as $key=>$id){
			$key = $all_keywords[$id]["metakey"];
			$query->clear();
			$query->update('#__ijseo_metags');
			$query->set("`titletag`='".addslashes(trim($key))."'");
			$query->where('id='.$id." and mtype='".$type."'");		
			$db->setQuery($query);
			if(!$db->query()){
				die($db->getQuery());
				return false;
			}
		}
		return true;
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
		$result_string = $result_string["0"];
		$result = json_decode($result_string);
		return $result;
	}
}

?>