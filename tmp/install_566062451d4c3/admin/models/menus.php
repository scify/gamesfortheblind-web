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

class iJoomla_SeoModelMenus extends JModelLegacy{
	var $_total = 0;
	var $_pagination = null;
	protected $context = 'com_ijoomla_seo.menus';

	function __construct () {
		parent::__construct();
		global $option;
		$app = JFactory::getApplication('administrator');
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
		//-------------------------------------------------------------------
		$old_menu_types = JRequest::getVar("old_menu_types", "");
		$old_types = JRequest::getVar("old_types", "menus");
		$menu_types = JRequest::getVar("menu_types", "");
		
		if(($old_menu_types != $menu_types) || $old_types != "menus"){
			$limitstart = "0";
			JRequest::setVar("limitstart", "0");
			$app->setUserState('limitstart', $limitstart);
		}
		//-------------------------------------------------------------------
		
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
		global $option;
		$config = new JConfig();
		$app		= JFactory::getApplication('administrator');
		$limit = $app->getUserStateFromRequest('global.list.limit', 'limit', $app->getCfg('list_limit'), 'int');
		$limitstart = $app->getUserStateFromRequest($option.'limitstart', 'limitstart', 0, 'int');
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = $this->getListQuery();
				
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->_total=count($result);
		
		$db->setQuery($query, $limitstart, $limit);
		$db->query();
		$result	= $db->loadObjectList();	
		return $result;
	}
	
	function getListQuery(){		
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
							
		$where=" 1=1 and `type` <> 'alias'";
					
		if($menu_types != "" && $menu_types != "0"){
			$where.= " and menutype='".$menu_types."'";
		}
					
		switch ($filter_missing){
			case "1":
				$where.= " and (params like '%\"page_title\":\"\"%' or params not like '%page_title%')";
				break;
			case "2":
				$where.= " and (params like '%\"menu-meta_keywords\":\"\"%' or params not like '%menu-meta_keywords%')";
				break;
			case "3":
				$where.= " and (params like '%\"menu-meta_description\":\"\"%' or params not like '%menu-meta_description%')";
				break;
			case "4":
				$where.= " and (params like '%\"page_title\":\"\"%' or  params like '%\"menu-meta_keywords\":\"\"%' or params like '%\"menu-meta_description\":\"\"%' or params not like '%page_title%' or params not like '%menu-meta_keywords%' or params not like '%menu-meta_description%')";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and published=1 ";
				break;
			case "2":
				$where.=" and published=0 ";
				break;
			case "3":
				$where.=" and published=-2 ";
				break;				
			default:
				$where.=" and published in (0, 1) ";
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (title like '%".addslashes($filter_search)."%' or params like '%".addslashes($filter_search)."%') ";
		}		
		$query->clear();
		$query->select('id, title, params, link');
		$query->from('#__menu');
		$query->where($where);
		$query->order('lft asc');		
		return $query;		
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
		$result_string = $result_string["0"];
		$result = json_decode($result_string, true);
		return $result;
	}
	
	function save(){		
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		$menutype = JRequest::getVar("menu_types", "");
		
		foreach($ids as $key=>$id){			
			$params = $this->getParams($id);				
			$params["page_title"] = $page_title[$id];
			$params["menu-meta_keywords"] = $metakey[$id];
			$params["menu-meta_description"] = $metadesc[$id];
			$param = json_encode($params);
			$param = str_replace("'", "''", $param);
			$param = str_replace("\\", "\\\\", $param);
			$query->clear();
			$query->update('#__menu');
			$query->set("`params`='".$param."'");
			$query->where('id='.$id);				
			$db->setQuery($query);
			if(!$db->query()){
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
}

?>