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

class iJoomla_SeoModelVirtuemart extends JModelLegacy{

	var $_pagination = null;
	protected $context = 'com_ijoomla_seo.virtuemart';
	var $_total = 0;

	function __construct () {
		parent::__construct();
		global $option;
		$app = JFactory::getApplication('administrator');
		// Get the pagination request variables
		$limit = $app->getUserStateFromRequest( 'global.list.limit', 'limit', $app->getCfg('list_limit'), 'int' );
		$limitstart = $app->getUserStateFromRequest( $option.'limitstart', 'limitstart', 0, 'int' );
		
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
		
		$virtuemart_option = JRequest::getVar("virtuemart", "1");
		
		if($virtuemart_option == "1"){
			$query = $this->getCategoriesListing();
		}
		elseif($virtuemart_option == "2"){		
			$query = $this->getProductsListing();
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
	
	function existsVirtuemart() {
		$db = JFactory::getDBO();
		$sql = "SHOW TABLES";
		$db->setQuery($sql);
		$tables = $db->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "virtuemart_categories", $tables)) { return false; } else { return true; }
	}	
	
	function getJomsocialCategories(){
		$db = JFactory::getDBO();
		$sql = "select id, name from #__community_groups_category";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		return $result;
	}
	
	function getCategoriesListing(){	
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_virtuemartcat = $app->getUserStateFromRequest($this->context.'.filter.filter_virtuemartcat', 'filter_virtuemartcat', -1);
		$this->setState('filter.filter_virtuemartcat', $filter_virtuemartcat, 'string');
		
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
			
			$this->setState('filter.virtuemartcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}
		
		$where=" c.`virtuemart_category_id`=cat.`virtuemart_category_id` ";
		
		if($filter_virtuemartcat != "-1"){
			$where .= " and c.`virtuemart_category_id` = ".intval($filter_jomsocialcat);
		}
		
		switch ($filter_missing){
			case "1":
				$where.= " and c.`customtitle` like '' ";
				break;
			case "2":
				$where.= " and c.`metakey` like '' ";
				break;
			case "3":
				$where.= " and c.`metadesc` like ''";
				break;
			case "4":
				$where.= " and ( c.`metakey` like '' or c.`metadesc` like ''  or c.`customtitle` like '') ";
				break;
			default:
				break;
		}
		
		switch ($filter_state){
			case "1":
				$where.=" and cat.`published`=1 ";
				break;
			case "2":
				$where.=" and cat.`published`=0 ";
				break;				
			default:
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (c.`category_name` like '%".addslashes($filter_search)."%' or c.`metakey` like '%".addslashes($filter_search)."%' or c.`metadesc` like '%".addslashes($filter_search)."%') ";
		}
		
		$query->clear();
		$query->select('c.virtuemart_category_id, c.category_name, c.`customtitle`, c.`metakey`, c.`metadesc`');
		$query->from('#__virtuemart_categories_en_gb c, #__virtuemart_categories cat');
		$query->where($where);
		return $query;
	}
	
	function getProductsListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$app 		= JFactory::getApplication('administrator');
		
		$filter_virtuemartcat = $app->getUserStateFromRequest($this->context.'.filter.filter_virtuemartcat', 'filter_virtuemartcat', -1);
		$this->setState('filter.filter_virtuemartcat', $filter_virtuemartcat, 'string');
		
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
			$filter_virtuemartcat = "-1";
			$filter_missing = JRequest::getVar("value", "", "get");
			
			$this->setState('filter.virtuemartcat', "-1" ,'string');
			$this->setState('filter.missing', $filter_missing, 'string');
			$this->setState('filter.published', "" ,'string');
			$this->setState('filter.search', "", 'string');
		}
		
		$where=" p.`virtuemart_product_id`=prod.`virtuemart_product_id` and pc.`virtuemart_product_id`=p.`virtuemart_product_id` ";			
		
		if($filter_virtuemartcat != "-1"){
			$where .= " and pc.`virtuemart_category_id` = ".intval($filter_virtuemartcat);
		}			
		
		switch ($filter_missing){
			case "1":
				$where.= " and p.`customtitle` like '' ";
				break;
			case "2":
				$where.= " and p.`metakey` like '' ";
				break;
			case "3":
				$where.= " and p.`metadesc` like ''";
				break;
			case "4":
				$where.= " and ( p.`metakey` like '' or p.`metadesc` like ''  or p.`customtitle` like '') ";
				break;
			default:
				break;
		}	
		
		switch ($filter_state){
			case "1":
				$where.=" and prod.`published`='1' ";
				break;
			case "2":
				$where.=" and prod.`published`='0' ";
				break;				
			default:
				break;
		}
		
		if($filter_search!=""){ 
			$where.=" and (p.`product_name` like '%".addslashes($filter_search)."%' or p.`metakey` like '%".addslashes($filter_search)."%' or p.`metadesc` like '%".addslashes($filter_search)."%') ";
		}
		
