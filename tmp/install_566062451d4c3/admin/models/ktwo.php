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

class iJoomla_SeoModelKtwo extends JModelLegacy {

	protected $context = 'com_ijoomla_seo.ktwo';
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
	
	function getItems(){
		$config = new JConfig();
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->list_limit);
				
		$db = JFactory::getDBO();
		$query = $this->getListQuery();
		
		if ($query != NULL) {
			$db->setQuery($query);
			$db->query();
			$result	= $db->loadObjectList();
			$this->_total=count($result);
			
			$db->setQuery($query,$limistart,$limit);
			$db->query();
			$result	= $db->loadObjectList();				
		}
		else{
			$this->_total = 0;
			$result = NULL;
		}
		
		return $result;
	}
	
	function getCategs() {
		$db = JFactory::getDBO();
		$search = JRequest::getVar("ktwo");
		// only query the db if we select "items"
		if ($search == "1") {
			$sql = "SELECT id, name FROM #__k2_categories where `trash`=0";
			$db->setQuery($sql);
			return $db->loadObjectList();
		}
		return false;
	}
	
	function existsK2() {
		$db = JFactory::getDBO();
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$tables = $db->loadColumn();
		$config = JFactory::getConfig();
		if(!in_array($config->get('dbprefix')."k2_items", $tables)){
			return false;
		}
		else{
			return true;
		}
	}
	
	function getListQuery(){		
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		$k2_type = JRequest::getInt('ktwo', 0);
		
		$filter_missing = $app->getUserStateFromRequest($this->context.'.filter.missing', 'atype','any','string');
		$this->setState('filter.missing', $filter_missing,'string');
		
		$filter_state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state','','string');
		$this->setState('filter.published', $filter_state,'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search','','string');
		$this->setState('filter.search', $filter_search, 'string');
		
		$menu_types = $app->getUserStateFromRequest($this->context.'.filter.menu_types', 'menu_types','','string');
		$this->setState('filter.menu_types', $menu_types, 'string');

		$filter = JRequest::getVar("filter", "", "get");
		if($filter != ""){
			$filter_author = "";
			$filter_state = "";
			$filter_search = "";
			$catid = "";
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.author', "" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}	

		$where=" where 1=1 ";

		switch ($filter_missing) {
			case "1":
				$where.= " AND m.titletag = '' ";
				break;
			case "2":
				if($k2_type == 1){
					$where.= " AND k.metakey ='' ";
				}
				else{
					$where.= ' AND k.params like \'%"catMetaKey":""%\'';
				}
				break;
			case "3":
				if($k2_type == 1){
					$where.= " AND k.metadesc ='' ";
				}
				else{
					$where.= ' AND k.params like \'%"catMetaDesc":""%\' ';
				}
				break;
			case "4":
				if($k2_type == 1){
					$where.= " AND ( k.metadesc = '' OR m.titletag = '' OR k.metakey = '') ";
				}
				break;
			default:
				break;
		}
		
		switch ($filter_state) {
			case "1":
				$where.= " AND k.published=1 ";
				break;
			case "2":
				$where.= " AND k.published=0 ";
				break;
			case "3":
				$where.= " AND k.trash=1 ";
				break;				
			default:
				$where.= "";
				break;
		}
		
		if ($filter_search!="") { 
			$where .= " AND (title LIKE '%".addslashes($filter_search)."%') ";
		}
		
		if ((JRequest::getInt('ktwo') == 1) && JRequest::getInt('itemcats_k2') > 0) {
			$where .= " AND k.catid = '" . JRequest::getInt('itemcats_k2') . "' ";
		}
		
		$query = NULL;
		
		if ($k2_type) {
			if($k2_type == 1){// check the items "branch"
				$query = "
					SELECT k.id, k.title, m.titletag, k.metakey, k.metadesc 
					FROM #__k2_items AS k 
					LEFT JOIN #__ijseo_metags AS m 
					ON k.`id` = m.`id` AND m.`mtype` = 'k2-item' 
					{$where} group by k.id ORDER BY k.id DESC";
			}
			elseif($k2_type == 2){// check the categories "branch"
				$query = "
					SELECT k.id, k.name AS title, m.titletag, k.params 
					FROM #__k2_categories AS k 
					LEFT JOIN #__ijseo_metags AS m
					ON k.`id` = m.`id` 
					and m.`mtype` = 'k2-cat'
					{$where} ORDER BY k.`id` DESC";
			}
		}
		
		return $query;		
	}
	
	function getParams($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('params');
		$query->from('#__k2_categories');
		$query->where("id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result_string = $db->loadColumn();
		$result_string = @$result_string["0"];
		
		$result = json_decode($result_string, true);
		return $result;
	}
	
	function getTitleTipe(){
		$types = JRequest::getVar("ktwo", "k2-item");
		$return = "";
		switch($types){
			case "1" : {
				$return = "k2-item";
				break;
			}
			case "2" : {
				$return = "k2-cat";
				break;
			}
			default: {
				$return = "k2-item";
				break;
			}
		}
		return $return;
	}
	
	function save() {
		$component_params = $this->getComponentParams();
		$db = JFactory::getDBO();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		$k2_type = JRequest::getVar("ktwo", "");
		$title_type = $this->getTitleTipe();
		
		if($k2_type == 2){
			$the_type = 'k2-cat'; 
		}
		else{
			$the_type = 'k2-item';
		}
		
		$sql = "select `id` from #__ijseo_metags where `mtype`='".$the_type."'";
		$db->setQuery($sql);
		$db->query();
		$all_ids = $db->loadColumn();

		foreach($ids as $id){
			if(in_array($id, $all_ids)){
				$sql = "UPDATE `#__ijseo_metags` SET `titletag` = '".addslashes(trim($page_title[$id]))."'
						WHERE `mtype` = '".$the_type."' AND `id` = '".intval($id)."'";
			}
			else{
				$name_item = "";
				if($k2_type == 2){
					$sql = "select `name` from #__k2_categories where `id`=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$name_item = $db->loadColumn();
					$name_item = @$name_item["0"];
				}
				else{
					$sql = "select `title` from #__k2_items where `id`=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$name_item = $db->loadColumn();
					$name_item = @$name_item["0"];
				}
				
				$sql = "insert into `#__ijseo_metags` (`mtype`, `id`, `titletag`) values ('".$the_type."', '".$id."', '".addslashes(trim($page_title[$id]))."')";
			}
			$db->setQuery($sql);
			if($db->query()){
				$sql = "";
				if($k2_type == 1){ // items
					$sql = "update #__k2_items set `metadesc`='".addslashes(trim($metadesc[$id]))."', `metakey`='".addslashes(trim($metakey[$id]))."' where `id`=".intval($id);
				}
				else{ // categtories
					$params = $this->getParams($id);
					$params["catMetaKey"] = $metakey[$id];
					$params["catMetaDesc"] = $metadesc[$id];
					$sql = "update #__k2_categories set `params`='".addslashes(json_encode($params))."' where `id`=".intval($id);
				}
				$db->setQuery($sql);
				$db->query();
			}
		}
		return true;	
	}
	
	function getArticleTitle($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('title');
		$query->from('#__menu');
		$query->where("id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadColumn();
		$result = $result["0"];
		
		return $result;	
	}
	
	function copyKeyToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$session_titletag = array();
		foreach($ids as $key=>$id){
			$query->clear();
			$params = $this->getParams($id);
			$session_titletag[$id] = $params["menu-meta_keywords"];
		}
		$_SESSION["session_titletag"] = $session_titletag;
		return true;
	}
	
	function copyTitleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$session_metakey = array();
		foreach($ids as $key=>$id){
			$params = $this->getParams($id);
			$session_metakey[$id] = $params["page_title"];
		}
		$_SESSION["session_metakey"] = $session_metakey;
		return true;
	}
	
	function copyArticleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$session_metakey = array();
		foreach($ids as $key=>$id){
			$params = $this->getParams($id);
			$title = $this->getArticleTitle($id);			
			$session_metakey[$id] = $title;
		}
		$_SESSION["session_metakey"] = $session_metakey;
		return true;
	}
	
	function copyArticleToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$session_titletag = array();
		foreach($ids as $key=>$id){
			$params = $this->getParams($id);
			$title = $this->getArticleTitle($id);
			$session_titletag[$id] = $title;
		}
		$_SESSION["session_titletag"] = $session_titletag;
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
	
	function getIntroFullText($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('`introtext`, `fulltext`');
		$query->from('#__k2_items');
		$query->where("`id`=".$id);
		$db->setQuery($query);		
		$db->query();	
		$result = $db->loadAssocList();
		return $result;
	}	
	
	function genMetadesc(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$session_description = array();
		foreach($ids as $key=>$id){
			$intr_full = $this->getIntroFullText($id);			
			$desc = "";
			if(count($intr_full) > 0){
				$params = $this->getComponentParams();
				
				if($params->ijseo_type_desc == "Words"){
					$exclude_key = $params->exclude_key;
					$temp1 = "";
					foreach($exclude_key as $e_key=>$e_value){					
						$temp1 = str_replace(" ".$e_value." ", " ", strip_tags($intr_full["0"]["introtext"]));
						$intr_full["0"]["introtext"] = $temp1;
					}
					$temp2 = explode(" ", $temp1);
					$delete = array_splice($temp2, 0, $params->ijseo_allow_no_desc);					
					$desc = implode(" ", $delete);					
				}
				else{
					if(isset($intr_full["0"]["introtext"])){
						$exclude_key = $params->exclude_key;
						$temp1 = "";
						foreach($exclude_key as $e_key=>$e_value){
							$temp1 = str_replace(" ".$e_value." ", " ", strip_tags($intr_full["0"]["introtext"]));
							$intr_full["0"]["introtext"] = $temp1;
						}					
						$desc = mb_substr($temp1, 0, $params->ijseo_allow_no_desc);
					}
				}
			}
			$session_description[$id] = $desc;
		}
		
		echo "<pre>";
		var_dump($session_description);
		
		$_SESSION["session_description"] = $session_description;
		return true;
	}
}

?>