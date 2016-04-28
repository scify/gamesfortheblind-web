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
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
defined('DS') or define("DS", DIRECTORY_SEPARATOR);

$app = JFactory::getApplication();
if($app->isAdmin()){			
	return;
}
else{
	jimport( 'joomla.plugin.plugin' );
}

class plgSystemIjseo extends JPlugin{    
   
   	function plgSystemIjseo(& $subject, $config){
		parent::__construct($subject, $config);
   	}
	
	function getParams(){
		$database = JFactory::getDBO();
		$sql = "select `params` from #__ijseo_config";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result);	
		return $params;
	}
	
	function onAfterDispatch(){		
		$doc = JFactory::getDocument();
		$style = '.iseo-dashed{
						border-bottom:1px dashed;
						text-decoration:none !important;
				  }';
		$doc->addStyleDeclaration($style);
		
		$seo_title = "";
		$seo_keywords = "";
		$seo_description = "";
		
		$app = JFactory::getApplication();
		$domain = JURI::base();
		$db = JFactory::getDBO();
		
		if($app->isAdmin()){			
			return;
		}
		
		$result = $this->getMetaValues();		
		if(isset($result) && is_array($result) && count($result) > 0){
			$seo_title = $result["metatile"];
			$seo_keywords = $result["metakey"];
			$seo_description = $result["metadesc"];
		}
		$document = JFactory::getDocument();
		
		if(trim($seo_title) != ""){
			$document->setTitle($seo_title);
            $document->setMetaData("title", $seo_title);
		}	
		if(trim($seo_keywords) != ""){
			$params = $this->getParams();
			if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
				$metakey = trim($seo_title);			
				$delimiters = str_split(trim($params->delimiters));			
				$metakey = str_replace($delimiters, ",", $metakey);
				$document->setMetaData("keywords", trim($metakey));
			}
			else{
				$document->setMetaData("keywords", trim($seo_keywords));
			}	
		}	
		if(trim($seo_description) != "") {
			$document->setDescription($seo_description);
		}
		return true;
	}
	
	function getMetaValues(){
		$db = JFactory::getDBO();
		$option = JRequest::getVar('option', '');
		$view = JRequest::getVar('view', '');
		$task = JRequest::getVar('task', '');
		$layout = JRequest::getVar('layout', '');
		$show = JRequest::getVar('show', '');
		$itemid = JRequest::getVar('Itemid', 0);
		$menutype = "";
		$menuName = "";
		$return = "";
		
		if(isset($this->show) && ($show == "" || empty($show)) && $view != 'shop.product_details' && (($view!="itemlist" && $layout!='category') || ($view=="itemlist" && $layout=='category'))){
			$query = " select menutype, id, title, link ".
					 " from #__menu ".
					 " where id = ".intval($itemid);					 				    
			$db->setQuery($query);
			$res = $db->loadAssoc();
			$menutype = $res["menutype"];
			$menuName = $db->Quote($res["title"]);
		}		
		//get meta values for articles, menus or any component		
		$is_menu = $this->isMenu($option, $task, $view);
		
		if(trim($is_menu) == "" && ($view == "article" || $view == "featured")){//for articles
			$return = $this->getArticlesMeta();
		}
		elseif(trim($is_menu) == ""){ // for another components
			$return = $this->getComponentsMeta($option, $task, $view);
		}
		else{//for menus
			$return = $this->getMenusMeta($menutype);
		}
		return $return;
	}
	
	function isMenu($option, $task, $view){
		$id = JRequest::getInt("id");
		$layout = JRequest::getVar("layout", "");
		$view = JRequest::getVar("view", "");
		
		$where = " 1=1 ";
		
		if ($option == "com_easyblog" && ($view == 'categories' || $view == 'entry') && $id > 0) {
			return '';
		}
		
		switch($option){
			case "com_news_portal" : {
				$where .= " and menutype not in ('categories', 'sections', 'news-portal-content') ";
				break;
			}
			case 'com_digistore' : {
				$where .= " and menutype not in ('digicats') ";
				break;
			}
			case 'com_magazine' : {
				$where .= " and menutype not in ('magazine-content', 'magazines') ";
				break;
			}
		}
		
		if(trim($option) != ""){
			$where .= " and link like '%option=".$option."%'";
		}
		if(trim($task) != ""){
			$where .= " and link like '%task=".$task."%'";
		}
		if(trim($view) != ""){
			$where .= " and link like '%view=".$view."%'";
		}
		if($id != ""){
			$where .= " and link like '%id=".$id."%'";
		}
		if($layout != ""){
			$where .= " and link like '%layout=".$layout."%'";
		}
		
		
		if($where != " 1=1 "){
			$sql = "select `menutype` from #__menu where ".$where." and `published`=1";
			$db = JFactory::getDBO();
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadColumn();
			$result = @$result["0"];
			if($result == NULL){
				return "";
			}
			else{
				return trim($result);
			}
		}
	 }
	 
	function getComponentsMeta($option, $task, $view){
		$db = JFactory::getDBO();
		$return_array = array("metatile"=>"", "metakey"=>"", "metadesc"=>"");		
		switch($option){
			case "com_mtree" : {			
				if(trim($task) != "" && trim($task) == "listcats"){
					$id = JRequest::getVar("cat_id", 0);
					$sql = "select mc.metakey, mc.metadesc, sm.titletag from #__mt_cats mc left join #__ijseo_metags sm on mc.cat_id=sm.id where sm.mtype='mt_cat' and mc.cat_id=".intval($id);					
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if(isset($result) && is_array($result) && count($result) > 0){
						if(isset($result["0"]["titletag"])){
							$return_array["metatile"] = trim($result["0"]["titletag"]);
						}
						else{
							$return_array["metatile"] = "";
						}
						
						if(isset($result["0"]["metakey"])){
							$return_array["metakey"] = trim($result["0"]["metakey"]);
						}
						else{
							$return_array["metakey"] = "";
						}
						
						if(isset($result["0"]["metadesc"])){
							$return_array["metadesc"] = trim($result["0"]["metadesc"]);
						}
						else{
							$return_array["metadesc"] = "";
						}
					}
				}
				elseif(trim($task) != "" && trim($task) == "viewlink"){
					$id = JRequest::getVar("link_id", 0);
					$sql = "select ml.metakey, ml.metadesc, sm.titletag from #__mt_links ml left join #__ijseo_metags sm on ml.link_id=sm.id where sm.mtype='mt_list' and ml.link_id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if(isset($result) && is_array($result) && count($result) > 0){
						if(isset($result["0"]["titletag"])){
							$return_array["metatile"] = trim($result["0"]["titletag"]);
						}
						else{
							$return_array["metatile"] = "";
						}
						
						if(isset($result["0"]["metakey"])){
							$return_array["metakey"] = trim($result["0"]["metakey"]);
						}
						else{
							$return_array["metakey"] = "";
						}
						
						if(isset($result["0"]["metadesc"])){
							$return_array["metadesc"] = trim($result["0"]["metadesc"]);
						}
						else{
							$return_array["metadesc"] = "";
						}
					}
				}
				break;
			}
			case "com_community" : {
			/*	echo"<pre>";
				print_r($task);
				die();
			
			*/ 
			  	if(trim($task) != "" && trim($task) == "viewgroup"){
					$id = JRequest::getVar("groupid", 0);
					$sql = "select sm.metakey, sm.metadesc, sm.titletag from #__ijseo_metags sm  where sm.mtype='js_group' and sm.id=".intval($id);					
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if($result != NULL){
						if(isset($result) && is_array($result) && count($result) > 0){
							if(isset($result["0"]["titletag"])){
								$return_array["metatile"] = trim($result["0"]["titletag"]);
							}
							else{
								$return_array["metatile"] = "";
							}
							
							if(isset($result["0"]["metakey"])){
								$return_array["metakey"] = trim($result["0"]["metakey"]);
							}
							else{
								$return_array["metakey"] = "";
							}
							
							if(isset($result["0"]["metadesc"])){
								$return_array["metadesc"] = trim($result["0"]["metadesc"]);
							}
							else{
								$return_array["metadesc"] = "";
							}
						}
					}
				}
				
				elseif(trim($task) != "" && trim($task) == "photo"){
					$id = JRequest::getVar("photoid", 0);
					$sql = "select sm.metakey, sm.metadesc, sm.titletag from #__ijseo_metags sm  where sm.mtype='js_photos' and sm.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if($result != NULL){
						if(isset($result) && is_array($result) && count($result) > 0){
							if(isset($result["0"]["titletag"])){
								$return_array["metatile"] = trim($result["0"]["titletag"]);
							}
							else{
								$return_array["metatile"] = "";
							}
							
							if(isset($result["0"]["metakey"])){
								$return_array["metakey"] = trim($result["0"]["metakey"]);
							}
							else{
								$return_array["metakey"] = "";
							}
							
							if(isset($result["0"]["metadesc"])){
								$return_array["metadesc"] = trim($result["0"]["metadesc"]);
							}
							else{
								$return_array["metadesc"] = "";
							}
						}
					}
				}
				elseif(trim($task) != "" && trim($task) == "viewevent"){
					$id = JRequest::getVar("eventid", 0);
					$sql = "select sm.metakey, sm.metadesc, sm.titletag from #__ijseo_metags sm  where sm.mtype='js_events' and sm.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if($result != NULL){
						if(isset($result) && is_array($result) && count($result) > 0){
							if(isset($result["0"]["titletag"])){
								$return_array["metatile"] = trim($result["0"]["titletag"]);
							}
							else{
								$return_array["metatile"] = "";
							}
							
							if(isset($result["0"]["metakey"])){
								$return_array["metakey"] = trim($result["0"]["metakey"]);
							}
							else{
								$return_array["metakey"] = "";
							}
							
							if(isset($result["0"]["metadesc"])){
								$return_array["metadesc"] = trim($result["0"]["metadesc"]);
							}
							else{
								$return_array["metadesc"] = "";
							}
						}
					}
				}
				elseif(trim($task) != "" && trim($task) == "album"){
					$id = JRequest::getVar("albumid", 0);
					$sql = "select sm.metakey, sm.metadesc, sm.titletag from #__ijseo_metags sm  where sm.mtype='js_albums' and sm.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if($result != NULL){
						if(isset($result) && is_array($result) && count($result) > 0){
							if(isset($result["0"]["titletag"])){
								$return_array["metatile"] = trim($result["0"]["titletag"]);
							}
							else{
								$return_array["metatile"] = "";
							}
							
							if(isset($result["0"]["metakey"])){
								$return_array["metakey"] = trim($result["0"]["metakey"]);
							}
							else{
								$return_array["metakey"] = "";
							}
							
							if(isset($result["0"]["metadesc"])){
								$return_array["metadesc"] = trim($result["0"]["metadesc"]);
							}
							else{
								$return_array["metadesc"] = "";
							}
						}
					}
				}
				elseif(trim($task) != "" && trim($task) == "video"){
					$id = JRequest::getVar("videoid", 0);
					$sql = "select sm.metakey, sm.metadesc, sm.titletag from #__ijseo_metags sm  where sm.mtype='js_videos' and sm.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if($result != NULL){
						if(isset($result) && is_array($result) && count($result) > 0){
							if(isset($result["0"]["titletag"])){
								$return_array["metatile"] = trim($result["0"]["titletag"]);
							}
							else{
								$return_array["metatile"] = "";
							}
							
							if(isset($result["0"]["metakey"])){
								$return_array["metakey"] = trim($result["0"]["metakey"]);
							}
							else{
								$return_array["metakey"] = "";
							}
							
							if(isset($result["0"]["metadesc"])){
								$return_array["metadesc"] = trim($result["0"]["metadesc"]);
							}
							else{
								$return_array["metadesc"] = "";
							}
						}
					}
				}
				break;
			}
			case "com_zoo" : {
				if(trim($task) != "" && trim($task) == "item"){
					$id = JRequest::getVar("item_id", 0);
					$sql = "select it.params from #__zoo_item it where it.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
										
					if(isset($result) && is_array($result) && count($result) > 0){
						$params = json_decode($result["0"]["params"], true);	
						$return_array["metatile"] = trim($params["metadata.title"]);
						$return_array["metakey"] = trim($params["metadata.keywords"]);
						$return_array["metadesc"] = trim($params["metadata.description"]);
					}
				}
				elseif(trim($task) != "" && trim($task) == "category"){
					$id = JRequest::getVar("item_id", 0);
					$sql = "select it.params from #__zoo_category it where it.id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
										
					if(isset($result) && is_array($result) && count($result) > 0){
						$params = json_decode($result["0"]["params"], true);	
						$return_array["metatile"] = trim($params["metadata.title"]);
						$return_array["metakey"] = trim($params["metadata.keywords"]);
						$return_array["metadesc"] = trim($params["metadata.description"]);
					}
				}
				break;
			}
			case "com_k2" : {
				$layout = JRequest::getVar('layout');
				$id = JRequest::getInt("id");
				$seo_params = $this->getComponentParams();
				$view = JRequest::getVar("view");
				
				if(!isset($layout)){
					$layout = $view;
				}
				
				if ($layout == "item" || $layout == "category" || $view == "item" || $view == "category" || $view == "itemlist") {
					if ($layout == "category" || $view == "itemlist") {
						$sql = "SELECT c.`params`, m.titletag FROM #__k2_categories c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-item' WHERE c.`id`=".intval($id);
						$db->setQuery($sql);
						$obj = $db->loadObject();
						
						$params = trim($obj->params);
						$params = json_decode($params, true);
						
						$return_array["metatile"] = trim($obj->titletag);
						$return_array["metakey"] = trim($params["catMetaKey"]);
						$return_array["metadesc"] = trim($params["catMetaDesc"]);
					}
					elseif ($layout == "item" || $view == "item") {
						$sql = "SELECT c.`metadesc`, c.`metakey`, m.`titletag` FROM #__k2_items c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-cat' WHERE c.`id`=".intval($id);
						$db->setQuery($sql);
						$obj = $db->loadObject();
						
						$return_array["metatile"] = trim($obj->titletag);
						$return_array["metakey"] = trim($obj->metakey);
						$return_array["metadesc"] = trim($obj->metadesc);
					}
					
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
				break;
			}
			case "com_easyblog" : {
				$id = JRequest::getInt("id");
				$seo_params = $this->getComponentParams();
				$view = JRequest::getVar("view");
				
				if ($view == "entry" && $id > 0) {
					$sql = "SELECT c.`description`, c.`keywords`, m.`titletag` FROM #__easyblog_meta c LEFT JOIN #__ijseo_metags m on m.`id`=c.`content_id` and m.`mtype` = 'easyblog-item' WHERE c.`type` = 'post' and c.`content_id`=".intval($id);
				}
				elseif ($view == "categories" && $id > 0) {
					$sql = "SELECT c.`description`, c.`keywords`, m.`titletag` FROM #__easyblog_meta c LEFT JOIN #__ijseo_metags m on m.`id`=c.`content_id` and m.`mtype` = 'easyblog-cat' WHERE c.`type` = 'category' and c.`content_id`=".intval($id);
				}
				
				if(($view == "entry" || $view == "categories") && ($id > 0)){
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim(@$obj->titletag);
					$return_array["metakey"] = trim(@$obj->keywords);
					$return_array["metadesc"] = trim(@$obj->description);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
				
				break;
			}
			case "com_kunena" : {
				$view = JRequest::getVar('view');
				$id = JRequest::getInt("catid");
				$seo_params = $this->getComponentParams();
				if ($view == 'listcat' || $view == 'showcat' || $view == "category") {
					$sql = "SELECT * FROM #__ijseo_metags WHERE mtype = 'kunena-cat' AND `id`=".intval($id);
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim(@$obj->titletag);
					$return_array["metakey"] = trim(@$obj->metakey);
					$return_array["metadesc"] = trim(@$obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
				break;
			}

		}
		return $return_array;
	}
	
	function getMenusMeta($menutype){
		$option = JRequest::getVar('option', '', 'get', 'string');
		$view = JRequest::getVar('view', '', 'get', 'string');		
		$itemid = JRequest::getVar('Itemid', 0);
		$params = $this->getComponentParams();		
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";
		//get meta values
		$sql = "select `params` from #__menu where id=".intval($itemid);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		
		if(isset($result) && trim($result) != "" && trim($result) != "{}"){
			$result = json_decode(trim($result), true);
			$metakey = isset($result["menu-meta_keywords"]) ? trim($result["menu-meta_keywords"]) : "";
			$metadesc = isset($result["menu-meta_description"]) ? trim($result["menu-meta_description"]) : "";
			$metatile = isset($result["page_title"]) ? trim($result["page_title"]) : "";			
			if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){				
				$metakey = $metatile;			
				$delimiters = str_split(trim($params->delimiters));			
				$metakey = str_replace($delimiters, ",", $metakey);
			}
		}
		$return_array["metatile"] = trim($metatile);
		$return_array["metakey"] = trim($metakey);
		$return_array["metadesc"] = trim($metadesc);
		
		// Special "hack" for Kunena, if a menu item doesn't exist for e specific category
		if ($option == 'com_kunena') {
			$seo_params = $this->getComponentParams();
			$id = JRequest::getInt('catid');
			
			
			// If a specific Menu Link exists for a category, then leave this alone
			// else return the meta data for that category from SEO
			if (($id) && ($view == 'listcat' || $view == 'showcat')) {
				$sql = "SELECT COUNT(id) FROM `#__menu` WHERE 
						   `link` = 'index.php?option=com_kunena&view=showcat&catid={$id}'
						   OR `link` = 'index.php?option=com_kunena&view=listcat&catid={$id}' ";
				$db->setQuery($sql);
				$specific_menu_item_exists = $db->loadColumn();
				$specific_menu_item_exists = @$specific_menu_item_exists["0"];
				
				if ($specific_menu_item_exists === 0) {
					$sql = "SELECT * FROM #__ijseo_metags WHERE mtype = 'kunena-cat' AND id='{$id}' ";
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim(@$obj->titletag);
					$return_array["metakey"] = trim(@$obj->metakey);
					$return_array["metadesc"] = trim(@$obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}				
				}
			}
		}
		
		// Special "hack" for K2, if a menu item doesn't exist for e specific category / item
		if ($option == 'com_k2') {
			$seo_params = $this->getComponentParams();
			$id = JRequest::getInt('id');
			$view = JRequest::getVar('view');
			$layout = JRequest::getVar('layout');
			$itemid = JRequest::getInt('Itemid');
			
			// if it's an item
			if (($id) && ($view == 'item')) {
				// Check to see if it has a specific item id
				$sql = "SELECT COUNT(id) FROM #__menu 
						   WHERE `link` = 'index.php?option=com_k2&view=item&layout=item&id={$id}' ";
				$db->setQuery($sql);
				$itemid_exists = $db->loadColumn();
				$itemid_exists = @$itemid_exists["0"];
				
				if (!$itemid_exists) {
					// if there isn't any specific item id for the element
					// get the metadata from SEO
					$sql = "SELECT * FROM #__ijseo_metags 
							   WHERE `mtype` = 'k2-item' AND `id` = '{$id}' 
							   LIMIT 1";
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim(@$obj->titletag);
					$return_array["metakey"] = trim(@$obj->metakey);
					$return_array["metadesc"] = trim(@$obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}
			// if it's a category
			} elseif (($id) && ($view == 'itemlist')) {
				// Check to see if it has a specific item id
				$sql = "SELECT COUNT(id) FROM #__menu 
						   WHERE `link` = 'index.php?option=com_k2&view=itemlist&layout=category&task=category&id={$id}' ";
				$db->setQuery($sql);
				$itemid_exists = $db->loadColumn();
				$itemid_exists = @$itemid_exists["0"];
				
				if (!$itemid_exists) {
					// if there isn't any specific item id for the element
					// get the metadata from SEO
					$sql = "SELECT * FROM #__ijseo_metags 
							   WHERE `mtype` = 'k2-cat' AND `id` = '{$id}' 
							   LIMIT 1";
					$db->setQuery($sql);
					$obj = $db->loadObject();
					
					$return_array["metatile"]  = trim(@$obj->titletag);
					$return_array["metakey"] = trim(@$obj->metakey);
					$return_array["metadesc"] = trim(@$obj->metadesc);
					// Get keywords from title field
					if ($seo_params->ijseo_keysource == "1") {
						$return_array["metakey"] = $return_array["metatile"];
					}
				}				
			}
		}
				
		return $return_array;
	}
	
	function getArticlesMeta(){
		$params = $this->getComponentParams();
		$id = JRequest::getVar("id", "");
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";
		//get meta values
		$sql = "select c.`attribs`, c.`metakey`, c.`metadesc`, m.`titletag` from #__content c left join #__ijseo_metags m on m.id=c.id and m.`mtype`='article' where c.`id`=".intval($id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadAssocList();
		
		if(isset($result) && is_array($result) && count($result) > 0){
			if(isset($result["0"]["titletag"])){
				$metatile = trim($result["0"]["titletag"]);
			}
			else{
				$metatile = "";
			}
			
			if(isset($result["0"]["metakey"])){
				$metakey = trim($result["0"]["metakey"]);
			}
			else{
				$metakey = "";
			}
			
			if(isset($result["0"]["metadesc"])){
				$metadesc = trim($result["0"]["metadesc"]);
			}
			else{
				$metadesc = "";
			}
			$attribs = json_decode($result["0"]["attribs"]);
		}
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			$metakey = $metatile;			
			$delimiters = str_split(trim($params->delimiters));			
			$metakey = str_replace($delimiters, ",", $metakey);
		}
		
		$return_array["metatile"] = trim($metatile);
		$return_array["metakey"] = trim($metakey);
		$return_array["metadesc"] = trim($metadesc);		
		return $return_array;
	}
	
	function MenuParams($menu_id){
		$db = JFactory::getDBO();
		$sql = "select `params` from #__menu where `id`=".intval($menu_id);
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result, true);
		return $params;
	}
	
	function getComponentParams(){
		$db = JFactory::getDBO();
		$sql = "select `params` from #__ijseo_config";
		$db->setQuery($sql);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result);
		return $params;
	}
	
	function onContentPrepare(){
		$db = JFactory::getDBO();		
		$params = $this->getComponentParams();
		
		$check_grank=intval($params->ijseo_check_grank);

		if($params->ijseo_gposition){			
			$jnow	=  JFactory::getDate();
			$date	=  $jnow->toSQL();
			$current_date_for_ping = $jnow->toUnix();
			
			$sql = "select max(`checkdate`) from #__ijseo_keys limit 0,1";
			$db->setQuery($sql);
			$db->query();
			$last_ping_date = $db->loadColumn();
			$last_ping_date = @$last_ping_date["0"];
			
			if($current_date_for_ping <= (strtotime($last_ping_date)+30)){
				// no ping, time less then 30 seconds from last ping
			}
			else{
				$Q = " select * from #__ijseo_keys as a where ". 
						 $check_grank."<=(SELECT DATEDIFF('".$date."',a.checkdate))".
						 " OR a.checkdate='0000-00-00 00:00:00' order by checkdate limit 0,20 ";
				$db->setQuery($Q);
				if(!$db->query()){
					echo $db->getErrorMsg();
				}	
				$keys = $db->loadAssocList();
				
				foreach ($keys as $key){
					$this->getKeyRank(trim($key['title']), $key['rank'], $date, $params->ijseo_keysource);
				}
			}
		}
	}
	
	function google_position($total_to_search, $searchquery, $google){
		$searchurl = $_SERVER['HTTP_HOST'];
		
		if(!empty($searchquery) && !empty($searchurl)){
			$query = str_replace(" ","+",$searchquery);
			$query = str_replace("%26","&",$query);
	 
			// The number of hits per page.
			$hits_per_page = 10;
	 
			// Obviously, the total pages / queries we will be doing is
			// $total_to_search / $hits_per_page
	 
			// This will be our rank
			$position = 0;
	 
			for($i=0; $i<$total_to_search; $i+=$hits_per_page){
				// Open the search page.
				// We are filling in certain variables -
				// $query,$hits_per_page and $start.
	 
				$filename = "https://www.google.$google/search?as_q=$query".
				"&num=$hits_per_page&hl=en&ie=UTF-8&btnG=Google+Search".
				"&as_epq=&as_oq=&as_eq=&lr=&as_ft=i&as_filetype=".
				"&as_qdr=all&as_nlo=&as_nhi=&as_occt=any&as_dt=i".
				"&as_sitesearch=&safe=images&start=$i";
				
				if(function_exists('curl_init')) {
					$ch = curl_init($filename); // initialize curl with given url
					curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // add useragent
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
					if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
						curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
					}
					curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // max. seconds to execute
					curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
					$var = curl_exec($ch);
				}
				else{ 
					$var = file_get_contents($filename);
				}
				
				// split the page code by "<h3 class" which tops each result
				$fileparts = explode("<h3 class=", $var);
				
				for($f=1; $f<sizeof($fileparts); $f++) {
					$position++;
					if(strpos($fileparts[$f], $searchurl)){
						return $position;
					}
				}
			}
		}
	}
	
	function getKeyRank($key, $oldrank, $date, $ijseo_keysource){ 
		$database = JFactory::getDBO();
		$params = $this->getComponentParams();
		
		if(!isset($params->ijseo_check_ext) || $params->ijseo_check_ext == ""){
			$params->ijseo_check_ext="com";
		}	
		if(!isset($params->check_nr)){
			$params->check_nr = "10";
		}	
		
		$google_pos = $this->google_position($params->check_nr, $key, $params->ijseo_check_ext);
		
		if(intval($google_pos) > 0){
			$this->updateRank($oldrank, $google_pos, $date, $key, $ijseo_keysource);
		}
		else{
			$this->updateRank($oldrank, 0, $date, $key, $ijseo_keysource);
		}
		
		return intval($google_pos);
	}
	
	function getPageData($url) {
		if(function_exists('curl_init')) {
			$ch = curl_init($url); // initialize curl with given url
			curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']); // add useragent
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // write the response to a variable
			if((ini_get('open_basedir') == '') && (ini_get('safe_mode') == 'Off')) {
				curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true); // follow redirects if any
			}
			curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5); // max. seconds to execute
			curl_setopt($ch, CURLOPT_FAILONERROR, 1); // stop when it encounters an error
			return @curl_exec($ch);
		}
		else {
			return @file_get_contents($url);
		}
	}  
  
	function updateRank($oldrank, $newrank, $currentDate, $key, $ijseo_keysource){
		$key = trim($key);
		$db = JFactory::getDBO();
		$change = 0;
		$mode = -1;
		if($newrank > 0){
			$change = abs($newrank - $oldrank);
		}	
		if($newrank > $oldrank && $oldrank > 0){
			$mode = 0;
		}	
		elseif(($oldrank >0  && $newrank < $oldrank) || ($oldrank == 0 && $newrank >0)){
			$mode = 1;
		}
		
		$sql = "update #__ijseo_keys set rank = ".$newrank." , rchange =  ".$change.", mode = ".$mode." , checkdate = '".$currentDate."' where title = '".mysql_escape_string($key)."' ";
		$db->setQuery($sql);
		if(!$db->query()){
			return $db->getErrorMsg();
		}
		
		//save statistics rank
		$currentDate = strtotime($currentDate);
		$currentDate = date("Y-m-d", $currentDate);
		
		$sql = "select count(*) from #__ijseo_statistics where `check_date` = '".trim($currentDate)."'";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		$count = @$count["0"];
		
		if(intval($oldrank) < intval($newrank)){ // rank_down
			if($count == 0){
				$sql = "insert into #__ijseo_statistics (`check_date`, `rank_up`, `rank_down`, `rank_same`) values ('".$currentDate."', 0, 1, 0)";
				$db->setQuery($sql);
				$db->query();
			}
			else{
				$sql = "update #__ijseo_statistics set `rank_down` = `rank_down`+1 where `check_date`='".$currentDate."'";
				$db->setQuery($sql);
				$db->query();
			}
		}
		elseif(intval($oldrank) > intval($newrank)){ // rank_up
			if($count == 0){
				$sql = "insert into #__ijseo_statistics (`check_date`, `rank_up`, `rank_down`, `rank_same`) values ('".$currentDate."', 1, 0, 0)";
				$db->setQuery($sql);
				$db->query();
			}
			else{
				$sql = "update #__ijseo_statistics set `rank_up` = `rank_up`+1 where `check_date`='".$currentDate."'";
				$db->setQuery($sql);
				$db->query();
			}
		}
		else{ // rank_same
			if($count == 0){
				$sql = "insert into #__ijseo_statistics (`check_date`, `rank_up`, `rank_down`, `rank_same`) values ('".$currentDate."', 0, 0, 1)";
				$db->setQuery($sql);
				$db->query();
			}
			else{
				$sql = "update #__ijseo_statistics set `rank_same` = `rank_same`+1 where `check_date`='".$currentDate."'";
				$db->setQuery($sql);
				$db->query();
			}
		}
		
	}
	
	function onAfterRender(){
		$app = JFactory::getApplication();
		
		if($app->isAdmin()){			
			return;
		}
		
		$view = JRequest::getVar("view", "");
		$option = JRequest::getVar("option", "");

		if($option == "com_community" || $option == "com_zoo" || $option == "com_kunena" || $option == "com_k2" || $option == "com_easyblog" || $option == "com_content"){
			$doc = JFactory::getDocument();
			$style = '.iseo-dashed{
						border-bottom:1px dashed;
						text-decoration:none !important;
				  		}';
			$doc->addStyleDeclaration($style);
			
			if($option == "com_community"){
				$task = JRequest::getVar("task", "");
				$albumid = JRequest::getVar("albumid", "0");
				$photoid = JRequest::getVar("photoid", "0");
				
				if($view == "photos" && $task == "photo" && intval($albumid) > 0 && intval($photoid) > 0){
					return true;
				}
			}
			
			$db	= JFactory::getDBO();
			$body = JResponse::getBody();
			
			$pattern=array();
			$replace=array();
			//take only the body content
					
			$sql = "SELECT params FROM `#__ijseo_config`";
			$db->setQuery($sql);
			$result_params = $db->loadColumn();
			$config_params = @json_decode($result_params["0"]);
			
			if (!isset($config_params->case_sensitive) || ($config_params->case_sensitive == 0)) {
				$sensitive = "i"; // insensitive
			} else {
				$sensitive = ""; // case sensitive
			}
			
			if (isset($config_params->replace_in) && ($config_params->replace_in != "")) {
				$replace_in = "div id=\"" . $config_params->replace_in . "\"";
			} else {
				$replace_in = "body";            
			}
			
			if (isset($config_params->sb_start) 
				&& isset($config_params->sb_end) 
				&& (strlen($config_params->sb_start) > 3) 
				&& (strlen($config_params->sb_end) > 3)) 
			{
				$sb_start = $config_params->sb_start;
				$sb_end = $config_params->sb_end;
			} else {
				$sb_start = "<body([^>]*)>";
				$sb_end = "<\/body>" ;
			}
			
			$regex = '/' . $sb_start . '\s*(.*)\s*' . $sb_end . '/isU';
	
			preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
			$body_content=$out[0];
			$text=$out[0];
			
			$query = "
				SELECT i.*
				FROM #__ijseo_ilinks AS i
				LEFT JOIN #__ijseo_ilinks_articles AS ia ON i.id = ia.ilink_id
				WHERE ( i.published =1 AND ia.article_id = '" . @$this->_article->id . "'  AND i.include_in = 1 )
				OR ( i.published =1 AND i.include_in = 0 )";                 
			$db->setQuery($query);
			$db->query();
			$ilinks = $db->loadAssocList();
			
			$url = "";
			
			if($db->getErrorNum()){
				echo $db->getErrorMsg();
			}
			
			if(count($ilinks) && trim($view) != "category"){
				require_once(JPATH_SITE.DS.'components'.DS.'com_content'.DS.'helpers'.DS.'route.php');			
				foreach($ilinks as $val){
					$params = json_decode($val['params'], true);
					if($option == "com_community" && $params['enable_jomsocial'] == 0){
						continue;
					}
					if($option == "com_zoo" && $params['enable_zoo'] == 0){
						continue;
					}
					if($option == "com_k2" && $params['enable_k2'] == 0){
						continue;
					}
					if($option == "com_kunena" && $params['enable_kunena'] == 0){
						continue;
					}
					if($option == "com_easyblog" && $params['enable_easyblog'] == 0){
						continue;
					}
					
					switch($val['target']){
						case 2:
							$target = '_blank';
							break;
						case 1:
							$target = '_parent';
							break;
					}
					
					switch($val['type']){			
					// Article
						case 1:						
							$query = ' SELECT c.*, '.
									' CASE WHEN CHAR_LENGTH(c.alias) THEN CONCAT_WS(":", c.id, c.alias) ELSE c.id END as slug, '.
									' CASE WHEN CHAR_LENGTH(cc.alias) THEN CONCAT_WS(":", cc.id, cc.alias) ELSE cc.id END as catslug '.
									' FROM #__content as c '.
									' LEFT JOIN #__categories AS cc ON cc.id = c.catid '.		
									' WHERE c.id = '.$val['articleId'];								 
							$db->setQuery( $query );
							$db->query();
							if($db->getErrorNum()){
								echo $db->getErrorMsg();
							}
							else{
								$article = $db->loadAssocList();
							}
							$url = JRoute::_(ContentHelperRoute::getArticleRoute($article[0]['slug'], $article[0]['catslug'], @$article[0]['sectionid'])); 				
							break;
					
					// Menu 	
						case 2:
							$query = " SELECT * ".
									" FROM #__menu ".
									" WHERE id = ".$val['loc_id']; 															
							$db->setQuery($query);
							$db->query();				 				
							if($db->getErrorNum()){
								echo $db->getErrorMsg();
							}					
							else{
								$menu = $db->loadAssocList();
							}
							if(isset($menu[0]['link'])){
								$url = JRoute::_($menu[0]['link'] . "&Itemid=" . $menu[0]['id'] );
							}
							break;
					
					// External URL    
						case 3:								  
							$url = $val['location2'];				     
							break;
					// No link
						case 4:
							$url = "#";
							break;
					}      
					$articolId = JRequest::getVar('id', '', 'get', 'int');
					$itemId = JRequest::getVar('Itemid','');
					$val['name'] = str_replace("/", "\\/", $val['name']);
					$val['name'] = str_replace("-", "\\-", $val['name']);
					if (!isset($val['title'])) { $val['title'] = $val['name']; }
					switch($val['type']){
						case 1:
							if($val['articleId']!=$articolId){
								$temp_replace = $val['name'];
								$temp_replace = str_replace("'", "\'", $temp_replace);							
								if($val["other_phrases"]==1){
									$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))('.$temp_replace.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
									$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
								}
								else{
									$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
									$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
									$replace[] =' <a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
									$replace[] ='<a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
								}
							}
							break;
						case 2:
							if($val['loc_id']!=$itemId){
								$temp_replace = $val['name'];
								$temp_replace = str_replace("'", "\'", $temp_replace);
								if($val["other_phrases"]==1){
									$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))('.$temp_replace.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
									$replace[] ='<a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
								}
								else{								
									$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
									$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
									$replace[] =' <a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
									$replace[] =' <a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
								}							
							}
							break;
						case 4:
							$temp_replace = $val['name'];
							$temp_replace = str_replace("'", "\'", $temp_replace);
							$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
							$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
							$replace[] =' <a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
							$replace[] ='<a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
							break;
						default:
							$temp_replace = $val['name'];
							$temp_replace = str_replace("'", "\'", $temp_replace);
							if($val["other_phrases"]==1){
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))('.$temp_replace.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
								$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
								
								//$pattern[] = '/'.$temp_replace.'(?![^<]*>)/';
								//$replace[] ='<a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
							}
							else{
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
								$replace[] =' <a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
								$replace[] ='<a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
								
								//$pattern[] = '/\b'.$temp_replace.'\b(?![^<]*>)/';
								//$pattern[] = '\'(?!((<.*?)|(<a.*?)))(b'. $temp_replace . 'b)(?!(([^<>]*?)>)|([^>]*?</a>))\'si';
								//$replace[] ='<a class="iseo-dashed" href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
								
							}
							break;
					}
				}
				//replace only the body content
				$text = preg_replace($pattern, $replace, $text);			
				$body = str_replace($body_content, $text, $body);
							
			}
			JResponse::setBody($body);
		}
	}
	
}

?>