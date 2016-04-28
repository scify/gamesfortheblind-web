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
	
	class Meta{		
		function getList() {
			$types = array();
			$controller = JRequest::getVar("controller", "articles");
			$default = "";
			switch($controller){
				case "articles" : {
					$default = "articles";
					break;
				}
				case "menus" : {
					$default = "menus";
					break;
				}
				case "menus" : {
					$default = "menus";
					break;
				}
				case "jomsocial" : {
					$default = "jomsocial";
					break;
				}
				case "zoo" : {
					$default = "zoo";
					break;
				}
				case "keysarticles" : {
					$default = "articles";
					break;
				}
				case "keysmenus" : {
					$default = "menus";
					break;
				}
				case "keysjomsocial" : {
					$default = "jomsocial";
					break;
				}
				case "keyszoo" : {
					$default = "zoo";
					break;
				}
				case "ktwo": {
					$default = "ktwo";
					break;
				}
				case "keysktwo": {
					$default = "ktwo";
					break;
				}
				case "kunena": {
					$default = "kunena";
					break;
				}
				case "keyskunena": {
					$default = "kunena";
					break;
				}
				case "easyblog": { 
					$default = "easyblog";
					break;
				}
				case "keyseasyblog": { 
					$default = "easyblog";
					break;
				}
			}
			$types[] = JHTML::_('select.option','articles', JText::_("COM_IJOOMLA_SEO_ARTICLES") , 'id', 'name');
			$types[] = JHTML::_('select.option','menus', JText::_("COM_IJOOMLA_SEO_MENU_ITEMS") , 'id', 'name');
			$types[] = JHTML::_('select.option','jomsocial', JText::_("COM_IJOOMLA_SEO_MENU_JOMSOCIAL") , 'id', 'name');
			//$types[] = JHTML::_('select.option','mtree', JText::_("COM_IJOOMLA_SEO_MENU_MTREE") , 'id', 'name');
			$types[] = JHTML::_('select.option','zoo', JText::_("COM_IJOOMLA_SEO_MENU_ZOO") , 'id', 'name');
			$types[] = JHTML::_('select.option','ktwo', JText::_("COM_IJOOMLA_SEO_MENU_KTWO") , 'id', 'name');
			$types[] = JHTML::_('select.option','kunena', JText::_("COM_IJOOMLA_SEO_MENU_KUNENA") , 'id', 'name');
			$types[] = JHTML::_('select.option','easyblog', JText::_("COM_IJOOMLA_SEO_MENU_EASYBLOG") , 'id', 'name');			
			
			$onchange = ' style="vertical-align: text-top;" onchange="showMenu(this.options[this.options.selectedIndex].value);" ';
			
			return JHTML::_('select.genericlist', $types, 'types',  $onchange , 'id', 'name', $default);
		}
		
		function createOptions(){
			$list_menus = $this->createListMenus();
			$list_jomsocial = $this->createListJomSocial();
			$list_mtree = $this->createListMtree();
			$list_zoo = $this->createListZoo();
			$list_ktwo = $this->createListKtwo();
			$list_kunena = $this->createListKunena();
			$list_easyblog = $this->createListEasyblog();
			
			$display_menus = "none";
			$display_mtree = "none";
			$display_jomsocial = "none";
			$display_zoo = "none";
			$display_ktwo = "none";
			$display_kunena = "none";
			$display_easyblog = "none";
			
			$type = JRequest::getVar("controller", "articles");
			
			if($type == "mtree") {
				$display_mtree = "block";
			} elseif($type == "jomsocial") {
				$display_jomsocial = "block";
			} elseif ($type == "zoo") {
				$display_zoo = "block";
			} elseif ($type == "ktwo") {
				$display_ktwo = "block";
			} elseif ($type == "menus") {
				$display_menus = "block";
			} elseif ($type == "keysmtree") {
				$display_mtree = "block";
			} elseif ($type == "keysmenus") {
				$display_menus = "block";
			} elseif ($type == "keyszoo") {
				$display_zoo = "block";
			} elseif ($type == "keysktwo") {
				$display_ktwo = "block";
			} elseif ($type == "keyseasyblog") {
				$display_easyblog = "block";
			} elseif ($type == "kunena") {
				$display_kunena = "block";
			} elseif ($type == "keyskunena") {
				$display_kunena = "block";
			} elseif ($type == "easyblog") {
				$display_easyblog = "block";

			}
			
			$return  = "";
			$return .= '<div id="list_menus" class="types" style="display:'.$display_menus.'">
							&nbsp;&nbsp;&nbsp; '.$list_menus.'
						</div>';
			$return .= '<div id="list_mtree" class="types" style="display:'.$display_mtree.'">
							&nbsp;&nbsp;&nbsp; '.$list_mtree.'
						</div>';
			$return .= '<div id="list_mtree" class="types" style="display:'.$display_jomsocial.'">
							&nbsp;&nbsp;&nbsp; '.$list_jomsocial.'
						</div>';
			$return .= '<div id="list_zoo" class="types" style="display:'.$display_zoo.'">
							&nbsp;&nbsp;&nbsp; '.$list_zoo.'
						</div>';
			$return .= '<div id="list_ktwo" class="types" style="display:'.$display_ktwo.'">
							&nbsp;&nbsp;&nbsp; '.$list_ktwo.'
						</div>';
			$return .= '<div id="list_kunena" class="types" style="display:'.$display_kunena.'">
							&nbsp;&nbsp;&nbsp; '.$list_kunena.'
						</div>';
			$return .= '<div id="list_easyblog" class="types" style="display:'.$display_easyblog.'">
							&nbsp;&nbsp;&nbsp; '.$list_easyblog.'
						</div>';
			return $return;			
		}
		
		function ToolBar(){
			$controller = JRequest::getVar("controller", "articles");
			JToolBarHelper::title(JText::_('COM_IJOOMLA_SEO_COMPONENT_TITLE'));
			
			JToolBarHelper::apply();
			JToolBarHelper::save();		
			JToolBarHelper::cancel ('cancel', 'Cancel'); 
			JToolBarHelper::divider();
			//JToolBarHelper::spacer('500');
			if ($controller == "keys"){
				JToolBarHelper::custom('sticky', 'flag.png', 'flag.png', 'Sticky', true );
				JToolBarHelper::custom('unsticky', 'minus.png', 'minus.png', 'Unsticky', true );
				JToolBarHelper::deleteList('COM_IJOOMLA_SEO_SURE_DELETE_KEYS');
				JToolBarHelper::cancel('cancel', 'Cancel');
			}
			else{											
				JToolBarHelper::custom('copy_key_title', 'share.png', 'share.png', 'keywords metatag to title metatag ', true );        
				JToolBarHelper::custom('copy_title_key', 'arrow-right.png', 'arrow-right.png', ' title metatag to keywords metatag ', true );        
				JToolBarHelper::custom('copy_article_key', 'forward.png', 'forward.png', ' article title to keywords metatag ', true );       
				JToolBarHelper::custom('copy_article_title', 'share-alt.png', 'share-alt.png', ' article title to title metatag ', true );               
				if($controller == "articles" || $controller == "ktwo" || $controller == "kunena" || $controller == "easyblog" || $controller == "jomsocial"){
					if($controller == "articles"){
						JToolBarHelper::custom('gen_metadesc', 'repeat.png', 'repeat.png', 'Generate Descriptions', true);
					}
					if($controller == "jomsocial"){
						$jomS = JRequest::getVar("jomsocial", "");
						if($jomS != "5") JToolBarHelper::custom('gen_metadesc', 'repeat.png', 'repeat.png', 'Generate Descriptions', true);
					}
					if($controller == "ktwo"){
						$ktwo = JRequest::getVar("ktwo",  "0");
						if($ktwo == 1){
							JToolBarHelper::custom('gen_metadesc', 'repeat.png', 'repeat.png', 'Generate Descriptions', true);
						}
					}
					
					if($controller == "kunena"){
						JToolBarHelper::custom('gen_metadesc', 'repeat.png', 'repeat.png', 'Generate Descriptions', true);
					}
					
					if($controller == "easyblog"){
						$easyblog = JRequest::getVar("easyblog",  "0");
						if($easyblog == 1){
							JToolBarHelper::custom('gen_metadesc', 'repeat.png', 'repeat.png', 'Generate Descriptions', true);
						}
					}
				}
				
			}	                
		}
		
		function getParams(){
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
		
		function getAllMenus(){
			$db = JFactory::getDBO();		
			$query = $db->getQuery(true);
			$query->clear();		
			$query->select('*');
			$query->from('#__menu_types');
			$db->setQuery($query);		
			$db->query();
			$result = $db->loadAssocList();
			return $result;
		}
		
		function createListMenus() {
			$db = JFactory::getDBO();		
			$query = $db->getQuery(true);
			$query->clear();		
			$query->select('*');
			$query->from('#__menu_types');
			$db->setQuery($query);		
			$db->query();
			$result = $db->loadAssocList();			
			$default = JRequest::getVar('menu_types', '');
			$menu[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT_MENU"), 'id', 'name');
			foreach($result as $key=>$value){
				$menu[] = JHTML::_('select.option', $value["menutype"], $value["title"], 'id', 'name');
			}			 					
			$onchange = 'onchange="if(this.selectedIndex != 0) document.adminForm.submit();"' ;
			return JHTML::_('select.genericlist', $menu, 'menu_types', $onchange, 'id', 'name', $default);				
		}
		
		function createListMtree(){
			$mtree = array();
			$mtree[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT"), 'id', 'name');
			$mtree[] = JHTML::_('select.option', "1", JText::_("COM_IJOOMLA_SEO_MTREE_LISTING"), 'id', 'name');
			$mtree[] = JHTML::_('select.option', "2", JText::_("COM_IJOOMLA_SEO_MTREE_CAETGORY"), 'id', 'name');
			$default = JRequest::getVar('mtree', '0');
			$onchange = 'onchange="if(this.selectedIndex != 0) document.adminForm.submit();"' ;
			return JHTML::_('select.genericlist', $mtree, 'mtree', $onchange, 'id', 'name', $default);			
		}
		
		
		function createListJomsocial(){
			$jomsocial = array();
			//$jomsocial[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT"), 'id', 'name');
			$jomsocial[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_JOMSOCIAL_GROUPS"), 'id', 'name');
			$jomsocial[] = JHTML::_('select.option', "1", JText::_("COM_IJOOMLA_SEO_JOMSOCIAL_EVENTS"), 'id', 'name');
			$jomsocial[] = JHTML::_('select.option', "2", JText::_("COM_IJOOMLA_SEO_JOMSOCIAL_PHOTO_ALBUMS"), 'id', 'name');
			$jomsocial[] = JHTML::_('select.option', "3", JText::_("COM_IJOOMLA_SEO_JOMSOCIAL_VIDEOS"), 'id', 'name');
			$jomsocial[] = JHTML::_('select.option', "4", JText::_("COM_IJOOMLA_SEO_JOMSOCIAL_PHOTOS"), 'id', 'name');
			$default = JRequest::getVar('jomsocial', '0');
			$onchange = 'onchange="if(this.selectedIndex != -1) document.adminForm.submit();"' ;
			return JHTML::_('select.genericlist', $jomsocial, 'jomsocial', $onchange, 'id', 'name', $default);			
		}
		function createListZoo(){
			$zoo = array();
			$zoo[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT"), 'id', 'name');
			$zoo[] = JHTML::_('select.option', "1", JText::_("COM_IJOOMLA_SEO_ZOO_ITEMS"), 'id', 'name');
			$zoo[] = JHTML::_('select.option', "2", JText::_("COM_IJOOMLA_SEO_ZOO_CAETGORY"), 'id', 'name');
			$default = JRequest::getVar('zoo', '0');
			$onchange = 'onchange="if(this.selectedIndex != 0) document.adminForm.submit();"';
			return JHTML::_('select.genericlist', $zoo, 'zoo', $onchange, 'id', 'name', $default);
		}

		function createListKtwo(){
			$k2 = array();
			$k2[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT"), 'id', 'name');
			$k2[] = JHTML::_('select.option', "1", JText::_("COM_IJOOMLA_SEO_KTWO_ITEMS"), 'id', 'name');
			$k2[] = JHTML::_('select.option', "2", JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY"), 'id', 'name');
			$default = JRequest::getVar('ktwo', '0');
			$onchange = 'onchange="if(this.selectedIndex != 0) document.adminForm.submit();"';
			return JHTML::_('select.genericlist', $k2, 'ktwo', $onchange, 'id', 'name', $default);
		}

		function createListEasyblog(){
			$easyblog = array();
			$easyblog[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT"), 'id', 'name');
			$easyblog[] = JHTML::_('select.option', "1", JText::_("COM_IJOOMLA_SEO_KTWO_ITEMS"), 'id', 'name');
			$easyblog[] = JHTML::_('select.option', "2", JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY"), 'id', 'name');
			$default = JRequest::getVar('easyblog', '0');
			$onchange = 'onchange="if(this.selectedIndex != 0) document.adminForm.submit();"';
			return JHTML::_('select.genericlist', $easyblog, 'easyblog', $onchange, 'id', 'name', $default);
		}

		function createListKunena(){
			$kunena = array();
			$kunena[] = JHTML::_('select.option', "0", JText::_("COM_IJOOMLA_SEO_SELECT"), 'id', 'name');
			$kunena[] = JHTML::_('select.option', "2", JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY"), 'id', 'name');
			$default = JRequest::getVar('kunena', '0');
			$onchange = 'onchange="if(this.selectedIndex != 0) document.adminForm.submit();"';
			return JHTML::_('select.genericlist', $kunena, 'kunena', $onchange, 'id', 'name', $default);
		}

	}
	
?>