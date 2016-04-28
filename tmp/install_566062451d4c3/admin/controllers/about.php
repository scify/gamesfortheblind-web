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

defined( '_JEXEC' ) or die( 'Restricted access' );

class iJoomla_SeoControllerAbout extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'about');
		$this->registerTask('stats', 'calculateStats');	
	}
	
	function about(){
		JRequest::setVar( 'view', 'About' );	
		parent::display();
	}
    
    function vimeo() {
   		JRequest::setVar( 'view', 'about' );
		JRequest::setVar( 'layout', 'vimeo'  );
        $view = $this->getView("about", "html");
		$view->setLayout("vimeo");
        $view->vimeo();
        die();
    }
	
	function youtube() {
   		JRequest::setVar( 'view', 'about' );
		JRequest::setVar( 'layout', 'youtube'  );
        $view = $this->getView("about", "html");
		$view->setLayout("youtube");
        $view->youtube();
        die();
    }
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
	
	function calculateStats(){
		$source = JRequest::getVar("source", "");
		
		switch($source){
			case "articles" : {
				$this->calculateArticleStats();
				break;
			}
			case "menuitems" : {
				$this->calculateMenuItemsStats();
				break;
			}
			case "jomsocial" : {
				$this->calculateJomsocialStats();
				break;
			}
			case "mtree" : {
				$this->calculateMtreeStats();
				break;
			}
			case "zoo" : {
				$this->calculateZooStats();
				break;
			}
			case "k2" : {
				$this->calculateKtwoStats();
				break;
			}			
			case "kunena" : {
				$this->calculateKunenaStats();
				break;
			}
			case "easyblog" : {
				$this->calculateEasyblogStats();
				break;
			}
		}
		die();
	}
	
	function calculateArticleStats(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and mt.`titletag`='' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=articles&atype=1';
				break;
			case "keys":
				$where.= " and c.metakey='' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=articles&atype=2';
				break;
			case "desc":
				$where.= " and c.metadesc='' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=articles&atype=3';
				break;
			default:
				break;
		}
		
		$query->select('c.id, c.title, c.metakey, c.metadesc, c.attribs, mt.titletag');
		$query->from('#__content c');
		$query->join('LEFT', '`#__ijseo_metags` AS mt ON c.id = mt.id and mt.mtype=\'article\'');
		$query->where($where);
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function calculateMenuItemsStats(){	
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$menu = JRequest::getVar("menu", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and (params like '%\"page_title\":\"\"%' or params not like '%page_title%')";
				$href = 'index.php?option=com_ijoomla_seo&controller=menus&menu_types='.$menu.'&atype=1';
				break;
			case "keys":
				$where.= " and (params like '%\"menu-meta_keywords\":\"\"%' or params not like '%menu-meta_keywords%')";
				$href = 'index.php?option=com_ijoomla_seo&controller=menus&menu_types='.$menu.'&atype=2';
				break;
			case "desc":
				$where.= " and (params like '%\"menu-meta_description\":\"\"%' or params not like '%menu-meta_description%')";
				$href = 'index.php?option=com_ijoomla_seo&controller=menus&menu_types='.$menu.'&atype=3';
				break;
			default:
				break;
		}
		
		$where.= " and menutype='".$menu."'";
		
		$query->clear();
		$query->select('id, title, params, link');
		$query->from('#__menu');
		$query->where($where);
		$query->order('lft asc');
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function calculateJomsocialStats(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$jomsocial_option = JRequest::getVar("jomsocial", "");
		
		$sql = "SHOW TABLES";
		$database->setQuery($sql);
		$tables = $database->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "community_groups", $tables)) {
			echo "";
			die();
		}
		
		if($jomsocial_option == "1"){
			$this->getGroupsListing();
		}
		elseif($jomsocial_option == "2"){
			$this->getEventsListing();
		}
		elseif($jomsocial_option == "3"){
			$this->getPhotoAlbumsListing();
		}
		elseif($jomsocial_option == "4"){
			$this->getVideosListing();
		}
		elseif($jomsocial_option == "5"){
			$this->getPhotosListing();
		}
	}
	
	function calculateMtreeStats($selsubmenu){	
		global $database;
		$sql = "select params from #__ijseo_config";
		$database->setQuery($sql);
		$database->query();
		$result = $database->loadColumn();
		$result = @$result["0"];
		$params = json_decode($result);
		
		$stats = JRequest::getVar("stats", "0");		
		$sql = "";
		$result = "";
		
		$submenu = "0";
		if($selsubmenu == "mt_list"){
			$submenu = "1";
		}
		elseif($selsubmenu == "mt_cat"){
			$submenu = "2";
		}	
		
		if($selsubmenu != "" && $selsubmenu == "mt_list"){
			if($stats == "1"){
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
				}						
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';			
			}
			elseif($stats == "2"){
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
				}
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';				
			}
			elseif($stats == "3"){		
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
				}			
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "4"){		
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
				}	
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&filter=sticky&value=1&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "5"){
				$sql = "SELECT count(*) FROM #__mt_links l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.link_id WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=1&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "6"){		
				$sql = "SELECT count(*) FROM #__mt_links l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.link_id WHERE 1=1 and l.metakey = '' and mt.mtype='".$selsubmenu."' ";
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=2&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "7"){		
				$sql = "SELECT count(*) FROM #__mt_links l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.link_id WHERE 1=1 and l.metadesc = '' and mt.mtype='".$selsubmenu."' ";
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=3&types=mtree&mtree='.$submenu.'">';
			}
			if($sql != ""){
				$database->setQuery($sql);		
				if(!$database->query()){
					 echo "-";
				}
				$temp = $database->loadColumn();
				$temp = @$temp["0"];
				echo $result.$temp.'</a>';
			}
		}
		elseif($selsubmenu != "" && $selsubmenu == "mt_cat"){
			if($stats == "1"){
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 1 and k.rchange <> 0";
				}						
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';			
			}
			elseif($stats == "2"){
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys k join #__ijseo_keys_id ki on ki.keyword = k.title where ki.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys k where k.type = '".$selsubmenu."' and k.mode = 0 and k.rchange <> 0";
				}
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&keyup_doun=true">';				
			}
			elseif($stats == "3"){		
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys_id where `type`='".$selsubmenu."'";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys where `type`='".$selsubmenu."'";
				}			
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "4"){		
				if($params->ijseo_keysource == "0"){
					$sql = "select count(*) from #__ijseo_keys k, #__ijseo_keys_id ki where ki.`type`='".$selsubmenu."' and k.sticky=1 and k.title=ki.keyword";
				}
				else{
					$sql = "select count(*) from #__ijseo_titlekeys k where k.`type`='".$selsubmenu."' and k.sticky=1";
				}
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=keysmtree&filter=sticky&value=1&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "5"){
				$sql = "SELECT count(*) FROM #__mt_cats l LEFT JOIN `#__ijseo_metags` AS mt ON mt.id = l.cat_id WHERE 1=1 and mt.titletag = '' and mt.mtype='".$selsubmenu."' ";
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=1&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "6"){		
				$sql = "SELECT count(*) FROM #__mt_cats l WHERE 1=1 and l.metakey = '' ";
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=2&types=mtree&mtree='.$submenu.'">';
			}
			elseif($stats == "7"){		
				$sql = "SELECT count(*) FROM #__mt_cats l WHERE l.metadesc = '' ";
				$result .= '<a href="index.php?option=com_ijoomla_seo&controller=mtree&filter=atype&value=3&types=mtree&mtree='.$submenu.'">';
			}
			
			if($sql != ""){
				$database->setQuery($sql);		
				if(!$database->query()){
					 echo "-";
				}
				$temp = $database->loadColumn();
				$temp = @$temp["0"];
				echo $result.$temp.'</a>';
			}
		}
		else{
			echo "-";	
		}
	}
	
	function calculateZooStats(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$zoo = JRequest::getVar("zoo", "0");
		$where = " 1=1 ";
		$href = "";
		
		$sql = "SHOW TABLES";
		$database->setQuery($sql);
		$tables = $database->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "zoo_category", $tables)) {
			echo "";
			die();
		}
		
		if($zoo == 1){ // items
			switch ($value){
				case "title":
					$where.= ' and l.params like (\'%"metadata.title":""%\') ';
					$href = 'index.php?option=com_ijoomla_seo&controller=zoo&zoo=1&atype=1';
					break;
				case "keys":
					$where.= ' and l.params like (\'%"metadata.keywords":""%\') ';
					$href = 'index.php?option=com_ijoomla_seo&controller=zoo&zoo=1&atype=2';
					break;
				case "desc":
					$where.= ' and l.params like (\'%"metadata.description":""%\') ';
					$href = 'index.php?option=com_ijoomla_seo&controller=zoo&zoo=1&atype=3';
					break;
				default:
					break;
			}
			
			$query = "
				SELECT l.`id`, l.`name`, l.`params`
				FROM #__zoo_item AS l
				LEFT JOIN `#__zoo_category_item` AS k ON l.id = k.item_id
				WHERE {$where}
				group by l.id";
			
			$database->setQuery($query);
			$database->query();
			$result = $database->loadAssocList();
			$count = intval(count($result));
			
			echo '<a href="'.$href.'">'.$count.'</a>';
		}
		elseif($zoo == 2){ // categories
			switch ($value){
				case "title":
					$where.= ' and c.params like (\'%"metadata.title":""%\') ';
					$href = 'index.php?option=com_ijoomla_seo&controller=zoo&zoo=2&atype=1';
					break;
				case "keys":
					$where.= ' and c.params like (\'%"metadata.keywords":""%\') ';
					$href = 'index.php?option=com_ijoomla_seo&controller=zoo&zoo=2&atype=2';
					break;
				case "desc":
					$where.= ' and c.params like (\'%"metadata.description":""%\') ';
					$href = 'index.php?option=com_ijoomla_seo&controller=zoo&zoo=2&atype=3';
					break;
				default:
					break;
			}
			
			$query->clear();
			$query->select('c.`id`, c.`name`, c.`params`');
			$query->from('#__zoo_category c');
			$query->where($where);
			
			$database->setQuery($query);
			$database->query();
			$result = $database->loadAssocList();
			$count = intval(count($result));
			
			echo '<a href="'.$href.'">'.$count.'</a>';
		}
	}
	
	function calculateKtwoStats(){	
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$k2 = JRequest::getVar("k2", "0");
		$where = "";
		$href = "";
		
		$sql = "SHOW TABLES";
		$database->setQuery($sql);
		$tables = $database->loadColumn();
		$config = JFactory::getConfig();
		if(!in_array($config->get('dbprefix')."k2_items", $tables)){
			echo "";
			die();
		}
		
		if($k2 == 1){ // items
			switch ($value){
				case "title":
					$where .= " AND m.titletag = '' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=1&atype=1';
					break;
				case "keys":
					$where .= " AND k.metakey ='' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=1&atype=2';
					break;
				case "desc":
					$where .= " AND k.metadesc ='' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=1&atype=3';
					break;
				default:
					break;
			}
			
			$query = "SELECT k.id, k.title, m.titletag, k.metakey, k.metadesc FROM #__k2_items AS k LEFT JOIN #__ijseo_metags AS m ON k.`id` = m.`id` AND m.`mtype` = 'k2-item' where 1=1 ".$where." group by k.id ORDER BY k.id DESC";
						
			$database->setQuery($query);
			$database->query();
			$result = $database->loadAssocList();
			$count = intval(count($result));
			
			echo '<a href="'.$href.'">'.$count.'</a>';
		}
		elseif($k2 == 2){ // categories
			switch ($value){
				case "title":
					$where.= " AND m.titletag = '' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=2&atype=1';
					break;
				case "keys":
					$where.= ' AND k.params like \'%"catMetaKey":""%\'';
					$href = 'index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=2&atype=2';
					break;
				case "desc":
					$where.= ' AND k.params like \'%"catMetaDesc":""%\' ';
					$href = 'index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=2&atype=3';
					break;
				default:
					break;
			}
			
			$query = "SELECT k.id, k.name AS title, m.titletag, k.params FROM #__k2_categories AS k LEFT JOIN #__ijseo_metags AS m ON k.`id` = m.`id` and m.`mtype` = 'k2-cat' where 1=1 ".$where." ORDER BY k.`id` DESC";
			
			$database->setQuery($query);
			$database->query();
			$result = $database->loadAssocList();
			$count = intval(count($result));
			
			echo '<a href="'.$href.'">'.$count.'</a>';
		}
	}
	
	function calculateKunenaStats(){	
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		$sql = "SHOW TABLES";
		$database->setQuery($sql);
		$tables = $database->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "kunena_categories", $tables)) {
			echo "";
			die();
		}
		
		switch ($value){
			case "title":
				$where.= " AND m.titletag = '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=kunena&atype=1';
				break;
			case "keys":
				$where.= " AND m.metakey ='' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=kunena&atype=2';
				break;
			case "desc":
				$where.= " AND m.metadesc ='' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=kunena&atype=3';
				break;
			default:
				break;
		}
		
		$query = "SELECT k.id, k.name AS title, m.titletag, m.metakey, m.metadesc 
				  FROM #__kunena_categories AS k 
				  LEFT JOIN #__ijseo_metags AS m 
				  ON k.id = m.id and m.mtype = 'kunena-cat'
				  where {$where} ORDER BY k.id DESC";
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function calculateEasyblogStats(){	
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$easy_blog_id = JRequest::getVar("easy_blog_id", "0");
		$where = " where 1=1 ";
		$href = "";
		
		$sql = "SHOW TABLES";
		$database->setQuery($sql);
		$tables = $database->loadColumn();
		$config = JFactory::getConfig();
		if (!in_array($config->get( 'dbprefix' ) . "easyblog_meta", $tables)){
			echo "";
			die();
		}
		
		if($easy_blog_id == 1){ // items
			switch ($value){
				case "title":
					$where.= " AND m.titletag = '' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=easyblog&easyblog=1&atype=1';
					break;
				case "keys":
					$where.= " AND e.keywords ='' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=easyblog&easyblog=1&atype=2';
					break;
				case "desc":
					$where.= " AND e.description ='' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=easyblog&easyblog=1&atype=3';
					break;
				default:
					break;
			}
			
			$query = "
						SELECT k.id, k.title, m.titletag, e.keywords as metakey, e.description as metadesc
						FROM #__easyblog_post AS k 
						LEFT JOIN #__ijseo_metags AS m ON k.id = m.id and m.`mtype`='easyblog-item'
						LEFT JOIN #__easyblog_meta AS e ON e.content_id = k.id and e.`type`='post'
						{$where} ORDER BY k.id DESC";
						
			$database->setQuery($query);
			$database->query();
			$result = $database->loadAssocList();
			$count = intval(count($result));
			
			echo '<a href="'.$href.'">'.$count.'</a>';
		}
		elseif($easy_blog_id == 2){ // categories
			switch ($value){
				case "title":
					$where.= " AND m.titletag = '' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=easyblog&easyblog=2&atype=1';
					break;
				case "keys":
					$where.= " AND e.keywords ='' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=easyblog&easyblog=2&atype=2';
					break;
				case "desc":
					$where.= " AND e.description ='' ";
					$href = 'index.php?option=com_ijoomla_seo&controller=easyblog&easyblog=2&atype=3';
					break;
				default:
					break;
			}
			
			$query = "
						SELECT k.id, k.title, m.titletag, e.keywords as metakey, e.description as metadesc
						FROM #__easyblog_category AS k 
						LEFT JOIN #__ijseo_metags AS m ON k.id = m.id and m.`mtype`='easyblog-cat'
						LEFT JOIN #__easyblog_meta AS e ON e.content_id = k.id and e.`type`='category'
						{$where} ORDER BY k.id DESC";
			
			$database->setQuery($query);
			$database->query();
			$result = $database->loadAssocList();
			$count = intval(count($result));
			
			echo '<a href="'.$href.'">'.$count.'</a>';
		}
	}
	
	function getGroupsListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and mt.titletag like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=0&atype=1';
				break;
			case "keys":
				$where.= " and mt.metakey like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=0&atype=2';
				break;
			case "desc":
				$where.= " and mt.metadesc like ''";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=0&atype=3';
				break;
			default:
				break;
		}
		
		$query->select('g.id, g.name, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_groups g');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = g.id and mt.mtype='js_group'");
		$query->where($where);
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function getEventsListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and mt.titletag like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=1&atype=1';
				break;
			case "keys":
				$where.= " and mt.metakey like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=1&atype=2';
				break;
			case "desc":
				$where.= " and mt.metadesc like ''";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=1&atype=3';
				break;
			default:
				break;
		}
		
		$query->select('p.id, p.title, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_events p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_events'");
		$query->where($where);
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function getPhotoAlbumsListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and mt.titletag like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=2&atype=1';
				break;
			case "keys":
				$where.= " and mt.metakey like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=2&atype=2';
				break;
			case "desc":
				$where.= " and mt.metadesc like ''";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=2&atype=3';
				break;
			default:
				break;
		}
		
		$query->select('p.id, p.name,p.creator, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_photos_albums p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_albums'");
		$query->where($where);
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function getVideosListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and mt.titletag like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=3&atype=1';
				break;
			case "keys":
				$where.= " and mt.metakey like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=3&atype=2';
				break;
			case "desc":
				$where.= " and mt.metadesc like ''";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=3&atype=3';
				break;
			default:
				break;
		}
		
		$query->select('p.id, p.title,p.creator, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_videos p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_videos'");
		$query->where($where);
		$query->order('p.created desc');
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
	
	function getPhotosListing(){
		$database	= JFactory::getDBO();
		$query		= $database->getQuery(true);
		$value = JRequest::getVar("value", "");
		$where = " 1=1 ";
		$href = "";
		
		switch ($value){
			case "title":
				$where.= " and mt.titletag like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=4&atype=1';
				break;
			case "keys":
				$where.= " and mt.metakey like '' ";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=4&atype=2';
				break;
			case "desc":
				$where.= " and mt.metadesc like ''";
				$href = 'index.php?option=com_ijoomla_seo&controller=jomsocial&jomsocial=4&atype=3';
				break;
			default:
				break;
		}
		
		$query->select('p.id, p.caption,p.creator,p.albumid, mt.titletag, mt.metakey, mt.metadesc');
		$query->from('#__community_photos p');
		$query->join('LEFT', "`#__ijseo_metags` AS mt ON mt.id = p.id and mt.mtype='js_photos'");
		$query->where($where);
		
		$database->setQuery($query);
		$database->query();
		$result = $database->loadAssocList();
		$count = intval(count($result));
		
		echo '<a href="'.$href.'">'.$count.'</a>';
	}
}

?>