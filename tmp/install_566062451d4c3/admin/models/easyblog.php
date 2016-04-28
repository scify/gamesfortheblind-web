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

class iJoomla_SeoModelEasyblog extends JModelLegacy {

	protected $context = 'com_ijoomla_seo.easyblog';
	private $_total=0;
	var $_pagination = null;
	private $_items_2sync = array();

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
	
	function getItems() {
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
		} else {
			$this->_total = 0;
			$result = NULL;
		}
		
		return $result;
	}

	function getCategs() {
		$db = JFactory::getDBO();
		$search = JRequest::getVar("easyblog");
		// only query the db if we select "items"
		if ($search == "1") {
			$sql = "SELECT id, title AS name FROM #__easyblog_category";
			$db->setQuery($sql);
			return $db->loadObjectList();
		}
		return false;
	}
	
	function existsEasyblog() {
		$db = JFactory::getDBO();
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$tables = $db->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "easyblog_meta", $tables)) { return false; } else { return true; }
	}
	
	function getListQuery() {
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
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

		$where = " where 1=1 ";

		switch ($filter_missing){
			case "1":
				$where.= " AND m.titletag = '' ";
				break;
			case "2":
				$where.= " AND e.keywords ='' ";
				break;
			case "3":
				$where.= " AND e.description ='' ";
				break;
			case "4":
				$where.= " AND ( e.description = '' OR m.titletag = '' OR e.keywords = '') ";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.= " AND published=1 ";
				break;
			case "2":
				$where.= " AND published=0 ";
				break;
			case "3":
				$where.= " AND published=5 ";
				break;				
			default:
				$where.= "";
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" AND (title LIKE '%".addslashes($filter_search)."%') ";
		}
		
		if ((JRequest::getInt('easyblog') == 1) && JRequest::getInt('itemcats_easyblog') > 0) {
			$where .= " AND k.category_id = '" . JRequest::getInt('itemcats_easyblog') . "' ";
		}		
		
		$easyblog_type = JRequest::getInt('easyblog', 0);
		
		$query = NULL;
		
		if ($easyblog_type) {
			if($easyblog_type == 1){ // check the items "branch"
				$query = "
					SELECT k.id, k.title, m.titletag, e.keywords as metakey, e.description as metadesc
					FROM #__easyblog_post AS k 
					LEFT JOIN #__ijseo_metags AS m ON k.id = m.id and m.`mtype`='easyblog-item'
					LEFT JOIN #__easyblog_meta AS e ON e.content_id = k.id and e.`type`='post'
					{$where} ORDER BY k.id DESC";
			}
			elseif($easyblog_type == 2){ // check the categories "branch"
				$query = "
					SELECT k.id, k.title, m.titletag, e.keywords as metakey, e.description as metadesc
					FROM #__easyblog_category AS k 
					LEFT JOIN #__ijseo_metags AS m ON k.id = m.id and m.`mtype`='easyblog-cat'
					LEFT JOIN #__easyblog_meta AS e ON e.content_id = k.id and e.`type`='category'
					{$where} ORDER BY k.id DESC";
			}
		}
		return $query;
	}
	
	function get_ids($items) {
		function get_em($obj) {
			return $obj->id;
		}

		return array_map("get_em", $items);
	}
	
	function get_meta_for($items, $id) {
		$found = new stdClass();
		$found->keywords = '';
		$found->description = '';
		for ($i=0; $i <= count($items); $i++) {
			if ($items[$i]->id == $id) { 
				$found = $items[$i];
				break;
			}
		}
		return $found;
	}
	
	function getParams($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('params');
		$query->from('#__menu');
		$query->where("id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result_string = $db->loadColumn();
		$result_string = @$result_string["0"];
		$result = json_decode($result_string, true);
		return $result;
	}
	
	function getAllMTitleKeys(){
		$db = JFactory::getDBO();
		$sql = "select concat(title, '[', type, ']') from #__ijseo_titlekeys";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		return $result;
	} 

	function getTitleTipe(){
		$types = JRequest::getVar("easyblog", "easyblog-item");
		$return = "";
		switch($types){
			case "1" : {
				$return = "easyblog-item";
				break;
			}
			case "2" : {
				$return = "easyblog-cat";
				break;
			}
			default: {
				$return = "easyblog-item";
				break;
			}
		}
		return $return;
	}
	
	function check_exists_key_title($type, $key) {
		$db = JFactory::getDBO();
		$sql = "SELECT `id` FROM #__ijseo_{$type} WHERE `title` = {$key}";
		$db->setQuery($sql);
		$exists = $db->loadColumn();
		$exists = @$exists["0"];
		
		if (!$exists) {
			if ($type == 'title') {
				$sql = "INSERT INTO `#__ijseo_title` (`id`, `article_id`, `title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`) 
							VALUES (NULL, '0', '" . $key . "', '0', '0', '-1', NOW(), '0');";
			} else {
				$sql = "INSERT INTO `#__ijseo_keys` (`id`, `title`, `rank`, `rchange`, `mode`, `checkdate`, `sticky`) 
							VALUES (NULL, '{$key}', '0', '0', '-1', NOW(), '0');";
			}
			$db->setQuery();
			$db->query();
		}
	}
	
	function save() {
		$component_params = $this->getComponentParams();
		$db = JFactory::getDBO();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		$easyblog_type = JRequest::getVar("easyblog", "");
		$title_type = $this->getTitleTipe();
		
		$the_type = "";
		$the_type2 = "";
		
		if($easyblog_type == 1){
			$the_type = 'easyblog-item';
			$the_type2 = 'post';
		}
		elseif($easyblog_type == 2){
			$the_type = 'easyblog-cat';
			$the_type2 = 'category';
		}
		
		foreach($ids as $id) {
			$sql = "select count(*) from #__easyblog_meta where `type`='".$the_type2."' and `content_id`=".intval($id);
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			$count = $count["0"];
			
			$sql = "";
			if($count == 0){
				$sql = "insert into #__easyblog_meta (`type`, `content_id`, `keywords`, `description`) values ('".$the_type2."', ".$id.", '".addslashes(trim($metakey[$id]))."', '".addslashes(trim($metadesc[$id]))."')";
			}
			else{
				$sql = "update #__easyblog_meta set `keywords`='".addslashes(trim($metakey[$id]))."', `description`='".addslashes(trim($metadesc[$id]))."' where `type`='".$the_type2."' and `content_id`=".intval($id);
			}
			
			$db->setQuery($sql);
			if($db->query()){
				$sql = "select count(*) from #__ijseo_metags where `mtype`='".$the_type."' and `id`=".intval($id);
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadColumn();
				$count = $count["0"];
				
				$sql = "";
				if($count == 0){
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`) values ('".$the_type."', ".$id.", '".addslashes(trim($page_title[$id]))."')";
				}
				else{
					$sql = "update #__ijseo_metags set `titletag`='".addslashes(trim($page_title[$id]))."' where `mtype`='".$the_type."' and `id`=".intval($id);
				}
				
				$db->setQuery($sql);
				if(!$db->query()){
					return false;
				}
			}
			else{
				return false;
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
		$result = @$result["0"];
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
		$result_string = @$result_string["0"];
		
		$result = json_decode($result_string);
		return $result;
	}

	function getIntroFullText($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('`intro`, `excerpt`');
		$query->from('#__easyblog_post');
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
						$temp1 = str_replace(" ".$e_value." ", " ", strip_tags($intr_full["0"]["intro"]));
						$intr_full["0"]["intro"] = $temp1;
					}
					$temp2 = explode(" ", $temp1);
					$delete = array_splice($temp2, 0, $params->ijseo_allow_no_desc);					
					$desc = implode(" ", $delete);					
				}
				else{
					if(isset($intr_full["0"]["intro"])){
						$exclude_key = $params->exclude_key;
						$temp1 = "";
						foreach($exclude_key as $e_key=>$e_value){
							$temp1 = str_replace(" ".$e_value." ", " ", strip_tags($intr_full["0"]["intro"]));
							$intr_full["0"]["intro"] = $temp1;
						}					
						$desc = mb_substr($temp1, 0, $params->ijseo_allow_no_desc);
					}
				}
			}
			$session_description[$id] = $desc;
		}
		$_SESSION["session_description"] = $session_description;
		return true;
	}
}

?>