		$query->clear();
		$query->select('p.`virtuemart_product_id`, p.`product_name`, p.`customtitle`, p.`metakey`, p.`metadesc`');
		$query->from('#__virtuemart_product_categories pc, #__virtuemart_products_en_gb p, #__virtuemart_products prod');
		$query->where($where);
		return $query;
	}
	
	function save(){
		$virtuemart = JRequest::getVar("virtuemart", "1");
		
		if($virtuemart == "1"){
			if($this->saveCategories()){
				return true;
			}
			return false;
		}
		elseif($virtuemart == "2"){
			if($this->saveProducts()){
				return true;
			}
			return false;
		}
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
			if($page_title[$id] == ""){
				$page_title[$id] = " ";
			}
			
			if($metakey[$id] == ""){
				$metakey[$id] = " ";
			}
			
			if($metadesc[$id] == ""){
				$metadesc[$id] = " ";
			}
																		
			$sql = "update #__virtuemart_categories_en_gb set `customtitle`='".addslashes(trim($page_title[$id]))."', `metakey`='".addslashes(trim($metakey[$id]))."', `metadesc`='".addslashes($metadesc[$id])."' where `virtuemart_category_id`=".intval($id);
			
			$db->setQuery($sql);
			$db->query();
		}
		return true;	
	}

	function saveProducts(){
		$component_params = $this->getComponentParams();		
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		$ids = JRequest::getVar("cid", "", "post", "array");
		$page_title = JRequest::getVar("page_title", "", "post", "array");
		$metakey = JRequest::getVar("metakey", "", "post", "array");
		$metadesc = JRequest::getVar("metadesc", "", "post", "array");
		
		foreach($ids as $key=>$id){
			if($page_title[$id] == ""){
				$page_title[$id] = " ";
			}
			
			if($metakey[$id] == ""){
				$metakey[$id] = " ";
			}
			
			if($metadesc[$id] == ""){
				$metadesc[$id] = " ";
			}
																		
			$sql = "update #__virtuemart_products_en_gb set `customtitle`='".addslashes(trim($page_title[$id]))."', `metakey`='".addslashes(trim($metakey[$id]))."', `metadesc`='".addslashes($metadesc[$id])."' where `virtuemart_product_id`=".intval($id);
			
			$db->setQuery($sql);
			$db->query();
		}
		return true;
	}
	
	function copyKeyToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$virtuemart = JRequest::getVar("virtuemart", "1");
				
        foreach($ids as $key=>$id){
			$sql = "";
			
			if($virtuemart == 1){
				$sql = "update #__virtuemart_categories_en_gb set `customtitle`=`metakey` where `virtuemart_category_id`=".intval($id);
			}
			elseif($virtuemart == 2){
				$sql = "update #__virtuemart_products_en_gb set `customtitle`=`metakey` where `virtuemart_product_id`=".intval($id);
			}
			
			$db->setQuery($sql);
			if(!$db->query()){
				die($db->getQuery());
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
		$virtuemart = JRequest::getVar("virtuemart", "1");
				
        foreach($ids as $key=>$id){
			$sql = "";
			
			if($virtuemart == 1){
				$sql = "update #__virtuemart_categories_en_gb set `metakey`=`customtitle` where `virtuemart_category_id`=".intval($id);
			}
			elseif($virtuemart == 2){
				$sql = "update #__virtuemart_products_en_gb set `metakey`=`customtitle` where `virtuemart_product_id`=".intval($id);
			}
			
			$db->setQuery($sql);
			if(!$db->query()){
				die($db->getQuery());
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
		$virtuemart = JRequest::getVar("virtuemart", "1");
				
        foreach($ids as $key=>$id){
			$sql = "";
			
			if($virtuemart == 1){
				$sql = "update #__virtuemart_categories_en_gb set `metakey`=`category_name` where `virtuemart_category_id`=".intval($id);
			}
			elseif($virtuemart == 2){
				$sql = "update #__virtuemart_products_en_gb set `metakey`=`product_name` where `virtuemart_product_id`=".intval($id);
			}
			
			$db->setQuery($sql);
			if(!$db->query()){
				die($db->getQuery());
				return false;
			}
		}
		return true;
	}
	
	function copyArticleToTitle(){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();
		
		$ids = JRequest::getVar("cid", "", "post", "array");
		$virtuemart = JRequest::getVar("virtuemart", "1");
				
        foreach($ids as $key=>$id){
			$sql = "";
			
			if($virtuemart == 1){
				$sql = "update #__virtuemart_categories_en_gb set `customtitle`=`category_name` where `virtuemart_category_id`=".intval($id);
			}
			elseif($virtuemart == 2){
				$sql = "update #__virtuemart_products_en_gb set `customtitle`=`product_name` where `virtuemart_product_id`=".intval($id);
			}
			
			$db->setQuery($sql);
			if(!$db->query()){
				die($db->getQuery());
				return false;
			}
		}
		return true;
	}
	
	function getVirtuemartGroupCategories(){
		$db = JFactory::getDBO();
		$sql = "select `virtuemart_category_id` as id, `category_name` as name from #__virtuemart_categories_en_gb";
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
		$virtuemart = JRequest::getVar("virtuemart", "1");
		
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
			
			if($virtuemart == "1"){
				$sql = "update #__virtuemart_categories_en_gb set `metadesc`='".addslashes(trim($desc))."' where `virtuemart_category_id`=".intval($id);
				$db->setQuery($sql);
				if(!$db->query()){
					return false;
				}
			}
			elseif($virtuemart == "2"){
				$sql = "update #__virtuemart_products_en_gb set `metadesc`='".addslashes(trim($desc))."' where `virtuemart_product_id`=".intval($id);
				$db->setQuery($sql);
				if(!$db->query()){
					return false;
				}
			}
		}
		return true;
	}
	
	function getAllDesc(){
		$db = JFactory::getDBO();
		$virtuemart = JRequest::getVar("virtuemart", "1");
		$sql = "";
		$result = "";
		
		if($virtuemart == "1"){
			$sql = "select `virtuemart_category_id` as id, `category_description` as metadesc from #__virtuemart_categories_en_gb";
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList("id");
				
		}
		elseif($virtuemart == "2"){
			$sql = "select `virtuemart_product_id` as id, `product_desc` as metadesc from #__virtuemart_products_en_gb";
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