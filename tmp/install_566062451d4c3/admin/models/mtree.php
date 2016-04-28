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

class iJoomla_SeoModelMtree extends JModelLegacy{

	var $_pagination = null;
	protected $context = 'com_ijoomla_seo.mtree';
	var $_total = 0;

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
	
	function getItems(){		
		$config = new JConfig();
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->list_limit);
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$mtree_option = JRequest::getVar("mtree", "1");
		if($mtree_option == "1"){
			$this->sincronizeListingsWithSeo();		
			$query = $this->getListQueryListing();			
		}
		else{
			$this->sincronizeCategoriesWithSeo();
			$query = $this->getListQueryCategory();
		}
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->_total = count($result);
		
		$db->setQuery($query,$limistart,$limit);
		$db->query();
		$result	= $db->loadObjectList();			
		return $result;
	}
	
	function getListQueryListing(){		
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_mtreecat = $app->getUserStateFromRequest($this->context.'.filter.mtreecat', 'filter_mtreecat','-1','string');
		$this->setState('filter.mtreecat', $filter_mtreecat, 'string');
		
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
			$filter_mtreecat = "-1";			
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.mtreecat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}	
							
		$where=" 1=1 ";		
		
		if($filter_mtreecat != "-1"){
			$where .= " and l.link_id in (select link_id from #__mt_cl where cat_id=".intval($filter_mtreecat).") ";
		}
					
		switch ($filter_missing){
			case "1":
				$where.= " and mt.titletag = '' ";
				break;
			case "2":
				$where.= " and l.metakey = '' ";
				break;
			case "3":
				$where.= " and l.metadesc = ''";
				break;
			case "4":
				$where.= " and ( l.metakey = '' or l.metadesc = '') ";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and l.link_published=1 ";
				break;
			case "2":
				$where.=" and l.link_published=0 ";
				break;				
			default:
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (l.link_name like '%".addslashes($filter_search)."%' or l.metakey like '%".addslashes($filter_search)."%' or l.metadesc like '%".addslashes($filter_search)."%') ";
		}		
		$query->clear();
		$query->select('l.link_id, l.link_name, l.metakey, l.metadesc, mt.titletag');
		$query->from('#__mt_links l');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = l.link_id and mt.mtype='mt_list'");
		$query->where($where);
		return $query;
	}
	
	function existsMtree() {
		$db = JFactory::getDBO();
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$tables = $db->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "mt_cats", $tables)) { return false; } else { return true; }
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
				$where.= " and mt.titletag = '' ";
				break;
			case "2":
				$where.= " and c.metakey = '' ";
				break;
			case "3":
				$where.= " and c.metadesc = ''";
				break;
			case "4":
				$where.= " and (c.metakey = '' or c.metadesc = '') ";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and c.cat_published=1 ";
				break;
			case "2":
				$where.=" and c.cat_published=0 ";
				break;				
			default:
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (c.cat_name like '%".addslashes($filter_search)."%' or c.metakey like '%".addslashes($filter_search)."%' or c.metadesc like '%".addslashes($filter_search)."%') ";
		}		
		$query->clear();
		$query->select('c.cat_id, c.cat_name, c.metakey, c.metadesc, mt.titletag');
		$query->from('#__mt_cats c');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = c.cat_id and mt.mtype='mt_cat'");
		$query->where($where);		
		return $query;
	}
	
	function getMtreeCategories(){
		$db = JFactory::getDBO();
		$sql = "select cat_id, cat_name from #__mt_cats where cat_published=1 and cat_approved=1";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function sincronizeListingsWithSeo(){
		$db = JFactory::getDBO();
		$sql = "select link_id, link_name, metakey, metadesc from #__mt_links";
		$db->setQuery($sql);
		$db->query();
		$all_links_from_mtree = $db->loadAssocList("link_id"); // all values from mtree links and meta datas
		$all_links_name_from_mtree = $db->loadColumn(1); // all values from mtree links and meta datas
		$links_from_mtree1 = $db->loadAssocList(); // all values from mtree but not with meta datas	
		$links_from_mtree2 = array(); //$db->loadColumn(2); // all values from mtree but not with meta datas		
		$links_from_mtree = array();
		$links_name_from_mtree = array(); // all names from mtree but not with meta datas
		
		//separate links keywords by ,
		if(isset($links_from_mtree1) && is_array($links_from_mtree1) && count($links_from_mtree1) > 0){
			foreach($links_from_mtree1 as $key=>$value){
				if(trim($value["metakey"]) != ""){
					$temp = explode(",", trim($value["metakey"]));
					if(count($temp) > 1){
						$i = 0;
						foreach($temp as $key2=>$value2){
							if(trim($value2) != ""){
								$links_from_mtree[$value["link_id"]."-".$i++] = trim($value2);
							}
						}						
					}
					else{
						$links_from_mtree[$value["link_id"]."-0"] = trim($value["metakey"]);
					}
					$links_from_mtree2[$value["link_id"]."-0"] = trim($value["metakey"]);
				}
			}
		}
		
		//separate links name by separators
		$component_params = $this->getComponentParams();
		if(isset($component_params) && isset($component_params->delimiters) && trim($component_params->delimiters) != ""){
			$delimiters = $component_params->delimiters;
			$delimiters = str_split(trim($delimiters));
			if(isset($all_links_from_mtree) && is_array($all_links_from_mtree) && count($all_links_from_mtree) > 0){
				foreach($all_links_from_mtree as $key=>$value){
					$temp = str_replace($delimiters, "*****", $value["link_name"]);
					$temp_array = explode("*****", $temp);
					$i = 0;
					if(count($temp_array) > 1){						
						foreach($temp_array as $key_temp=>$value_temp){
							if(trim($value_temp) != ""){
								$links_name_from_mtree[$value["link_id"]."-".$i++] = $value_temp;
							}
						}
						unset($links_name_from_mtree[$key]);
					}
					else{
						$links_name_from_mtree[$value["link_id"]."-".$i++] = $value["link_name"];
					}					
				}
			}
		}		
		//------------------------------------
		
		$sql = "select id, title from #__ijseo_keys";
		$db->setQuery($sql);
		$db->query();
		$keys_from_seo = $db->loadColumn(1); // all keywords from seo
		
		$sql = "select keyword, type_id from #__ijseo_keys_id where type = 'mt_list'";
		$db->setQuery($sql);
		$db->query();
		$keys_id_from_seo = $db->loadColumn(0); // all key_id from seo
		
		$sql = "select id, metakey from #__ijseo_metags where mtype = 'mt_list'";
		$db->setQuery($sql);
		$db->query();
		$metags_from_seo = $db->loadColumn(1); // all metags from seo
		$metags_from_seo1 = $db->loadColumn(0); // all id metags from seo
		
		$sql = "select name from #__ijseo_metags where mtype = 'mt_list'";		
		$db->setQuery($sql);
		$db->query();
		$mtags_name_from_seo = $db->loadColumn(); // all titlekeys from seo
		
		$sql = "select title, joomla_id from #__ijseo_titlekeys where type = 'mt_list'";
		$db->setQuery($sql);
		$db->query();
		$titlekeys_from_seo = $db->loadColumn(0); // all titlekeys from seo								
		
		//differences for insert new rows
		$links_keys = array_diff($links_from_mtree, $keys_from_seo); // difference bettwin links and keywords
		$links_key_id = array_diff($links_from_mtree, $keys_id_from_seo); // difference bettwin links and key_id
		$links_metags = array_diff($links_from_mtree2, $metags_from_seo); // difference bettwin links and key_id
		$links_name_titlekeys = array_diff($links_name_from_mtree, $titlekeys_from_seo); // difference bettwin links_name and titlekeys
			
		$jnow = JFactory::getDate();
		$date_time = $jnow->toSQL();
		
		//insert all new keywords in seo tables
		if(isset($links_keys) && is_array($links_keys) && count($links_keys) > 0){
			foreach($links_keys as $key=>$value){
				if(trim($value) != ""){
					$sql = "insert into #__ijseo_keys(`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`) values ('".addslashes(trim($value))."', 0, 0, -1, '".$date_time."', 0)";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
		
		//insert all new keys_id in seo tables
		if(isset($links_key_id) && is_array($links_key_id) && count($links_key_id) > 0 && isset($all_links_from_mtree) && count($all_links_from_mtree) > 0){
			foreach($links_key_id as $key=>$value){
				if(trim($value) != ""){
					$temp = explode("-", $key);
					$id = $temp["0"];
					$sql = "insert into #__ijseo_keys_id (`keyword`, `type`, `type_id`) values ('".addslashes(trim($value))."', 'mt_list', ".intval($id).")";					
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
		
		//insert all new metags in seo tables
		if(isset($links_metags) && is_array($links_metags) && count($links_metags) > 0 && isset($all_links_from_mtree) && count($all_links_from_mtree) > 0){
			foreach($links_metags as $key=>$value){
				$temp = explode("-", $key);
				$id = intval($temp["0"]);
				if(in_array($id, $metags_from_seo1)){
					$sql = "update #__ijseo_metags set metakey='".addslashes(trim($all_links_from_mtree[$id]["metakey"]))."', metadesc='".addslashes(trim($all_links_from_mtree[$id]["metadesc"]))."' where mtype='mt_list' and id=".$id;
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `name`, `titletag`, `metakey`, `metadesc`) values ('mt_list', ".$all_links_from_mtree[$id]["link_id"].", '".addslashes(trim($all_links_from_mtree[$id]["link_name"]))."', '".addslashes(trim($all_links_from_mtree[$id]["link_name"]))."', '".addslashes(trim($all_links_from_mtree[$id]["metakey"]))."', '".addslashes(trim($all_links_from_mtree[$id]["metadesc"]))."')";
				}
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		//insert all new titlekeys in seo tables
		if(isset($links_name_titlekeys) && is_array($links_name_titlekeys) && count($links_name_titlekeys) > 0 && isset($all_links_from_mtree) && count($all_links_from_mtree) > 0){
			foreach($links_name_titlekeys as $key=>$value){
				if(trim($value != "")){
					$temp = explode("-", $key);
					$id = $temp["0"];
					$sql = "insert into #__ijseo_titlekeys (`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`, `type`, `joomla_id`) values ('".addslashes(trim($value))."', 0, 0, -1, '".$date_time."', 0, 'mt_list', ".$id.")";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}

		//differences for delete old rows
		$keys_for_delete = array_diff($keys_id_from_seo, $links_from_mtree); // difference bettwin links and key_id
		if(isset($keys_for_delete) && is_array($keys_for_delete) && count($keys_for_delete) > 0){
			foreach($keys_for_delete as $key=>$value){
				$sql = "delete from #__ijseo_keys where title = '".addslashes(trim($value))."'";				
				$db->setQuery($sql);
				$db->query();
				$sql = "delete from #__ijseo_keys_id where type='mt_list' and keyword='".addslashes(trim($value))."'";
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		$metags_for_delete = array_diff($mtags_name_from_seo, $all_links_name_from_mtree); // difference bettwin links and key_id
		if(isset($metags_for_delete) && is_array($metags_for_delete) && count($metags_for_delete) > 0){
			foreach($metags_for_delete as $key=>$value){
				$sql = "delete from #__ijseo_metags where name = '".addslashes(trim($value))."' and mtype='mt_list'";				
				$db->setQuery($sql);
				$db->query();				
			}
		}
	}
	
	function sincronizeCategoriesWithSeo(){
		$db = JFactory::getDBO();
		$sql = "select cat_id, cat_name, metakey, metadesc from #__mt_cats";
		$db->setQuery($sql);
		$db->query();
		$all_links_from_mtree = $db->loadAssocList("cat_id"); // all values from mtree links and meta datas
		$all_links_name_from_mtree = $db->loadColumn(1); // all values from mtree links and meta datas
		$links_from_mtree1 = $db->loadAssocList(); // all values from mtree but not with meta datas	
		$links_from_mtree2 = array(); //$db->loadColumn(2); // all values from mtree but not with meta datas		
		$links_from_mtree = array();
		$links_name_from_mtree = array(); // all names from mtree but not with meta datas
		
		//separate links keywords by ,
		if(isset($links_from_mtree1) && is_array($links_from_mtree1) && count($links_from_mtree1) > 0){
			foreach($links_from_mtree1 as $key=>$value){
				if(trim($value["metakey"]) != ""){
					$temp = explode(",", trim($value["metakey"]));
					if(count($temp) > 1){
						$i = 0;
						foreach($temp as $key2=>$value2){
							if(trim($value2) != ""){
								$links_from_mtree[$value["cat_id"]."-".$i++] = trim($value2);
							}
						}						
					}
					else{
						$links_from_mtree[$value["cat_id"]."-0"] = trim($value["metakey"]);
					}
					$links_from_mtree2[$value["cat_id"]."-0"] = trim($value["metakey"]);
				}
			}
		}
		
		//separate links name by separators
		$component_params = $this->getComponentParams();
		if(isset($component_params) && isset($component_params->delimiters) && trim($component_params->delimiters) != ""){
			$delimiters = $component_params->delimiters;
			$delimiters = str_split(trim($delimiters));
			if(isset($all_links_from_mtree) && is_array($all_links_from_mtree) && count($all_links_from_mtree) > 0){
				foreach($all_links_from_mtree as $key=>$value){
					$temp = str_replace($delimiters, "*****", $value["cat_name"]);
					$temp_array = explode("*****", $temp);
					$i = 0;
					if(count($temp_array) > 1){						
						foreach($temp_array as $key_temp=>$value_temp){
							if(trim($value_temp) != ""){
								$links_name_from_mtree[$value["cat_id"]."-".$i++] = $value_temp;
							}
						}
						unset($links_name_from_mtree[$key]);
					}
					else{
						$links_name_from_mtree[$value["cat_id"]."-".$i++] = $value["cat_name"];
					}					
				}
			}
		}		
		//------------------------------------
		
		$sql = "select id, title from #__ijseo_keys";
		$db->setQuery($sql);
		$db->query();
		$keys_from_seo = $db->loadColumn(1); // all keywords from seo
		
		$sql = "select keyword, type_id from #__ijseo_keys_id where type = 'mt_cat'";
		$db->setQuery($sql);
		$db->query();
		$keys_id_from_seo = $db->loadColumn(0); // all key_id from seo
		
		$sql = "select id, metakey from #__ijseo_metags where mtype = 'mt_cat'";
		$db->setQuery($sql);
		$db->query();
		$metags_from_seo = $db->loadColumn(1); // all metags from seo
		$metags_from_seo1 = $db->loadColumn(0); // all id metags from seo
		
		$sql = "select name from #__ijseo_metags where mtype = 'mt_cat'";		
		$db->setQuery($sql);
		$db->query();
		$mtags_name_from_seo = $db->loadColumn(); // all titlekeys from seo
		
		$sql = "select title, joomla_id from #__ijseo_titlekeys where type = 'mt_cat'";
		$db->setQuery($sql);
		$db->query();
		$titlekeys_from_seo = $db->loadColumn(0); // all titlekeys from seo								
	
		//differences for insert new rows
		$links_keys = array_diff($links_from_mtree, $keys_from_seo); // difference bettwin links and keywords
		$links_key_id = array_diff($links_from_mtree, $keys_id_from_seo); // difference bettwin links and key_id
		$links_metags = array_diff($links_from_mtree2, $metags_from_seo); // difference bettwin links and key_id
		$links_name_titlekeys = array_diff($links_name_from_mtree, $titlekeys_from_seo); // difference bettwin links_name and titlekeys
			
		$jnow = JFactory::getDate();
		$date_time = $jnow->toSQL();
		
		//insert all new keywords in seo tables
		if(isset($links_keys) && is_array($links_keys) && count($links_keys) > 0){
			foreach($links_keys as $key=>$value){
				if(trim($value) != ""){
					$sql = "insert into #__ijseo_keys(`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`) values ('".addslashes(trim($value))."', 0, 0, -1, '".$date_time."', 0)";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
		
		//insert all new keys_id in seo tables
		if(isset($links_key_id) && is_array($links_key_id) && count($links_key_id) > 0 && isset($all_links_from_mtree) && count($all_links_from_mtree) > 0){
			foreach($links_key_id as $key=>$value){
				if(trim($value) != ""){
					$temp = explode("-", $key);
					$id = $temp["0"];
					$sql = "insert into #__ijseo_keys_id (`keyword`, `type`, `type_id`) values ('".addslashes(trim($value))."', 'mt_cat', ".intval($id).")";					
					$db->setQuery($sql);
					$db->query();
				}
			}
		}
		
		//insert all new metags in seo tables
		if(isset($links_metags) && is_array($links_metags) && count($links_metags) > 0 && isset($all_links_from_mtree) && count($all_links_from_mtree) > 0){
			foreach($links_metags as $key=>$value){
				$temp = explode("-", $key);
				$id = intval($temp["0"]);
				if(in_array($id, $metags_from_seo1)){
					$sql = "update #__ijseo_metags set metakey='".addslashes(trim($all_links_from_mtree[$id]["metakey"]))."', metadesc='".addslashes(trim($all_links_from_mtree[$id]["metadesc"]))."' where mtype='mt_cat' and id=".$id;
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `name`, `titletag`, `metakey`, `metadesc`) values ('mt_cat', ".$all_links_from_mtree[$id]["cat_id"].", '".addslashes(trim($all_links_from_mtree[$id]["cat_name"]))."', '".addslashes(trim($all_links_from_mtree[$id]["cat_name"]))."', '".addslashes(trim($all_links_from_mtree[$id]["metakey"]))."', '".addslashes(trim($all_links_from_mtree[$id]["metadesc"]))."')";
				}
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		//insert all new titlekeys in seo tables
		if(isset($links_name_titlekeys) && is_array($links_name_titlekeys) && count($links_name_titlekeys) > 0 && isset($all_links_from_mtree) && count($all_links_from_mtree) > 0){
			foreach($links_name_titlekeys as $key=>$value){
				if(trim($value != "")){
					$temp = explode("-", $key);
					$id = $temp["0"];
					$sql = "insert into #__ijseo_titlekeys (`title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`, `type`, `joomla_id`) values ('".addslashes(trim($value))."', 0, 0, -1, '".$date_time."', 0, 'mt_cat', ".$id.")";
					$db->setQuery($sql);
					$db->query();
				}
			}
		}

		//differences for delete old rows
		$keys_for_delete = array_diff($keys_id_from_seo, $links_from_mtree); // difference bettwin links and key_id
		if(isset($keys_for_delete) && is_array($keys_for_delete) && count($keys_for_delete) > 0){
			foreach($keys_for_delete as $key=>$value){
				$sql = "delete from #__ijseo_keys where title = '".addslashes(trim($value))."'";				
				$db->setQuery($sql);
				$db->query();
				$sql = "delete from #__ijseo_keys_id where type='mt_cat' and keyword='".addslashes(trim($value))."'";
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		$metags_for_delete = array_diff($mtags_name_from_seo, $all_links_name_from_mtree); // difference bettwin links and key_id
		if(isset($metags_for_delete) && is_array($metags_for_delete) && count($metags_for_delete) > 0){
			foreach($metags_for_delete as $key=>$value){
				$sql = "delete from #__ijseo_metags where name = '".addslashes(trim($value))."' and mtype='mt_cat'";				
				$db->setQuery($sql);
				$db->query();				
			}
		}
	}
	
	function save(){
		$mtree = JRequest::getVar("mtree", "");
		if($mtree == "1"){
			if($this->saveListings()){
				return true;
			}
			return false;
		}
		elseif($mtree == "2"){
			if($this->saveCategories()){
				return true;
			}
			return false;
		}
	}
	
	function saveListings(){		
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		foreach($ids as $key=>$id){			
			if($page_title[$id] != "" || $metakey[$id] != "" || $metadesc[$id] != ""){																			
				$all_ptitlekeys = $this->getAllTitleKeysListing();
				$all_seo_metags = $this->getAllMetagsListing();
				$all_listings_name = $this->getAllListingsName();
				$jnow =  JFactory::getDate();
				$date =  $jnow->toSQL();
				
				//save title keywords if option is set to keywords from titlemetatag
				if(trim($component_params->delimiters) != ""){
					$delimiters = str_split(trim($component_params->delimiters));
					$page_title_temp = $page_title[$id];				
					$page_title_temp = str_replace($delimiters, "******", $page_title_temp);//replace |,:; with ****** and then for each element we have a new row in  _ijseo_titlekeys
					$page_title_array = explode("******", $page_title_temp);
										
					if(is_array($page_title_array) && count($page_title_array) > 0){
						foreach($page_title_array as $ptkey=>$ptvalue){
							if(isset($all_ptitlekeys) && is_array($all_ptitlekeys) && !in_array($ptvalue, $all_ptitlekeys) && trim($ptvalue) != ""){
								$sql = "insert into #__ijseo_titlekeys values('', '".addslashes(trim($ptvalue))."', 0, 0, -1,  '".$date."', 0, 'mt_list', ".intval($id).")";								
								$db->setQuery($sql);
								$db->query();
							}
						}								
					}
				}
				else{
					if(trim($page_title[$id]) != ""){
						$sql = "insert into #__ijseo_titlekeys values('', '".addslashes(trim($page_title[$id]))."', 0, 0, -1,  '".$date."', 0, 'mt_list', ".intval($id).")";
						$db->setQuery($sql);
						$db->query();
					}
				}
				
				// save values in metags tables of seo
				if(isset($all_seo_metags[$id])){
					$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'mt_list' and id=".intval($id);
				}
				elseif(!isset($all_seo_metags[$id])){
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `name`, `titletag`, `metakey`, `metadesc`) values ('mt_list', ".intval($id).", '".addslashes($all_listings_name[$id]["link_name"])."', '".addslashes(trim($page_title[$id]))."','".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
				}						
				$db->setQuery($sql);
				$db->query();
				
				//save in mtree tables then on edit all values will be save in another seo tables
				$sql = "update #__mt_links set metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where link_id=".intval($id);
				$db->setQuery($sql);
				$db->query();				
			}		
		}
		return true;	
	}
	
	function saveCategories(){		
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		foreach($ids as $key=>$id){			
			if($page_title[$id] != "" || $metakey[$id] != "" || $metadesc[$id] != ""){																			
				$all_ptitlekeys = $this->getAllTitleKeysCategories();				
				$all_seo_metags = $this->getAllMetagsCategories();
				$all_listings_name = $this->getAllCategoriesName();
				$jnow =  JFactory::getDate();
				$date =  $jnow->toSQL();
				
				//save title keywords if option is set to keywords from titlemetatag
				if(trim($component_params->delimiters) != ""){
					$delimiters = str_split(trim($component_params->delimiters));
					$page_title_temp = $page_title[$id];				
					$page_title_temp = str_replace($delimiters, "******", $page_title_temp);//replace |,:; with ****** and then for each element we have a new row in  _ijseo_titlekeys
					$page_title_array = explode("******", $page_title_temp);
										
					if(is_array($page_title_array) && count($page_title_array) > 0){
						foreach($page_title_array as $ptkey=>$ptvalue){
							if(isset($all_ptitlekeys) && is_array($all_ptitlekeys) && !in_array($ptvalue, $all_ptitlekeys) && trim($ptvalue) != ""){
								$sql = "insert into #__ijseo_titlekeys values('', '".addslashes(trim($ptvalue))."', 0, 0, -1,  '".$date."', 0, 'mt_cat', ".intval($id).")";								
								$db->setQuery($sql);
								$db->query();
							}
						}								
					}
				}
				else{
					if(trim($page_title[$id]) != ""){
						$sql = "insert into #__ijseo_titlekeys values('', '".addslashes(trim($page_title[$id]))."', 0, 0, -1,  '".$date."', 0, 'mt_cat', ".intval($id).")";
						$db->setQuery($sql);
						$db->query();
					}
				}
				
				// save values in metags tables of seo
				if(isset($all_seo_metags[$id])){
					$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'mt_cat' and id=".intval($id);
				}
				elseif(!isset($all_seo_metags[$id])){
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `name`, `titletag`, `metakey`, `metadesc`) values ('mt_cat', ".intval($id).", '".addslashes($all_listings_name[$id]["cat_name"])."', '".addslashes(trim($page_title[$id]))."', '".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
				}						
				$db->setQuery($sql);
				$db->query();
				
				//save in mtree tables then on edit all values will be save in another seo tables
				$sql = "update #__mt_cats set metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where cat_id=".intval($id);
				$db->setQuery($sql);
				$db->query();				
			}		
		}
		return true;	
	}
	
	function getAllTitleKeysListing(){
		$db = JFactory::getDBO();
		$sql = "select title from #__ijseo_titlekeys where type='mt_list'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	
	function getAllTitleKeysCategories(){
		$db = JFactory::getDBO();
		$sql = "select title from #__ijseo_titlekeys where type='mt_cat'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	}
	
	function getAllMetagsListing(){
		$db = JFactory::getDBO();
		$sql = "select id, titletag from #__ijseo_metags where mtype = 'mt_list'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllMetagsCategories(){
		$db = JFactory::getDBO();
		$sql = "select id, titletag from #__ijseo_metags where mtype = 'mt_cat'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function getAllListingsName(){
		$db = JFactory::getDBO();
		$sql = "select link_id, link_name from #__mt_links";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("link_id");
		return $result;
	}
	
	function getAllCategoriesName(){
		$db = JFactory::getDBO();
		$sql = "select cat_id, cat_name from #__mt_cats";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("cat_id");
		return $result;
	}
	
	function getAllDesc(){
		$db = JFactory::getDBO();
		$mtree = JRequest::getVar("mtree", "0");
		$sql = "";
		$result = "";
		if($mtree == "1"){
			$sql = "select link_id, link_desc as metadesc from #__mt_links";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("link_id");
		}
		elseif($mtree == "2"){
			$sql = "select cat_id, cat_desc as metadesc from #__mt_cats";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("cat_id");
		}
		return $result;
	}
	
	function getAllTitles(){
		$db = JFactory::getDBO();
		$mtree = JRequest::getVar("mtree", "0");
		$sql = "";
		$result = "";
		if($mtree == "1"){
			$sql = "select link_id, link_name as title from #__mt_links";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("link_id");
		}
		elseif($mtree == "2"){
			$sql = "select cat_id, cat_name as title from #__mt_cats";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("cat_id");
		}
		return $result;
	}
	
	function getAllTitlesTag(){
		$db = JFactory::getDBO();
		$mtree = JRequest::getVar("mtree", "0");
		$sql = "";
		$result = "";
		if($mtree == "1"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='mt_list'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		elseif($mtree == "2"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='mt_cat'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		return $result;
	}
	
	function getAllKeywords(){
		$db = JFactory::getDBO();
		$mtree = JRequest::getVar("mtree", "0");
		$sql = "";
		$result = "";
		if($mtree == "1"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='mt_list'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		elseif($mtree == "2"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='mt_cat'";
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
		$mtree = JRequest::getVar("mtree", "0");
		foreach($ids as $key=>$id){			
			$desc = "";
			if(isset($all_desc) && count($all_desc) > 0){
				$params = $this->getComponentParams();
				if($params->ijseo_type_desc == "Words"){
					$exclude_key = $params->exclude_key;
					$temp1 = "";
					foreach($exclude_key as $e_key=>$e_value){					
						$temp1 = str_replace($e_value, " ", strip_tags($all_desc[$id]["metadesc"]));
						$all_desc[$id]["metadesc"] = $temp1;
					}
					$temp2 = explode(" ", $temp1);
					$delete = array_splice($temp2, 0, $params->ijseo_allow_no_desc);					
					$desc = implode(" ", $delete);					
				}
				else{
					if(isset($all_desc[$id]["metadesc"])){
						$exclude_key = $params->exclude_key;
						$temp1 = "";
						foreach($exclude_key as $e_key=>$e_value){					
							$temp1 = str_replace($e_value, " ", strip_tags($all_desc[$id]["metadesc"]));
							$all_desc[$id]["metadesc"] = $temp1;
						}					
						$temp1 = str_replace($exclude_key, " ", strip_tags($all_desc[$id]["metadesc"]));
						$desc = mb_substr($temp1, 0, $params->ijseo_allow_no_desc);
					}
				}
			}			
			if($mtree == "1"){
				$query->clear();
				$query->update('#__mt_links');
				$query->set("`metadesc`='".addslashes(trim($desc))."'");
				$query->where('link_id='.$id);
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
			}
			elseif($mtree == "2"){
				$query->clear();
				$query->update('#__mt_cats');
				$query->set("`metadesc`='".addslashes(trim($desc))."'");
				$query->where('cat_id='.$id);
				$db->setQuery($query);
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
		$mtree = JRequest::getVar("mtree", "0");
		$type = "";
		if($mtree == "1"){
			$type = "mt_list";
		}
		elseif($mtree == "2"){
			$type = "mt_cat";
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
		$mtree = JRequest::getVar("mtree", "0");
		$type = "";
		$table = "";
		$col_id = "";
		if($mtree == "1"){
			$type = "mt_list";
			$table = "#__mt_links";
			$col_id = "link_id";
		}
		elseif($mtree == "2"){
			$type = "mt_cat";
			$table = "#__mt_cats";
			$col_id = "cat_id";
		}
		foreach($ids as $key=>$id){
			$title = $all_titles[$id]["title"];
			$query->clear();
			$query->update('#__ijseo_metags');
			$query->set("`metakey`='".addslashes(trim($title))."'");
			$query->where('id='.$id." and mtype='".$type."'");
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
			
			$query->clear();
			$query->update($table);
			$query->set("`metakey`='".addslashes(trim($title))."'");
			$query->where($col_id.'='.$id);
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function copyTitleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$mtree = JRequest::getVar("mtree", "0");
		$type = "";
		$table = "";
		$col_id = "";
		if($mtree == "1"){
			$type = "mt_list";
			$table = "#__mt_links";
			$col_id = "link_id";
		}
		elseif($mtree == "2"){
			$type = "mt_cat";
			$table = "#__mt_cats";
			$col_id = "cat_id";
		}
		$all_titles = $this->getAllTitlesTag();
		foreach($ids as $key=>$id){
			$title = $all_titles[$id]["title"];
			$query->clear();
			$query->update('#__ijseo_metags');
			$query->set("`metakey`='".addslashes(trim($title))."'");
			$query->where('id='.$id." and mtype='".$type."'");
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
			
			$query->clear();
			$query->update($table);
			$query->set("`metakey`='".addslashes(trim($title))."'");
			$query->where($col_id.'='.$id);
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function copyKeyToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$mtree = JRequest::getVar("mtree", "0");
		$type = "";				
		if($mtree == "1"){
			$type = "mt_list";			
		}
		elseif($mtree == "2"){
			$type = "mt_cat";			
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
		$result_string = @$result_string["0"];
		$result = json_decode($result_string);
		return $result;
	}
}

?>