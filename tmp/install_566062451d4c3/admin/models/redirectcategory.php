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

class iJoomla_SeoModelRedirectcategory extends JModelLegacy{

	protected $context = 'com_ijoomla_seo.redirectcategory';
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
		
		$where = "1=1";
		$search = JRequest::getVar("search", "");
		$filter_status = JRequest::getVar("filter_status", "");
		
		if(trim($search) != ""){
			$where .= " and `name` like '%".addslashes(trim($search))."%' ";
		}
		
		if(trim($filter_status) != -1 && trim($filter_status) != ""){
			$where .= " and `published`=".intval($filter_status);
		}
		
		$query->select('`id`, `name`, `published`');
		$query->from('#__ijseo_redirect_category');
		$query->where($where);
		return $query;		
	}
	
	function publish(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$ids = JRequest::getVar("cid");
		foreach($ids as $key=>$value){
			$query->clear();
			$query->update('#__ijseo_redirect_category');
			$query->set("`published`=1");
			$query->where('id='.$value);
			$db->setQuery($query);
			if(!$db->query()){
				return false;
			}
		}
		return true;
	}
	
	function unpublish(){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$ids = JRequest::getVar("cid");
		foreach($ids as $key=>$value){
			$query->clear();
			$query->update('#__ijseo_redirect_category');
			$query->set("`published`=0");
			$query->where('id='.$value);
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
		
		if(is_array($ids) && count($ids) > 0){
			foreach($ids as $key=>$value){
				$total_links = JRequest::getVar("total_links_".$value, "0");
				if(intval($total_links) > 0){
					$cant_delete = TRUE;
					$sql = "select `name` from #__ijseo_redirect_category where `id`=".intval($value);
					$db->setQuery($sql);
					$db->query();
					$name = $db->loadColumn();
					$name = $name["0"];
					$categs[] = $name;
					unset($ids[$key]);
				}
			}
		}
		
		if(count($ids) == 0){
			$ids = array("0");
		}
		
		$query->clear();
		$query->delete('#__ijseo_redirect_category');
		$query->where("`id` in(".implode(",",$ids).")");
		$db->setQuery($query);
		if(!$db->query()){
			return false;
		}
		
		if($cant_delete){
			$app = JFactory::getApplication("site");
			$app->redirect("index.php?option=com_ijoomla_seo&controller=redirectcategory", JText::_("COM_IJOOMLA_SEO_THOSE_CATEGS").": ".implode(", ", $categs)." ".JText::_("COM_IJOOMLA_SEO_CANT_BE_DELETED2"), "error");
			return false;
		}
		
		return true;
	}
}

?>