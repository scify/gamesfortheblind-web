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

class iJoomla_SeoModelArticles extends JModelLegacy{

	var $_pagination = null;
	protected $context = 'com_ijoomla_seo.articles';
	private $_total=0;

	function __construct () {
		parent::__construct();
		global $option;
		$app = JFactory::getApplication('administrator');
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
		//-------------------------------------------------------------------
		$old_types = JRequest::getVar("old_types", "menus");

		if($old_types != "articles"){
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
	
	function getAuthors(){
		$db = JFactory::getDBO();		
		$query = " SELECT c.created_by,  u.name ".
				" FROM (#__content AS c) ".
				" LEFT JOIN #__users AS u ON (u.id = c.created_by) ".
				" WHERE c.state <> -1 ".
				" AND c.state <> -2 ".
				" GROUP BY u.name ".
				" ORDER BY u.name ";
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadObjectList();
		return $result;	
	}	
	
	function getItems(){
		$config = new JConfig();	
		$app = JFactory::getApplication('administrator');
		$limistart = $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit = $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->list_limit);

		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query = $this->getListQuery();
		
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->_total=count($result);
		
		$db->setQuery($query,$limistart,$limit);
		$db->query();
		$result	= $db->loadObjectList();	
		return $result;
	}
	
	function getListQuery(){		
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		$catid 		= $app->getUserStateFromRequest($this->context.'.filter.catid', 'filter_catid');
		
		$filter_author = $app->getUserStateFromRequest($this->context.'.filter.author', 'filter_authorid','','string');
		$this->setState('filter.author', $filter_author, 'string');
		
		$filter_missing = $app->getUserStateFromRequest($this->context.'.filter.missing', 'atype','','string');
		$this->setState('filter.missing', $filter_missing, 'string');
		
		$filter_state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state','','string');
		$this->setState('filter.published', $filter_state, 'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search','','string');
		$this->setState('filter.search', $filter_search, 'string');
		
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
		
		$where=" 1=1 ";
		
		if(intval($catid)>0){
			$where.= " and c.catid=".intval($catid);
		}
		
		if($filter_author != "0" && $filter_author != ""){
			$where.= " and c.created_by=".intval($filter_author);
		}
		
		switch ($filter_missing){
			case "1":
				$where.= " and mt.`titletag`='' ";
				break;
			case "2":
				$where.= " and c.metakey='' ";
				break;
			case "3":
				$where.= " and c.metadesc='' ";
				break;
			case "4":
				$where .= " and (mt.`titletag`='' or c.metakey='' or c.metadesc='')";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and c.state=1 ";
				break;
			case "2":
				$where.=" and c.state=0 ";
				break;
			case "3":
				$where.=" and c.state=2 ";
				break;
			case "4":
				$where.=" and c.state=-2 ";
				break;	
			default:
				$where.=" and c.state in (0, 1) ";
				break;
		}
		
		if($filter_search != ""){ 
			$where.=" and (c.title like '%".addslashes($filter_search)."%' or c.metakey like '%".addslashes($filter_search)."%' or c.metadesc like '%".addslashes($filter_search)."%') ";
		}		
		
		$query->select('c.id, c.title, c.metakey, c.metadesc, c.attribs, mt.titletag');
		$query->from('#__content c');
		$query->join('LEFT', '`#__ijseo_metags` AS mt ON c.id = mt.id and mt.mtype=\'article\'');
		$query->where($where);

		return $query;
	}
	
	function getIntroFullText($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('`introtext`, `fulltext`');
		$query->from('#__content');
		$query->where("`id`=".$id);
		$db->setQuery($query);		
		$db->query();	
		$result = $db->loadAssocList();
		return $result;
	}	
	
	function getMetakey($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('metakey');
		$query->from('#__content');
		$query->where("id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadColumn();
		$result = $result["0"];
		return $result;
	}
	
	function getArticleTitle($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('title');
		$query->from('#__content');
		$query->where("id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result = $db->loadColumn();
		$result = $result["0"];
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
		
		foreach($ids as $key=>$id){			
			$sql = "select count(*) from #__ijseo_metags where `mtype`='article' and `id`=".intval($id);
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			$count = $count["0"];
			
			$sql = "";
			if($count == 0){
				$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`) values ('article', ".$id.", '".addslashes(trim($page_title[$id]))."')";
			}
			else{
				$sql = "update #__ijseo_metags set `titletag`='".addslashes(trim($page_title[$id]))."' where `mtype`='article' and `id`=".intval($id);
			}
			
			$db->setQuery($sql);
			if($db->query()){
				$sql = "update #__content set `metakey`='".addslashes(trim($metakey[$id]))."', `metadesc`='".addslashes(trim($metadesc[$id]))."' where `id`=".intval($id);
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
	
	function copyKeyToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$session_titletag = array();
		foreach($ids as $key=>$id){
			$query->clear();
			$metakey = $this->getMetakey($id);			
			$session_titletag[$id] = $metakey;
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
			$sql = "select `titletag` from #__ijseo_metags where `mtype`='article' and `id`=".intval($id);
			$db->setQuery($sql);
			$db->query();
			$titletag = $db->loadColumn();
			$titletag = $titletag["0"];
			
			$metakey = $titletag;
			$session_metakey[$id] = $metakey;
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
			$article = $this->getArticleTitle($id);
			$metakey = $article;
			$session_metakey[$id] = $metakey;
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
			$article = $this->getArticleTitle($id);
			$session_titletag[$id] = $article;
		}
		$_SESSION["session_titletag"] = $session_titletag;
		return true;
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
				$from_text = $intr_full["0"]["introtext"];
				if(trim(strip_tags($intr_full["0"]["introtext"])) == ""){
					$from_text = $intr_full["0"]["fulltext"];
				}
				
				if($params->ijseo_type_desc == "Words"){
					$exclude_key = $params->exclude_key;
					$temp1 = "";
					foreach($exclude_key as $e_key=>$e_value){					
						$temp1 = str_replace(" ".$e_value." ", " ", strip_tags($from_text));
						$from_text = $temp1;
					}
					$temp2 = explode(" ", $temp1);
					$delete = array_splice($temp2, 0, $params->ijseo_allow_no_desc);					
					$desc = implode(" ", $delete);					
				}
				else{
					if(isset($from_text)){
						$exclude_key = $params->exclude_key;
						$temp1 = "";
						foreach($exclude_key as $e_key=>$e_value){
							$temp1 = str_replace(" ".$e_value." ", " ", strip_tags($from_text));
							$from_text = $temp1;
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