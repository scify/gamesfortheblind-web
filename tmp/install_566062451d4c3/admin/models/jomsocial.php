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

class iJoomla_SeoModelJomsocial extends JModelLegacy{

	var $_pagination = null;
	protected $context = 'com_ijoomla_seo.jomsocial';
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
		$jomsocial_option = JRequest::getVar("jomsocial", "0");
		if($jomsocial_option == "1"){
			$query = $this->getEventsListing();
		} elseif($jomsocial_option == "2"){		
			$query = $this->getPhotoAlbumsListing();
		}elseif($jomsocial_option == "3"){		
			$query = $this->getVideosListing();
		}elseif($jomsocial_option == "4"){		
			$query = $this->getPhotosListing();
		} else{ 	
			$query = $this->getGroupsListing();
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
	
	function existsJomsocial() {
		$db = JFactory::getDBO();
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$tables = $db->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "community_groups", $tables)) { return false; } else { return true; }
	}	
	
	function getJomsocialCategories(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__community_groups_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getGroupsListing(){	
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_jomsocialcat = $app->getUserStateFromRequest($this->context.'.filter.filter_jomsocialcat', 'filter_jomsocialcat',-1);
		$this->setState('filter.filter_jomsocialcat', $filter_jomsocialcat, 'string');
		
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
			$filter_jomsocialcat = "-1";			
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.jomsocialcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}							
		$where=" 1=1 ";
		
		if($filter_jomsocialcat != "-1"){
			$where .= " and g.categoryid in (select id from #__community_groups_category where id=".intval($filter_jomsocialcat).") ";
		}	
		switch ($filter_missing){
			case "1":
				$where.= " and mt.titletag like '' ";
				break;
			case "2":
				$where.= " and mt.metakey like '' ";
				break;
			case "3":
				$where.= " and mt.metadesc like ''";
				break;
			case "4":
				$where.= " and ( mt.metakey like '' or mt.metadesc like ''  or mt.titletag like '') ";
				break;
			default:
				break;
		}	
		switch ($filter_state){
			case "1":
				$where.=" and g.published=1 ";
				break;
			case "2":
				$where.=" and g.published=0 ";
				break;				
			default:
				break;
		}	
		if($filter_search!=""){ 
			$where.=" and (g.name like '%".addslashes($filter_search)."%' or mt.metakey like '%".addslashes($filter_search)."%' or mt.metadesc like '%".addslashes($filter_search)."%') ";
		}				
		$query->clear();
		$query->select('g.id, g.name, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_groups g');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = g.id and mt.mtype='js_group'");
		$query->where($where);
		return $query;
	}
	
	function getEventsListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_eventcat = $app->getUserStateFromRequest($this->context.'.filter.filter_eventcat', 'filter_eventcat',-1);
		$this->setState('filter.filter_eventcat', $filter_eventcat, 'string');
		
		
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
			$filter_jomsocialcat = "-1";			
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.jomsocialcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}							
		$where=" 1=1 ";			
		if($filter_eventcat != "-1"){
			$where .= " and p.catid in (select id from #__community_events_category where id=".intval($filter_eventcat).") ";
		}			
		switch ($filter_missing){
			case "1":
				$where.= " and mt.titletag like '' ";
				break;
			case "2":
				$where.= " and mt.metakey like '' ";
				break;
			case "3":
				$where.= " and mt.metadesc like ''";
				break;
			case "4":
				$where.= " and ( mt.metakey like '' or mt.metadesc like ''  or mt.titletag like '') ";
				break;
			default:
				break;
		}	
		switch ($filter_state){
			case "1":
				$where.=" and p.published=1 ";
				break;
			case "2":
				$where.=" and p.published=0 ";
				break;				
			default:
				break;
		}	
		if($filter_search!=""){ 
			$where.=" and (p.title like '%".addslashes($filter_search)."%' or mt.metakey like '%".addslashes($filter_search)."%' or mt.metadesc like '%".addslashes($filter_search)."%') ";
		}				
		$query->clear();
		$query->select('p.id, p.title, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_events p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_events'");
		$query->where($where);
		return $query;
	}
	
	
	function getPhotoAlbumsListing(){
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
			$filter_jomsocialcat = "-1";			
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.jomsocialcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}							
		$where=" 1=1 ";
			
		switch ($filter_missing){
			case "1":
				$where.= " and mt.titletag like '' ";
				break;
			case "2":
				$where.= " and mt.metakey like '' ";
				break;
			case "3":
				$where.= " and mt.metadesc like ''";
				break;
			case "4":
				$where.= " and ( mt.metakey like '' or mt.metadesc like ''  or mt.titletag like '') ";
				break;
			default:
				break;
		}	
		
		if($filter_search!=""){ 
			$where.=" and (p.name like '%".addslashes($filter_search)."%' or mt.metakey like '%".addslashes($filter_search)."%' or mt.metadesc like '%".addslashes($filter_search)."%') ";
		}				
		$query->clear();
		$query->select('p.id, p.name,p.creator, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_photos_albums p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_albums'");
		$query->where($where);
		return $query;
	}
	
	
	function getVideosListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_missing = $app->getUserStateFromRequest($this->context.'.filter.missing', 'atype','any','string');
		$this->setState('filter.missing', $filter_missing,'string');
		$filter_state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state','','string');
		$this->setState('filter.published', $filter_state,'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search', '', 'string');
		$this->setState('filter.search', $filter_search, 'string');			
		
		$filter = JRequest::getVar("filter", "", "get");
		
		if($filter != ""){
			$filter_state = "";
			$filter_search = "";
			$filter_jomsocialcat = "-1";			
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.jomsocialcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}							
		$where=" 1=1 ";			
				
		switch ($filter_missing){
			case "1":
				$where.= " and mt.titletag like '' ";
				break;
			case "2":
				$where.= " and mt.metakey like '' ";
				break;
			case "3":
				$where.= " and mt.metadesc like ''";
				break;
			case "4":
				$where.= " and ( mt.metakey like '' or mt.metadesc like ''  or mt.titletag like '') ";
				break;
			default:
				break;
		}	
		switch ($filter_state){
			case "1":
				$where.=" and p.published=1 ";
				break;
			case "2":
				$where.=" and p.published=0 ";
				break;				
			default:
				break;
		}	
		if($filter_search!=""){ 
			$where.=" and (p.title like '%".addslashes($filter_search)."%' or mt.metakey like '%".addslashes($filter_search)."%' or mt.metadesc like '%".addslashes($filter_search)."%') ";
		}				
		$query->clear();
		$query->select('p.id, p.title,p.creator, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_videos p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_videos'");
		$query->where($where);
		$query->order('p.created desc');
		return $query;
		
	}
	
	
	function getPhotosListing(){
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
			$filter_jomsocialcat = "-1";			
			$filter_missing = JRequest::getVar("value", "", "get");			
			$this->setState('filter.jomsocialcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}							
		$where=" 1=1 ";
			
		switch ($filter_missing){
			case "1":
				$where.= " and mt.titletag like '' ";
				break;
			case "2":
				$where.= " and mt.metakey like '' ";
				break;
			case "3":
				$where.= " and mt.metadesc like ''";
				break;
			case "4":
				$where.= " and ( mt.metakey like '' or mt.metadesc like ''  or mt.titletag like '') ";
				break;
			default:
				break;
		}	
		switch ($filter_state){
			case "1":
				$where.=" and p.published=1 ";
				break;
			case "2":
				$where.=" and p.published=0 ";
				break;				
			default:
				break;
		}	
		if($filter_search!=""){ 
			$where.=" and (p.caption like '%".addslashes($filter_search)."%' or mt.metakey like '%".addslashes($filter_search)."%' or mt.metadesc like '%".addslashes($filter_search)."%') ";
		}				
		$query->clear();
		$query->select('p.id, p.caption,p.creator,p.albumid, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_photos p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_photos'");
		$query->where($where);
		return $query;
	}
	
	function save(){
		$jomsocial = JRequest::getVar("jomsocial", "");
		
		if($jomsocial == "1"){
			if($this->saveEvents()){
				return true;
			}
			return false;
		} elseif($jomsocial == "2"){
			if($this->savePhotoAlbums()){
				return true;
			}
			return false;
		}elseif($jomsocial == "3"){
			if($this->saveVideos()){
				return true;
			}
			return false;
		}elseif($jomsocial == "4"){
			if($this->savePhotos()){
				return true;
			}
			return false;
		} elseif($jomsocial == "0"){ 
			if($this->saveGroups()){
				return true;
			}
			return false;
		}
	
	}
	
		
	function saveGroups(){
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		

		foreach($ids as $key=>$id){
			if($page_title[$id] == "") $page_title[$id] = " ";	
			if($metakey[$id] == "") $metakey[$id] = " ";	
			if($metadesc[$id] == "") $metadesc[$id] = " ";
			
																					
				$all_seo_metags = $this->getAllGroupsMetatagsListing();	
				$jnow =  JFactory::getDate();
				$date =  $jnow->toSQL();
				if(isset($all_seo_metags[$id])){
					$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'js_group' and id=".intval($id);
				}
				elseif(!isset($all_seo_metags[$id])){
					$sql = "insert into #__ijseo_metags (`mtype`, `id`,`titletag`, `metakey`, `metadesc`) values ('js_group', ".intval($id).", '".addslashes(trim($page_title[$id]))."','".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
				}						
				$db->setQuery($sql);
				$db->query();				
			
		}
		return true;	
	}

	function savePhotos(){
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		foreach($ids as $key=>$id){
			if($page_title[$id] == "") $page_title[$id] = " ";	
			if($metakey[$id] == "") $metakey[$id] = " ";	
			if($metadesc[$id] == "") $metadesc[$id] = " ";			
																					
				$all_seo_metags = $this->getAllPhotosMetatagsListing();	
				$jnow =  JFactory::getDate();
				$date =  $jnow->toSQL();
				if(isset($all_seo_metags[$id])){
					$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'js_photos' and id=".intval($id);
				}
				elseif(!isset($all_seo_metags[$id])){
					$sql = "insert into #__ijseo_metags (`mtype`, `id`,`titletag`, `metakey`, `metadesc`) values ('js_photos', ".intval($id).", '".addslashes(trim($page_title[$id]))."','".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
				}						
				$db->setQuery($sql);
				$db->query();				
			
		}
		return true;	
	}
	
	function saveVideos(){
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		foreach($ids as $key=>$id){
			if($page_title[$id] == "") $page_title[$id] = " ";	
			if($metakey[$id] == "") $metakey[$id] = " ";	
			if($metadesc[$id] == "") $metadesc[$id] = " ";			
																					
				$all_seo_metags = $this->getAllVideosMetatagsListing();	
				$jnow =  JFactory::getDate();
				$date =  $jnow->toSQL();
				if(isset($all_seo_metags[$id])){
					$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'js_videos' and id=".intval($id);
				}
				elseif(!isset($all_seo_metags[$id])){
					$sql = "insert into #__ijseo_metags (`mtype`, `id`,`titletag`, `metakey`, `metadesc`) values ('js_videos', ".intval($id).", '".addslashes(trim($page_title[$id]))."','".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
				}						
				$db->setQuery($sql);
				$db->query();				
			
		}
		return true;	
	}

	function savePhotoAlbums(){
			$component_params = $this->getComponentParams();		
			$db = JFactory::getDBO();		
			$query = $db->getQuery(true);
			$query->clear();
			$ids = JRequest::getVar("cid", "", "post", "array");
			$page_title = JRequest::getVar("page_title", "", "post", "array");
			$metakey = JRequest::getVar("metakey", "", "post", "array");
			$metadesc = JRequest::getVar("metadesc", "", "post", "array");
			
			foreach($ids as $key=>$id){
				if($page_title[$id] == "") $page_title[$id] = " ";	
				if($metakey[$id] == "") $metakey[$id] = " ";	
				if($metadesc[$id] == "") $metadesc[$id] = " ";			
																					
					$all_seo_metags = $this->getAllPhotoAlbumsMetatagsListing();	
					$jnow =  JFactory::getDate();
					$date =  $jnow->toSQL();
					if(isset($all_seo_metags[$id])){
						$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'js_albums' and id=".intval($id);
					}
					elseif(!isset($all_seo_metags[$id])){
						$sql = "insert into #__ijseo_metags (`mtype`, `id`,`titletag`, `metakey`, `metadesc`) values ('js_albums', ".intval($id).", '".addslashes(trim($page_title[$id]))."','".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
					}						
					$db->setQuery($sql);
					$db->query();				
					
			}
			return true;	
		}

		function saveEvents(){
					$component_params = $this->getComponentParams();		
					$db = JFactory::getDBO();		
					$query = $db->getQuery(true);
					$query->clear();
					$ids = JRequest::getVar("cid", "", "post", "array");
					$page_title = JRequest::getVar("page_title", "", "post", "array");
					$metakey = JRequest::getVar("metakey", "", "post", "array");
					$metadesc = JRequest::getVar("metadesc", "", "post", "array");
					
					foreach($ids as $key=>$id){
						if($page_title[$id] == "") $page_title[$id] = " ";	
						if($metakey[$id] == "") $metakey[$id] = " ";	
						if($metadesc[$id] == "") $metadesc[$id] = " ";			
																									
							$all_seo_metags = $this->getAllEventsMetatagsListing();	
							$jnow =  JFactory::getDate();
							$date =  $jnow->toSQL();
							if(isset($all_seo_metags[$id])){
								$sql = "update #__ijseo_metags set titletag='".addslashes(trim($page_title[$id]))."', metakey='".addslashes(trim($metakey[$id]))."', metadesc='".addslashes($metadesc[$id])."' where mtype = 'js_events' and id=".intval($id);
							}
							elseif(!isset($all_seo_metags[$id])){
								$sql = "insert into #__ijseo_metags (`mtype`, `id`,`titletag`, `metakey`, `metadesc`) values ('js_events', ".intval($id).", '".addslashes(trim($page_title[$id]))."','".addslashes(trim($metakey[$id]))."', '".addslashes($metadesc[$id])."')";
							}						
							$db->setQuery($sql);
							$db->query();				
						
					}
					return true;	
				}
		

	function getAllGroupsMetatagsListing(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `titletag`, `metakey`, `metadesc` from #__ijseo_metags where mtype = 'js_group'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	function getAllPhotosMetatagsListing(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `titletag`, `metakey`, `metadesc` from #__ijseo_metags where mtype = 'js_photos'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	function getAllEventsMetatagsListing(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `titletag`, `metakey`, `metadesc` from #__ijseo_metags where mtype = 'js_events'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	function getAllVideosMetatagsListing(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `titletag`, `metakey`, `metadesc` from #__ijseo_metags where mtype = 'js_videos'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	function getAllPhotoAlbumsMetatagsListing(){
		$db = JFactory::getDBO();
		$sql = "select `id`, `titletag`, `metakey`, `metadesc` from #__ijseo_metags where mtype = 'js_albums'";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList("id");
		return $result;
	}
	
	function copyKeyToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$type = "";	
		if($jomsocial == "1"){
			$type = "js_events";
		} elseif($jomsocial == "2"){
			$type = "js_albums";
		}elseif($jomsocial == "3"){
			$type = "js_videos";
		}elseif($jomsocial == "4"){
			$type = "js_photos";
		} elseif($jomsocial == "0"){
			$type = "js_group";
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
	
	function getAllKeywords(){
		$db = JFactory::getDBO();
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$sql = "";
		$result = "";
		if($jomsocial == "1"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='js_events'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "2"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='js_albums'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "3"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='js_videos'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "4"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='js_photos'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "0"){
			$sql = "select id, metakey from #__ijseo_metags where mtype='js_group'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		return $result;
	}

	function copyTitleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$type = "";
		$table = "";
		$col_id = "";
		if($jomsocial == "1"){
			$type = "js_events";
			$col_id = "id";
		} elseif($jomsocial == "2"){
			$type = "js_albums";
			$col_id = "id";
		}elseif($jomsocial == "3"){
			$type = "js_videos";
			$col_id = "id";
		}elseif($jomsocial == "4"){
			$type = "js_photos";
			$col_id = "id";
		} elseif($jomsocial == "0"){
			$type = "js_group";
			$col_id = "id";
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
			
		}
		return true;
	}

		
	function getAllTitlesTag(){
		$db = JFactory::getDBO();
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$sql = "";
		$result = "";
		if($jomsocial == "1"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='js_events'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "2"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='js_albums'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "3"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='js_videos'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "4"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='js_photos'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "0"){
			$sql = "select id, titletag as title from #__ijseo_metags where mtype='js_group'";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		
		return $result;
	}

	function copyArticleToKey(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$all_titles = $this->getAllTitles();
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$type = "";
		$table = "";
		$col_id = "";
		if($jomsocial == "1"){
			$type = "js_events";
			$col_id = "id";
		} elseif($jomsocial == "2"){
			$type = "js_albums";
			$col_id = "id";
		}elseif($jomsocial == "3"){
			$type = "js_videos";
			$col_id = "id";
		}elseif($jomsocial == "4"){
			$type = "js_photos";
			$col_id = "id";
		} elseif($jomsocial == "0"){
			$type = "js_group";
			$col_id = "id";
		}
	
		foreach($ids as $key=>$id){
			$title = $all_titles[$id]["title"];
			if($this->checkID($id, $type) == "1"){
				$query->clear();
				$query->update('#__ijseo_metags');
				$query->set("`metakey`='".addslashes(trim($title))."'");
				$query->where('id='.$id." and mtype='".$type."'");
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
			}
			else{
				
				$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`,  `metakey`, `metadesc`) values ('".$type."', '".intval($id)."',' ', '".$title."',' ')";
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
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$type = "";
		if($jomsocial == "1"){
			$type = "js_events";
		} elseif($jomsocial == "2"){
			$type = "js_albums";
		}elseif($jomsocial == "3"){
			$type = "js_videos";
		}elseif($jomsocial == "4"){
			$type = "js_photos";
		} elseif($jomsocial == "0"){
			$type = "js_group";
		}
		
		
		foreach($ids as $key=>$id){
			
			$title = $all_titles[$id]["title"];
			if($this->checkID($id, $type) == "1"){
				$query->clear();
				$query->update('#__ijseo_metags');
				$query->set("`titletag`='".addslashes(trim($title))."'");
				$query->where('id='.$id." and mtype='".$type."'");
				$db->setQuery($query);
				if(!$db->query()){
					return false;
				}
			}
			else{
				
				$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`, `metakey`, `metadesc`) values ('".$type."', '".intval($id)."', '".$title."',' ', ' ')";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
			}
		}
		return true;
	}
	
	function checkID($id, $type){
		$db = JFactory::getDBO();
		$sql = "SELECT COUNT(*) FROM #__ijseo_metags WHERE id ='".$id."' and mtype = '".$type."'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = @$count["0"];
		return $count;
	}
	
	
	function getAllTitles(){
		$db = JFactory::getDBO();
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$sql = "";
		$result = "";
		if($jomsocial == "1"){
			$sql = "select id, title as title from #__community_events";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "2"){
			$sql = "select id, name as title from #__community_photos_albums";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "3"){
			$sql = "select id, title as title from #__community_videos";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "4"){
			$sql = "select id, caption as title from #__community_photos";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "0"){
			$sql = "select id, name as title from #__community_groups";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}
		
		return $result;
	}

	function getJomsocialGroupCategories(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__community_groups_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	function getJomsocialEventCategories(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__community_events_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function genMetadesc(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$all_desc = $this->getAllDesc();		
		$jomsocial = JRequest::getVar("jomsocial", "0");
		
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
			
			if($jomsocial == "1"){
				$sql = "select count(*) from #__ijseo_metags where `id`=".intval($id)." and `mtype`='js_events'";
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadColumn();
				$count = @$count["0"];
				
				if(intval($count) > 0){
					$query->clear();
					$query->update('#__ijseo_metags');
					$query->set("`metadesc`='".addslashes(trim($desc))."'");
					$query->where('id='.$id.' and mtype = "js_events"');
					$db->setQuery($query);
					if(!$db->query()){
						return false;
					}
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`, `metakey`, `metadesc`) values ('js_events', '".intval($id)."', '', '', '".addslashes(trim($desc))."')";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
			}
			elseif($jomsocial == "2"){
				$sql = "select count(*) from #__ijseo_metags where `id`=".intval($id)." and `mtype`='js_albums'";
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadColumn();
				$count = @$count["0"];
				
				if(intval($count) > 0){
					$query->clear();
					$query->update('#__ijseo_metags');
					$query->set("`metadesc`='".addslashes(trim($desc))."'");
					$query->where('id='.$id.' and mtype = "js_albums"');
					$db->setQuery($query);
					if(!$db->query()){
						return false;
					}
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`, `metakey`, `metadesc`) values ('js_albums', '".intval($id)."', '', '', '".addslashes(trim($desc))."')";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
			}
			elseif($jomsocial == "3"){
				$sql = "select count(*) from #__ijseo_metags where `id`=".intval($id)." and `mtype`='js_videos'";
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadColumn();
				$count = @$count["0"];
				
				if(intval($count) > 0){
					$query->clear();
					$query->update('#__ijseo_metags');
					$query->set("`metadesc`='".addslashes(trim($desc))."'");
					$query->where('id='.$id.' and mtype = "js_videos"');
					$db->setQuery($query);
					if(!$db->query()){
						return false;
					}
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`, `metakey`, `metadesc`) values ('js_videos', '".intval($id)."', '', '', '".addslashes(trim($desc))."')";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
			}
			elseif($jomsocial == "4"){
				$sql = "select count(*) from #__ijseo_metags where `id`=".intval($id)." and `mtype`='js_photos'";
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadColumn();
				$count = @$count["0"];
				
				if(intval($count) > 0){
					$query->clear();
					$query->update('#__ijseo_metags');
					$query->set("`metadesc`='".addslashes(trim($desc))."'");
					$query->where('id='.$id.' and mtype = "js_photos"');
					$db->setQuery($query);
					if(!$db->query()){
						return false;
					}
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`, `metakey`, `metadesc`) values ('js_photos', '".intval($id)."', '', '', '".addslashes(trim($desc))."')";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
			}
			elseif($jomsocial == "0"){
				$sql = "select count(*) from #__ijseo_metags where `id`=".intval($id)." and `mtype`='js_group'";
				$db->setQuery($sql);
				$db->query();
				$count = $db->loadColumn();
				$count = @$count["0"];
				
				if(intval($count) > 0){
					$query->clear();
					$query->update('#__ijseo_metags');
					$query->set("`metadesc`='".addslashes(trim($desc))."'");
					$query->where('id='.$id.' and mtype ="js_group"');
					$db->setQuery($query);
					if(!$db->query()){
						return false;
					}
				}
				else{
					$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`, `metakey`, `metadesc`) values ('js_group', '".intval($id)."', '', '', '".addslashes(trim($desc))."')";
					$db->setQuery($sql);
					if(!$db->query()){
						return false;
					}
				}
			}
		
		}
		return true;
	}
	function getAllDesc(){
		$db = JFactory::getDBO();
		$jomsocial = JRequest::getVar("jomsocial", "0");
		$sql = "";
		$result = "";
		if($jomsocial == "1"){
			$sql = "select `id`, `description` as metadesc from #__community_events";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
				
		} elseif($jomsocial == "2"){
			$sql = "select `id`, `description` as metadesc from #__community_photos_albums";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
				
		}elseif($jomsocial == "3"){
			$sql = "select `id`, `description` as metadesc from #__community_videos";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		}elseif($jomsocial == "4"){
			$sql = "select `id`, `caption` as metadesc from #__community_photos";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
		} elseif($jomsocial == "0"){
			$sql = "select `id`, `description` as metadesc from #__community_groups";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
			}
		return $result;
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