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

defined('_JEXEC') or die;
defined('DS') or define("DS", DIRECTORY_SEPARATOR);

$app = JFactory::getApplication();
if($app->isAdmin()){			
	return;
}
else{
	jimport('joomla.plugin.plugin');
	jimport('joomla.event.plugin');
	require_once(JPATH_BASE .DS.'includes'.DS.'framework.php');
}

class plgContentIjseo_plugin extends JPlugin{
    var $_article;
	
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
	
	function isMenu($option, $task, $view){
		$id = JRequest::getInt("id", "");
		$layout = JRequest::getVar("layout", "");
		$itemid = JRequest::getInt("Itemid", 0);
		
		$where = " 1=1 ";
		
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
		if($itemid > 0) {
			$where .= " AND id= '" . $itemid . "' ";
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
			$sql = "select `menutype` from #__menu where ".$where;
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
	
	function getMenusMeta($menutype){
		$option = JRequest::getVar('option', '');
		$view = JRequest::getVar('view', '');		
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
				
		return $return_array["metakey"];
	}
	
	function getArticlesMeta(){
		$params = $this->getParams();
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
			$metatile = trim(@$result["0"]["titletag"]);
			$metakey = trim(@$result["0"]["metakey"]);
			$metadesc = trim(@$result["0"]["metadesc"]);
			$attribs = json_decode(@$result["0"]["attribs"]);
		}
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			$metakey = $metatile;			
			$delimiters = str_split(trim($params->delimiters));			
			$metakey = str_replace($delimiters, ",", $metakey);
		}
		return trim($metakey);
	}
	
