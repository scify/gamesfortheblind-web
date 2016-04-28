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

class iJoomla_SeoModelRedirect extends JModelLegacy{

	protected $context = 'com_ijoomla_seo.redirect';
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
		$app = JFactory::getApplication('administrator');
		$limistart = $app->getUserStateFromRequest($this->context.'.list.start', 'limitstart');
		$limit = $app->getUserStateFromRequest($this->context.'.list.limit', 'limit', $config->list_limit);
				
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->clear();
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
		$where = " 1=1 ";
		
		$cat_filter = JRequest::getVar("cat_filter", "0");
		if($cat_filter != "0"){
			$where .= " and ij.catid=".$cat_filter;
		}
		
		$search = JRequest::getVar("search", "");
		if($search != ""){
			$where .= " and ij.name like '%".addslashes($search)."%'";
		}
			
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');				
		$query->select('ij.*, ijc.name as cat_name');
		$query->from('#__ijseo as ij');
		$query->leftJoin('#__ijseo_redirect_category ijc on ij.catid=ijc.id');
		$query->where($where);	
		return $query;		
	}
	
	function resetHit(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$ids = JRequest::getVar("cid");
		foreach($ids as $key=>$value){
			$query->clear();
			$query->update('#__ijseo');
			$query->set("`hits`=0, `last_hit_reset`='".date("Y-m-d H:i:s")."'");
			$query->where('id='.intval($value));
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function remove(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$ids = JRequest::getVar("cid");
		foreach($ids as $key=>$value){
			$query->clear();
			$query->delete('#__ijseo');
			$query->where('id='.$value);
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function getAllCategories(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$app = JFactory::getApplication('administrator');				
		$query->select('id, name');
		$query->from('#__ijseo_redirect_category');
		$db->setQuery($query);
		$db->query();
		return $db->loadObjectList();
	}
}

?>