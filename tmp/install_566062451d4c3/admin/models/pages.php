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

class iJoomla_SeoModelPages extends JModelLegacy{

	protected $context = 'com_ijoomla_seo.pages';
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
		$query = $db->getQuery(true);
		$query = $this->getListQuery();
		
		$db->setQuery($query);
		$db->query();
		$result	= $db->loadObjectList();
		$this->_total = count($result);

		$db->setQuery($query, $limistart, $limit);
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
		$this->setState('filter.author', $filter_author,'string');
		
		$filter_missing = $app->getUserStateFromRequest($this->context.'.filter.missing', 'atype','','string');
		$this->setState('filter.missing', $filter_missing,'string');
		
		$filter_state = $app->getUserStateFromRequest($this->context.'.filter.state', 'filter_state','','string');
		$this->setState('filter.published', $filter_state,'string');
		
		$filter_search = $app->getUserStateFromRequest($this->context.'.filter.search', 'search','','string');
		$this->setState('filter.search', $filter_search, 'string');
				
		$where=" 1=1 ";
		
		if(intval($catid)>0){
			$where.= " and catid=".intval($catid);
		}
		
		if($filter_author != "0" && $filter_author != ""){
			$where.= " and created_by=".intval($filter_author);
		}
		
		switch ($filter_missing){
			case "1":
				$where.= " and m.`titletag`='' ";
				break;
			case "2":
				$where.= " and c.metakey='' ";
				break;
			case "3":
				$where.= " and c.metadesc='' ";
				break;
			case "4":
				$where.= " and (m.`titletag`='' or c.metakey='' or c.metadesc='')";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and state=1 ";
				break;
			case "2":
				$where.=" and state=0 ";
				break;
			case "3":
				$where.=" and state=2 ";
				break;
			case "4":
				$where.=" and state=-2 ";
				break;	
			default:
				$where.=" and state <> -2 ";
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (title like '%".addslashes($filter_search)."%' or c.metakey like '%".addslashes($filter_search)."%' or c.metadesc like '%".addslashes($filter_search)."%') ";
		}		
		
		$query->select('c.`id`, c.`title`, c.`metakey`, c.`metadesc`, c.`attribs`, c.`introtext`, c.`fulltext`, m.`titletag`');
		$query->from('#__content c left join #__ijseo_metags m on c.id=m.id and m.`mtype`=\'article\'');
		$query->where($where);
		return $query;		
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
	
	function getAttribs($id){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('attribs');
		$query->from('#__content');
		$query->where("id=".$id);
		$db->setQuery($query);		
		$db->query();
		$result_string = $db->loadColumn();
		$result_string = @$result_string["0"];
		$result = json_decode($result_string);
		return $result;
	}
	
	function savepage(){
		$id = JRequest::getVar("id");
		$mtitle = JRequest::getVar("mtitle", "");
		$metakey = JRequest::getVar("metakey", "");
		$metadesc = JRequest::getVar("metadesc", "");
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$all_dates = JRequest::get('post', JREQUEST_ALLOWRAW);
		
		$article_content = $all_dates["textedit"];
		$article_content = preg_replace('/<hr(.*)>/msU', 'ijoomla_separator', $article_content);
		$article_cont_array = explode('ijoomla_separator', $article_content);
		
		$and = "";
		
		if(isset($article_cont_array) && isset($article_cont_array["0"]) && trim($article_cont_array["0"]) != ""){
			$and .= ", `introtext`='".addslashes(trim($article_cont_array["0"]))."'";
		}
		
		if(isset($article_cont_array) && isset($article_cont_array["1"]) && trim($article_cont_array["1"]) != ""){
			$and .= ", `fulltext`='".addslashes(trim($article_cont_array["1"]))."'";
		}
		
		$query->update('#__content');		
		$query->set("`metakey`='".addslashes(trim($metakey))."', `metadesc`='".addslashes(trim($metadesc))."'".$and);
		$query->where('id='.$id);		
		$db->setQuery($query);
		
		if(!$db->query()){
			return false;
		}
		else{
			$sql = "select count(*) from #__ijseo_metags where `id`=".intval($id)." and `mtype`='article'";
			$db->setQuery($sql);
			$db->query();
			$count = $db->loadColumn();
			$count = $count["0"];
			
			if($count > 0){
				$query->clear();
				$query->update('#__ijseo_metags');		
				$query->set("`titletag`='".addslashes(trim($mtitle))."'");
				$query->where('`id`='.intval($id)." and `mtype`='article'");
				$db->setQuery($query);
				$db->query();
			}
			else{
				$sql = "insert into #__ijseo_metags (`mtype`, `id`, `titletag`) values ('article', ".intval($id).", '".addslashes(trim($mtitle))."')";
				$db->setQuery($sql);
				$db->query();
			}
		}
		
		echo "<script>window.parent.location.reload(true);</script>";
		die();
	}
}

?>