	function getMtreeMeta(){
		$params = $this->getParams();		
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";		
		//get meta values
		$task = JRequest::getVar("task", "");
		
		if($task == "listcats"){
			$id = JRequest::getVar("cat_id");
			$sql = "select mc.metakey, mc.metadesc, sm.titletag from #__mt_cats mc left join #__ijseo_metags sm on mc.cat_id=sm.id where sm.mtype='mt_cat' and mc.cat_id=".intval($id);
			
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
			
			if(isset($result) && is_array($result) && count($result) > 0){
				$metakey = trim(@$result["0"]["metakey"]);
				$metadesc = trim(@$result["0"]["metadesc"]);
				$metatile = trim(@$result["0"]["titletag"]);			
			}
			if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
				$metakey = $metatile;
				$delimiters = str_split(trim($params->delimiters));
				$metakey = str_replace($delimiters, ",", $metakey);
			}
			return trim($metakey);
		}
		elseif($task == "viewlink"){
			$id = JRequest::getVar("link_id", 0);
			$sql = "select ml.metakey, ml.metadesc, sm.titletag from #__mt_links ml left join #__ijseo_metags sm on ml.link_id=sm.id where sm.mtype='mt_list' and ml.link_id=".intval($id);
			
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
			
			if(isset($result) && is_array($result) && count($result) > 0){
				$metakey = trim(@$result["0"]["metakey"]);
				$metadesc = trim(@$result["0"]["metadesc"]);
				$metatile = trim(@$result["0"]["titletag"]);			
			}
			if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
				$metakey = $metatile;
				$delimiters = str_split(trim($params->delimiters));
				$metakey = str_replace($delimiters, ",", $metakey);
			}
			return trim($metakey);
		}
	}
	
	function getZooMeta(){
		$params = $this->getParams();		
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";		
		//get meta values
		$task = JRequest::getVar("task", "");
		if($task == "item"){
			$id = JRequest::getVar("item_id", 0);
			$sql = "select it.params from #__zoo_item it where it.id=".intval($id);
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
								
			if(isset($result) && is_array($result) && count($result) > 0){
				$params2 = json_decode(@$result["0"]["params"], true);	
				$metatile = trim($params2["metadata.title"]);
				$metakey = trim($params2["metadata.keywords"]);
				$metadesc = trim($params2["metadata.description"]);
			}
		}
		elseif($task == "category"){
			$id = JRequest::getVar("category_id", 0);
			$sql = "select it.params from #__zoo_category it where it.id=".intval($id);
			$db->setQuery($sql);
			$db->query();
			$result = $db->loadAssocList();
								
			if(isset($result) && is_array($result) && count($result) > 0){
				$params2 = json_decode(@$result["0"]["params"], true);	
				$metatile = trim($params2["metadata.title"]);
				$metakey = trim($params2["metadata.keywords"]);
				$metadesc = trim($params2["metadata.description"]);
			}
		}
		
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			$metakey = $metatile;
			$delimiters = str_split(trim($params->delimiters));
			$metakey = str_replace($delimiters, ",", $metakey);
		}
		return trim($metakey);
	}
	
	function getComponentParams() {
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

	function getKtwoMeta() {
		$params = $this->getParams();		
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";		
		//get meta values
		$layout = JRequest::getVar("layout", "item");
		$view = JRequest::getInt("view");
		$seo_params = $this->getComponentParams();
		$id = JRequest::getInt('id');
		
		if($layout == "item") {
			$sql = "SELECT c.`params`, m.titletag FROM #__k2_categories c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-item' WHERE c.`id`=".intval($id);
			$db->setQuery($sql);
			$obj = $db->loadObject();
			
			$params2 = trim(@$obj->params);
			$params2 = json_decode($params2, true);
			
			$metatile = trim(@$obj->titletag);
			$metakey = trim($params2["catMetaKey"]);
			$metadesc = trim($params2["catMetaDesc"]);
		}
		elseif($layout == "category"){
			$sql = "SELECT c.`metadesc`, c.`metakey`, m.`titletag` FROM #__k2_items c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-cat' WHERE c.`id`=".intval($id);
			$db->setQuery($sql);
			$obj = $db->loadObject();
			
			$metatile = trim(@$obj->titletag);
			$metakey = trim(@$obj->metakey);
			$metadesc = trim(@$obj->metadesc);
		}
		
		if ($layout == "item" || $layout == "category") {
			// Get keywords from title field
			if ($seo_params->ijseo_keysource == "1") {
				if(trim($metatile) != ""){
					$metakey = $metatile;
				}
			}		
		}
		
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			if(trim($metatile) != ""){
				$metakey = $metatile;
				$delimiters = str_split(trim($params->delimiters));
				$metakey = str_replace($delimiters, ",", $metakey);
			}
		}
		return trim($metakey);
	}

	function getEasyblogMeta() {
		$params = $this->getParams();
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";		
		//get meta values
		$layout = JRequest::getVar("layout", "");
		$seo_params = $this->getComponentParams();
		$id = JRequest::getInt('id');
		$view = JRequest::getVar("view");
		
		if($view == "entry" && $id > 0) {
			$sql = "SELECT c.`description`, c.`keywords`, m.`titletag` FROM #__easyblog_meta c LEFT JOIN #__ijseo_metags m on m.`id`=c.`content_id` and m.`mtype` = 'easyblog-item' WHERE c.`type` = 'post' and c.`content_id`=".intval($id);
		}
		elseif($view == "categories" && $id > 0) {
			$sql = "SELECT c.`description`, c.`keywords`, m.`titletag` FROM #__easyblog_meta c LEFT JOIN #__ijseo_metags m on m.`id`=c.`content_id` and m.`mtype` = 'easyblog-cat' WHERE c.`type` = 'category' and c.`content_id`=".intval($id);
		}

		if (($view == "entry" || $view == "categories") && ($id > 0)) {
			$db->setQuery($sql);
			$obj = $db->loadObject();
			
			$metatile = trim(@$obj->titletag);
			$metakey = trim(@$obj->keywords);
			$metadesc = trim(@$obj->description);
			
			// Get keywords from title field
			if ($seo_params->ijseo_keysource == "1") {
				$metakey = @$obj->titletag;
			}		
		}
		
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			$metakey = $metatile;
			$delimiters = str_split(trim($params->delimiters));
			$metakey = str_replace($delimiters, ",", $metakey);
		}
		return trim($metakey);
	}	


	function getKunenaMeta() {
		$params = $this->getParams();		
		$db = JFactory::getDBO();
		$return_array = array();
		$metakey = "";
		$metadesc = "";
		$metatile = "";		
		//get meta values
		$view = JRequest::getVar("view", "");
		if ($view != 'showcat' && $view != 'listcat') {
			return NULL;
		}
		$seo_params = $this->getComponentParams();
		$id = JRequest::getInt('catid');
		
		$sql = "SELECT * FROM #__ijseo_metags WHERE mtype = 'kunena-cat' AND `id`=".intval($id);
		$db->setQuery($sql);
		$obj = $db->loadObject();
		
		$metatile  = trim(@$obj->titletag);
		$metakey = trim(@$obj->metakey);
		$metadesc = trim(@$obj->metadesc);
		
		// Get keywords from title field
		if ($seo_params->ijseo_keysource == "1") {
			$metakey = $metatile;
		}		
		
		if($params->ijseo_keysource == "1" && trim($params->delimiters) != ""){
			$metakey = $metatile;
			$delimiters = str_split(trim($params->delimiters));
			$metakey = str_replace($delimiters, ",", $metakey);
		}
		return trim($metakey);
	}	
	
	public function onContentBeforeDisplay($context, &$article, &$params, $limitstart = 0) {
        $this->_article = $article;
		$app = JFactory::getApplication();
		$comp_params = $this->getParams();
		$database = JFactory::getDBO();
												
		if(!isset($article->id)){
			return;
		}
		
		$wrap_key = @$comp_params->ijseo_wrap_key;				
		$wrap_partial = @$comp_params->ijseo_wrap_partial;
		
		if(isset($wrap_key) && $wrap_key != "nowrap"){					
			$option = JRequest::getVar('option', '', 'get', 'string');
			$view = JRequest::getVar('view', '', 'get', 'string');
			$task = JRequest::getVar('task', '', 'get', 'string');
			$itemid = JRequest::getVar('Itemid', 0);
			$show = JRequest::getVar('show', '', 'get', 'string');
			$layout = JRequest::getVar('layout', '', 'get', 'string');
			$menutype = "";
			$menuName = "";
			$keywords = "";
			
			if(($show == "" || empty($show)) && $view != 'shop.product_details' && (($view!="itemlist" && $layout!='category') || ($view=="itemlist" && $layout=='category'))){
				$query = " select menutype, id, title, link ".
						 " from #__menu ".
						 " where id = ".intval($itemid);					 				    
				$database->setQuery($query);
				$res = $database->loadAssoc();
				$menutype = $res["menutype"];
				$menuName = $database->Quote($res["title"]);
			}		
			//get meta values for articles, menus or any component
			
			$option = JRequest::getVar('option', '');
			$view = JRequest::getVar('view', '');
			$task = JRequest::getVar('task', '');
		
			$is_menu = $this->isMenu($option, $task, $view);
		
			if(trim($is_menu) == "" && ($view == "article" || $view == "featured")){//for articles
				$keywords = $this->getArticlesMeta();
			}
			elseif(trim($is_menu) == ""){ // for another components
				$return = $this->getComponentsMeta($option, $task, $view);
				$keywords = $return["metakey"];
			}
			else{//for menus
				$return = $this->getMenusMeta($menutype);
				if(is_array($return) && isset($return["metakey"])){
					$keywords = $return["metakey"];
				}
				else{
					$keywords = $return;
				}
			}
			
			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != ""){//if is from title and must be separated by ";|,:"
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			$keys = array_unique(explode(",", $keywords));
			
			/// if Keywords Metatag is like: aaa,bbb,cccc, delete the empty item after the last ,
			if(empty($keys[count($keys)-1])){
				unset($keys[count($keys)-1]);
			}
			//list keywords/phrases
			
			foreach ($keys as $key){
				$key = trim($key);
				
				/*if(@strpos($article->introtext, "=".$key) != FALSE){
					continue;
				}
				if(@strpos($article->fulltext, "=".$key) != FALSE){
					continue;
				}
				if(@strpos($article->text, "=".$key) != FALSE){
					continue;
				}*/
				
				//Wrap partial words - no				
				if($wrap_partial == "0"){
					if(trim($key) != ""){
						if(isset($article->introtext)){
							$article->introtext = $this->frep(" ".$key." ", $article->introtext, '/ '.$key.' /');
						}
						if(isset($article->fulltext)){
							$article->fulltext = $this->frep(" ".$key." ", $article->fulltext, '/ '.$key.' /');
						}
						if(isset($article->text)){
							$article->text = $this->frep(" ".$key." ", $article->text, '/ '.$key.' /');
						}
					}
				}
				else{
					if(trim($key) != ""){
						if(isset($article->introtext)){
							$article->introtext = $this->frep($key, $article->introtext, '/'.$key.'/');
						}
						if(isset($article->fulltext)){
							$article->fulltext = $this->frep($key, $article->fulltext, '/'.$key.'/');
						}
						if(isset($article->text)){
							$article->text = $this->frep($key, $article->text, '/'.$key.'/');
						}
					}
				}
				
				/// Exclude/delete $wrap_key from href, title, alt, class, src tags attributes           				
				$regex_mod_plg = '/\{.*?\}/i';				
				$this->remove_wrapkeys($regex_mod_plg, $article, $wrap_key);				
				$regexs = '/(href|title|alt|src|class|base|flashvars)="?[^\"]*"?/i';			
				$this->remove_wrapkeys($regexs, $article, $wrap_key);	
			}//foreach
		}//if
		
		//Images
		if(isset($comp_params->ijseo_Image_when) && $comp_params->ijseo_Image_when != "Never"){
			$crt=1;
			$string='<img';
			if(isset($article->text)){
				$intro_tag = $article->text;
				$original_text = $article->text;
			}
			elseif(isset($article->introtext)){
				$intro_tag = $article->introtext;
				$original_text = $article->introtext;
			}
			$fisier=$intro_tag;
			$pos1 = @strpos($fisier,$string);
			
			while($pos1){
				$fisier=substr($fisier,$pos1+strlen($string),strlen($fisier));
				$pos2=strpos($fisier,'>');
				$url=substr($fisier,0,$pos2);
				$aaa[$crt] = $url;
				$crt++;
				$pos1=strpos($fisier,$string);
			}            

			for($t = 1; $t < $crt; $t++){
				$string = 'alt="';
				$intro_tag = $aaa[$t];
				$fisier = $intro_tag;
				
				$pos1 = strpos($fisier, $string);
				
				if($pos1 == ""){
					$bbb[$t] = "**";
				}
				
				while($pos1){
					$fisier = substr($fisier, $pos1+strlen($string), strlen($fisier));
					$pos2 = strpos($fisier, '"');
					$url = substr($fisier, 0, $pos2);
					$bbb[$t] = $url;
					$pos1 = strpos($fisier, $string);
				}
	
				$string = 'src="';
				$intro_tag = $aaa[$t];
				$fisier = $intro_tag;
				$pos1 = strpos($fisier, $string);
				while ($pos1){
					$fisier = substr($fisier, $pos1+strlen($string), strlen($fisier));
					$pos2 = strpos($fisier, '"');
					$url = substr($fisier, 0, $pos2);
					$ccc[$t] = $url;
					$pos1 = strpos($fisier, $string);
				}
				
				$string = 'title="';
				$intro_tag = $aaa[$t];
				$fisier = $intro_tag;
				$pos1 = strpos($fisier, $string);
				
				if($pos1 == ""){
					$ddd[$t] = "**";
				}
				
				while ($pos1){
					$fisier = substr($fisier, $pos1+strlen($string), strlen($fisier));
					$pos2 = strpos($fisier, '"');
					$title = substr($fisier, 0, $pos2);
					$ddd[$t] = $title;
					$pos1 = strpos($fisier, $string);
				}
			}
			
			// $bbb - array of default alt text
			// $ccc - array of imgs url
			// $ddd - array of title text
			$database->setQuery("SELECT `metakey` FROM #__content where `id`= ".intval($article->id));
			$keywords = $database->loadColumn();
			$keywords = @$keywords["0"];

			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != ""){//if is from title and must be separated by ";|,:"
				$database->setQuery("SELECT `titletag` FROM #__ijseo_metags where `mtype`='article' and `id`= ".intval($article->id));
				$keywords = $database->loadColumn();
				$keywords = @$keywords["0"];
				
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			
			if(!empty($keywords)){				
				if(isset($comp_params->ijseo_Image_what) && $comp_params->ijseo_Image_what=="up to"){	
					$keys = explode(",", $keywords, $comp_params->ijseo_Image_number+1);
					// remove the elements from positions > $_POST["Image_number"]
					$keys = array_slice($keys, 0, $comp_params->ijseo_Image_number);
					$keywords = implode(",", $keys);
				}
				//if is set only
				else{
					$keys = explode(",", $keywords);
					// remove the elements from positions > $_POST["Image_number"]
					$keys = array_slice($keys, $comp_params->ijseo_Image_number-1, 1);
					$keywords = implode("", $keys);
					$keywords = trim($keywords);
				}
				
				$final = $original_text;
				
				for ($t = 1; $t < $crt; $t++) {
					$added_title = false;
					
					if (isset($comp_params->ijseo_Image_when) && $comp_params->ijseo_Image_when=="Always") {
						$added_title = true;
						$final = str_replace('alt="'.$bbb[$t].'"', 'alt="'.$keywords.'" title="'.$keywords.'" ' , $final);
					}

					if ($bbb[$t]=="**" && ($comp_params->ijseo_Image_when=="NotSpecified" || $comp_params->ijseo_Image_when=="Always")) {
						$added_title = true;
						$final = str_replace('src="'.$ccc[$t].'"', 'src="'.$ccc[$t].'" alt="'.$keywords.'" title="'.$keywords.'" ', $final);
					}
					
					if ($bbb[$t]=="" && ($comp_params->ijseo_Image_when=="NotSpecified" || $comp_params->ijseo_Image_when=="Always")) {
						$added_title = true;
						$final = str_replace('alt="'.$bbb[$t].'"', 'alt="'.$keywords.'" title="'.$keywords.'" ' , $final);
					}
					
					if(!$added_title){
						if ($ddd[$t]=="**" && ($comp_params->ijseo_Image_when=="NotSpecified" || $comp_params->ijseo_Image_when=="Always")) {
							$final = str_replace('alt="'.$bbb[$t].'"', 'alt="'.$bbb[$t].'" title="'.$keywords.'" ' , $final);
						}
					}
				}
				if(isset($article->text)){
					$article->text = $final;
				}
				else{
					$article->introtext = $final;
				}
			}
		 }//if
		
		if(isset($article->text) && @strpos($article->text, 'ijseo_redirect' ) === false ){
			return;
		}
		// define the regular expression for the bot
		$regex = '/{(ijseo_redirect)\s*(.*?)}/i';
		
		if(isset($article->introtext)){
			$article->introtext = $this->plg_ijseo_redirect_replacer($article->introtext, $regex);
		}
		if(isset($article->fulltext)){
			$article->fulltext = $this->plg_ijseo_redirect_replacer($article->fulltext, $regex);
		}
		if(isset($article->text)){
			$article->text = $this->plg_ijseo_redirect_replacer($article->text, $regex);
		}		
		//return $article->text;
	}
	
	function MtreeWrapKeywords(){
		$comp_params = $this->getParams();
		$database = JFactory::getDBO();
		
		$wrap_key = $comp_params->ijseo_wrap_key;				
		$wrap_partial = $comp_params->ijseo_wrap_partial;
		
		if(isset($wrap_key) && $wrap_key != "nowrap"){
			$keywords = $this->getMtreeMeta();
			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != ""){//if is from title and must be separated by ";|,:"
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			$keys = array_unique(explode(",", $keywords));
						
			if(isset($keys) && is_array($keys) && count($keys) > 0){
				$body = JResponse::getBody();
                $sql = "SELECT params FROM `#__ijseo_config`";
                $database->setQuery($sql);
				$result_params = $database->loadColumn();
                $config_params = @json_decode($result_params["0"]);
                
                if (isset($config_params->replace_in) && ($config_params->replace_in != "")) {
                    $replace_in = "div id=\"" . $config_params->replace_in . "\"";
                } else {
                    $replace_in = "body";            
                }
                
                $regex = '/<' . $replace_in . '([^>]*)>\s*(.*)\s*<\/body>/isU';
				preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
				$body_content = $out[0];
				$text = $out[0];
				foreach($keys as $poz=>$key){
					if(trim($key) != ""){
						$pattern = str_replace("/", "\\/", $key);
						
						if($wrap_partial == "0"){
							$text = $this->frep($key, $text, '/\b'.$pattern.'\b/');
						}
						else{
							$text = $this->frep($key, $text, '/'.$pattern.'/');
						}
					}
				}//foreach
				$body = str_replace($body_content, $text, $body);
				JResponse::setBody($body);
			}//if we have keys
		}//if wrap key
	}
	
	function ZooWrapKeywords(){
		$comp_params = $this->getParams();
		$database = JFactory::getDBO();
		
		$wrap_key = $comp_params->ijseo_wrap_key;				
		$wrap_partial = $comp_params->ijseo_wrap_partial;
		
		if(isset($wrap_key) && $wrap_key != "nowrap"){
			$keywords = $this->getZooMeta();
			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != ""){//if is from title and must be separated by ";|,:"
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			$keys = array_unique(explode(",", $keywords));
						
			if(isset($keys) && is_array($keys) && count($keys) > 0){
				$body = JResponse::getBody();
                $sql = "SELECT params FROM `#__ijseo_config`";
                $database->setQuery($sql);
                $result_params = $database->loadColumn();
				$config_params = @json_decode($result_params["0"]);
                
                if (isset($config_params->replace_in) && ($config_params->replace_in != "")) {
                    $replace_in = "div id=\"" . $config_params->replace_in . "\"";
                } else {
                    $replace_in = "body";            
                }
                
                $regex = '/<' . $replace_in . '([^>]*)>\s*(.*)\s*<\/body>/isU';
				preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
				$body_content = $out[0];
				$text = $out[0];
				foreach($keys as $poz=>$key){
					if(trim($key) != ""){
						if($wrap_partial == "0"){
							$text = $this->frep($key, $text, '/\b'.$key.'\b/');
						}
						else{
							$text = $this->frep($key, $text, '/'.$key.'/');
						}
					}
				}//foreach
				$body = str_replace($body_content, $text, $body);
				JResponse::setBody($body);
			}//if we have keys
		}//if wrap key
	}
	
	function KtwoWrapKeywords() {
		$comp_params = $this->getParams();
		$database = JFactory::getDBO();
		
		$wrap_key = $comp_params->ijseo_wrap_key;				
		$wrap_partial = $comp_params->ijseo_wrap_partial;
		
		if(isset($wrap_key) && $wrap_key != "nowrap"){
			$keywords = $this->getKtwoMeta();
			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != ""){//if is from title and must be separated by ";|,:"
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			$keys = array_unique(explode(",", $keywords));
						
			if(isset($keys) && is_array($keys) && count($keys) > 0){
				$body = JResponse::getBody();
                $sql = "SELECT params FROM `#__ijseo_config`";
                $database->setQuery($sql);
				$result_params = $database->loadColumn();
                $config_params = @json_decode($result_params["0"]);
                
                if (isset($config_params->replace_in) && ($config_params->replace_in != "")) {
                    $replace_in = "div id=\"" . $config_params->replace_in . "\"";
                } else {
                    $replace_in = "body";            
                }
                
                $regex = '/<' . $replace_in . '([^>]*)>\s*(.*)\s*<\/body>/isU';
				preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
				$body_content = $out[0];
				$text = $out[0];
				foreach($keys as $poz=>$key){
					$key = trim($key);
					if(trim($key) != ""){
						if($wrap_partial == "0"){
							$text = $this->frep($key, $text, '/\b'.$key.'\b/');
						}
						else{
							$text = $this->frep($key, $text, '/'.$key.'/');
						}
					}
				}//foreach
				$body = str_replace($body_content, $text, $body);
				JResponse::setBody($body);
			}//if we have keys
		}//if wrap key	
	}

	function EasyblogWrapKeywords() {
		$comp_params = $this->getParams();
		$database = JFactory::getDBO();
		
		$wrap_key = $comp_params->ijseo_wrap_key;				
		$wrap_partial = $comp_params->ijseo_wrap_partial;
		
		if(isset($wrap_key) && $wrap_key != "nowrap"){
			$keywords = $this->getEasyblogMeta();
			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != ""){//if is from title and must be separated by ";|,:"
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			$keys = array_unique(explode(",", $keywords));
						
			if(isset($keys) && is_array($keys) && count($keys) > 0){
				$body = JResponse::getBody();
                $sql = "SELECT params FROM `#__ijseo_config`";
                $database->setQuery($sql);
				$result_params = $database->loadColumn();
                $config_params = @json_decode($result_params["0"]);
                
                if (isset($config_params->replace_in) && ($config_params->replace_in != "")) {
                    $replace_in = "div id=\"" . $config_params->replace_in . "\"";
                } else {
                    $replace_in = "body";            
                }
                
                $regex = '/<' . $replace_in . '([^>]*)>\s*(.*)\s*<\/body>/isU';
				preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
				$body_content = $out[0];
				$text = $out[0];
				foreach($keys as $poz=>$key){
					$key = trim($key);
					if(trim($key) != ""){
						if($wrap_partial == "0"){
							$text = $this->frep($key, $text, '/\b'.$key.'\b/');
						}
						else{
							$text = $this->frep($key, $text, '/'.$key.'/');
						}
					}
				}//foreach
				$body = str_replace($body_content, $text, $body);
				JResponse::setBody($body);
			}//if we have keys
		}//if wrap key	
	}
	
	
	function KunenaWrapKeywords() {
		$comp_params = $this->getParams();
		$database = JFactory::getDBO();
		
		$wrap_key = $comp_params->ijseo_wrap_key;				
		$wrap_partial = $comp_params->ijseo_wrap_partial;
		
		if(isset($wrap_key) && $wrap_key != "nowrap"){
			$keywords = $this->getKunenaMeta();
			if($comp_params->ijseo_keysource == "1" && trim($comp_params->delimiters) != "") {
			    //if is from title and must be separated by ";|,:"
				$delimiters = str_split(trim($comp_params->delimiters));			
				$keywords = str_replace($delimiters, ",", $keywords);
			}
			$keys = array_unique(explode(",", $keywords));
						
			if(isset($keys) && is_array($keys) && count($keys) > 0){
				$body = JResponse::getBody();
                $sql = "SELECT params FROM `#__ijseo_config`";
                $database->setQuery($sql);
				$result_params = $database->loadColumn();
                $config_params = @json_decode($result_params["0"]);
                
                if (isset($config_params->replace_in) && ($config_params->replace_in != "")) {
                    $replace_in = "div id=\"" . $config_params->replace_in . "\"";
                } else {
                    $replace_in = "body";            
                }
                
                $regex = '/<' . $replace_in . '([^>]*)>\s*(.*)\s*<\/body>/isU';
				preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
				$body_content = $out[0];
				$text = $out[0];
				foreach($keys as $poz=>$key){
					$key = trim($key);
					if(trim($key) != ""){
						if($wrap_partial == "0"){
							$text = $this->frep($key, $text, '/\b'.$key.'\b/');
						}
						else{
							$text = $this->frep($key, $text, '/'.$key.'/');
						}
					}
				}//foreach
				$body = str_replace($body_content, $text, $body);
				JResponse::setBody($body);
			}//if we have keys
		}//if wrap key	
	}	
	
	function plg_ijseo_redirect_replacer($text, $pattern){					
		@preg_match_all($pattern, $text, $matches);
		$db = JFactory::getDBO();
		
		if(isset($matches) && isset($matches["2"]))
		foreach($matches["2"] as $key=>$value){
			$temp = explode("=", trim($value));			
			$text = str_replace('&amp;', '&', $text);
			
			parse_str( html_entity_decode($text));
			
			$query = "SELECT * FROM #__ijseo WHERE id=".intval($temp["1"]);
			
			$db->setQuery($query);
			if(!$db->query()){
				echo $db->getErrorMsg();
				return;
			}
			$redirectrow = $db->loadObjectList();
			if(empty($redirectrow)){
				return;
			}	
			$redirect_row = $redirectrow[0];
		
			if($redirect_row->link_text != ''){
				$link_text = $redirect_row->link_text;
			}
			else{
				$link_text = $redirect_row->name;
			}
		
			 //Check for "rel_nofollow"
			 if($redirect_row->rel_nofollow == 1){
				$rel_nofollow = ' rel="nofollow"';
			 }
			 else{
				$rel_nofollow = '';
			 }
		
			 $uri = JURI::getInstance();
			 $prefix = $uri->toString(array('scheme', 'host', 'port'));
			 $url = $prefix.JRoute::_('index.php?option=com_ijoomla_seo&id='.intval($temp["1"]));
			 
			 //Check to see if this there is an image associated with this redirect.			 
			 if($redirect_row->image == ''){
				$redirect_output = '<a href="'.$url.'" target="'.$redirect_row->target.'"'.$rel_nofollow.'>'.$link_text.'</a>';
			 }
			 else{
				$redirect_output = '<a href="'.$url.'" target="'.$redirect_row->target.'"'.$rel_nofollow.'><img src="'.'images/ijseo_redirects/'.$redirect_row->image.'" alt="'.$link_text.'" title="'.$link_text.'" border="0" /></a>';
			 }			
			//$text = preg_replace($pattern, $redirect_output, $text);
			$text = str_replace('{ijseo_redirect id='.intval($temp["1"]).'}', $redirect_output, $text);
		}//foreach
		return $text;
	}	
	
	function onAfterRender(){		
		$app = JFactory::getApplication();
		
		if($app->isAdmin()){			
			return;
		}
		//wrap keywords for mtree component, because this has not view and not execute "onContentBeforeDisplay" function
		$view = JRequest::getVar("view", "");
		$option = JRequest::getVar("option", "");
		if ($option == "com_mtree") {
			$this->MtreeWrapKeywords();
		} elseif ($option == "com_zoo") {
			$this->ZooWrapKeywords();
		} elseif ($option == "com_k2") {
			$this->KtwoWrapKeywords();
		} elseif ($option == "com_easyblog") {
			$this->EasyblogWrapKeywords();
		} elseif ($option == "com_kunena") {
			$this->KunenaWrapKeywords();
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
				//$val['name'] = str_replace("/", "\\/", $val['name']);
				//$val['name'] = str_replace("-", "\\-", $val['name']);
                if (!isset($val['title'])) { $val['title'] = $val['name']; }
				switch($val['type']){
					case 1:
						if($val['articleId']!=$articolId){
							$temp_replace = $val['name'];
							$temp_replace = str_replace("'", "\'", $temp_replace);							
							if($val["other_phrases"]==1){
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))('.$temp_replace.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
								$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
							}
							else{
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
								$replace[] =' <a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
								$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
							}
						}
						break;
					case 2:
						if($val['loc_id']!=$itemId){
							$temp_replace = $val['name'];
							$temp_replace = str_replace("'", "\'", $temp_replace);
							if($val["other_phrases"]==1){
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))('.$temp_replace.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
								$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
							}
							else{								
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
								$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
								
								$replace[] =' <a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
								$replace[] =' <a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
							}							
						}
						break;
                    case 4:
						$temp_replace = $val['name'];
						$temp_replace = str_replace("'", "\'", $temp_replace);
                        $pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
                        $pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
                        $replace[] =' <a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
                        $replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
                        break;
					default:
						$temp_replace = $val['name'];
						$temp_replace = str_replace("'", "\'", $temp_replace);
						if($val["other_phrases"]==1){
							$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))('.$temp_replace.')(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>))\'s'.$sensitive;
							$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
						}
						else{
							$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b '.$temp_replace.' \b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
							$pattern[] = '\'(?!((<.*?)|(<a.*?)|(<script.*?)|(<style.*?)|(data-jmodediturl=")))(\b'.$temp_replace.'\b)(?!(([^<>]*?)>)|([^>]*?</a>)|([^>]*?</span></a>)|([^>]*?</script>)|([^>]*?</style>)|([^>]*?"))\'s'.$sensitive;
							$replace[] =' <a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a> ';
							$replace[] ='<a href="'.$url.'" title="'.$val['title'].'" target="'.$target.'" >'.$val['name'].'</a>';
						}
						break;
				}
			}
			//replace only the body content
			$text = preg_replace($pattern, $replace, $text);			
			$body = str_replace($body_content, $text, $body);
						
		}
		JResponse::setBody($body);
		$this->titleplg($article, $params);
	}
	
	function titleplg(&$article, &$params){				
		$comp_params = $this->getParams();	
		
		$className1 = $comp_params->ijseo_Replace1;
		$className2 = $comp_params->ijseo_Replace2;
		$className3 = $comp_params->ijseo_Replace3;
		$className4 = $comp_params->ijseo_Replace4;
		$className5 = $comp_params->ijseo_Replace5;
	
		$replaceH1 = $comp_params->ijseo_Replace1_with;
		$replaceH2 = $comp_params->ijseo_Replace2_with;
		$replaceH3 = $comp_params->ijseo_Replace3_with;
		$replaceH4 = $comp_params->ijseo_Replace4_with;
		$replaceH5 = $comp_params->ijseo_Replace5_with;       
	
		if(!empty($className1) && $replaceH1!='noreplace'){							
			$this->replaceClass($article, $params, $className1, $replaceH1);			
		}
		if(!empty($className2) && $replaceH2!='noreplace'){			
			$this->replaceClass($article, $params, $className2, $replaceH2);
		}
		if(!empty($className3) && $replaceH3!='noreplace'){
			$this->replaceClass($article, $params, $className3, $replaceH3);
		}
		if(!empty($className4) && $replaceH4!='noreplace'){
			$this->replaceClass($article, $params, $className4, $replaceH4);
		}
		if(!empty($className5) && $replaceH5!='noreplace'){
			$this->replaceClass($article, $params, $className5, $replaceH5);
		}
		if(!empty($className6) && $replaceH6!='noreplace'){
			$this->replaceClass($article, $params, $className6, $replaceH6);
		}
	}
	
	function replaceClass(&$article, &$params, $className, $replaceH){		
		//ex: <span class="$className">text here</span> become <$replaceH>text here</$replaceH>
		$replaceH = strtolower($replaceH);
		$document = JFactory::getDocument();		
		$body = JResponse::toString();		
		if(strpos($body, 'class="'.$className.'"') !== false){										
			$regexs = array('a','h1','h2','h3','h4','h5','h6','div','td','span'); // all tabs for replace
			$parents = array('h1','h2','h3','h4','h5','h6');
			foreach($regexs as $tag){
				$regex = "";
				if($tag=='a'){					
					$regex = '/<'.$tag.'([^>]*)class="'.$className.'"([^>]*)>\s*(.*)\s*<\/'.$tag.'>/isU';   					
				}
				else{
					$regex = '/<'.$tag.' class="'.$className.'"([^>]*)>\s*(.*)\s*<\/'.$tag.'>/isU'; 					 					
				}
				//get all matches
				preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);
				if($tag=='a'){
					$out[2]=$out[3];
				}
				
				$i = 0;
				foreach($out[2] as $match){					
					if($tag == 'td'){
						$replTag = "<".$tag." width='100%'><".$replaceH.">".$match."</".$replaceH."></".$tag.">";						
					}	
					else{		
						$replTag = "<".$replaceH.">".$match."</".$replaceH.">";
						if(!$this->checkParentNode($parents, $out[0][$i], $body)){
							$body = str_replace( $out[0][$i], $replTag, $body);
							JResponse::setBody($body);							
						}	
						$i++;
					}
				}
			}			
		}		
	}
	
	function checkParentNode($parents, $find, $body){
		$find = str_replace("/", "\\/", $find);		
		foreach($parents as $parent){
			$regex = '/<'.$parent.'(.*)>(.*)'.$find.'(.*)<\/'.$parent.'>/';						
			preg_match_all($regex, $body, $out, PREG_PATTERN_ORDER);			
			if(isset($out["0"]) && count($out["0"]) > 0){
				return true;
			}
		}
		return false;
	}
	
	function frep($key, $text, $pattern){
		$comp_params = $this->getParams();
		$wrap_key = $comp_params->ijseo_wrap_key;
		
		$result = preg_replace($pattern, "<".$wrap_key.">".$key."</".$wrap_key.">", $text);
		
		$result = str_replace("/<strong>", "/", $result);
		$result = str_replace("</strong>/", "/", $result);
		
		$result = str_replace("/<b>", "/", $result);
		$result = str_replace("</b>/", "/", $result);
		
		$result = str_replace("/<u>", "/", $result);
		$result = str_replace("</u>/", "/", $result);
		
		// replace strong, b and ul from alt and title from images -----------------------------------------------------------------------
		preg_match_all('/alt="(.*)"/msU', @$result["0"], $matches);
		if(isset($matches) && count($matches) > 0){
			foreach($matches as $key=>$alt){
				$original_alt = $alt;
				$alt = str_replace("<strong>", "", $alt);
				$alt = str_replace("</strong>", "", $alt);
				
				$alt = str_replace("<b>", "", $alt);
				$alt = str_replace("</b>", "", $alt);
				
				$alt = str_replace("<u>", "", $alt);
				$alt = str_replace("</u>", "", $alt);
				
				@$result["0"] = str_replace($original_alt, $alt, @$result["0"]);
			}
		}
		
		preg_match_all('/title="(.*)"/msU', @$result["0"], $matches);
		if(isset($matches) && count($matches) > 0){
			foreach($matches as $key=>$title){
				$original_title = $title;
				$title = str_replace("<strong>", "", $title);
				$title = str_replace("</strong>", "", $title);
				
				$title = str_replace("<b>", "", $title);
				$title = str_replace("</b>", "", $title);
				
				$title = str_replace("<u>", "", $title);
				$title = str_replace("</u>", "", $title);
				
				@$result["0"] = str_replace($original_title, $title, @$result["0"]);
			}
		}
		// replace strong, b and ul from alt and title from images -----------------------------------------------------------------------
		
		return $result;		
	}
	
	function remove_wrapkeys($regex, &$article, $wrap_key){		
		if(isset($article->text)){
			@preg_match_all($regex, $article->text, $out);
			if(isset($out) && isset($out["0"]))
			foreach($out[0] as $link){
				$regrep = preg_replace('#</?'.$wrap_key.'>#i', '', $link);
				$article->text = str_replace($link, $regrep, $article->text);				
			}
		}
		else{
			preg_match_all($regex, $article->introtext, $out);
			foreach($out[0] as $link){
				$regrep = preg_replace('#</?'.$wrap_key.'>#i', '', $link);
				$article->text = str_replace($link, $regrep, $article->introtext);
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
						$return_array["metatile"] = trim(@$result["0"]["titletag"]);
						$return_array["metakey"] = trim(@$result["0"]["metakey"]);
						$return_array["metadesc"] = trim(@$result["0"]["metadesc"]);
					}
				}
				elseif(trim($task) != "" && trim($task) == "viewlink"){
					$id = JRequest::getVar("link_id", 0);
					$sql = "select ml.metakey, ml.metadesc, sm.titletag from #__mt_links ml left join #__ijseo_metags sm on ml.link_id=sm.id where sm.mtype='mt_list' and ml.link_id=".intval($id);
					$db->setQuery($sql);
					$db->query();
					$result = $db->loadAssocList();
					if(isset($result) && is_array($result) && count($result) > 0){
						$return_array["metatile"] = trim(@$result["0"]["titletag"]);
						$return_array["metakey"] = trim(@$result["0"]["metakey"]);
						$return_array["metadesc"] = trim(@$result["0"]["metadesc"]);
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
						$params = json_decode(@$result["0"]["params"], true);	
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
						$params = json_decode(@$result["0"]["params"], true);	
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
				
				if ($layout == "item" || $layout == "category" || $view == "item" || $view == "category" || $view == "itemlist") {
					if ($layout == "item" || $view == "item") {
						$sql = "SELECT c.`params`, m.titletag FROM #__k2_categories c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-item' WHERE c.`id`=".intval($id);
						$db->setQuery($sql);
						$obj = $db->loadObject();
						
						$params = trim(@$obj->params);
						$params = json_decode($params, true);
						
						$return_array["metatile"] = trim(@$obj->titletag);
						$return_array["metakey"] = trim($params["catMetaKey"]);
						$return_array["metadesc"] = trim($params["catMetaDesc"]);
					}
					elseif ($layout == "category" || $view == "itemlist") {
						$sql = "SELECT c.`metadesc`, c.`metakey`, m.`titletag` FROM #__k2_items c LEFT JOIN #__ijseo_metags m on m.id=c.id and m.`mtype`='k2-cat' WHERE c.`id`=".intval($id);
						$db->setQuery($sql);
						$obj = $db->loadObject();
						
						$return_array["metatile"] = trim(@$obj->titletag);
						$return_array["metakey"] = trim(@$obj->metakey);
						$return_array["metadesc"] = trim(@$obj->metadesc);
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
	
}
?>