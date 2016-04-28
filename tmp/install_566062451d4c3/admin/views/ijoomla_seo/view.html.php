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

jimport('joomla.application.component.view');

class iJoomla_SeoViewiJoomla_Seo extends JViewLegacy {
	
	function display($tpl = null){
		$menu_type=$this->getMenuType("article");
		$this->menu_type = $menu_type;
		
		parent::display($tpl);
	}
	
	//here add new component in drop down
	function getMenuType($default){
		 $types = array();		 
		 $types[] = JHTML::_('select.option','article', JText::_("COM_IJOOMLA_SEO_ARTICLES") , 'id', 'name');
		 $types[] = JHTML::_('select.option','menuitems', JText::_("COM_IJOOMLA_SEO_MENU_ITEMS") , 'id', 'name');
		 //$types[] = JHTML::_('select.option','mtree', JText::_("COM_IJOOMLA_SEO_MENU_MTREE") , 'id', 'name');
		 $types[] = JHTML::_('select.option','zoo', JText::_("COM_IJOOMLA_SEO_MENU_ZOO") , 'id', 'name');
		 $types[] = JHTML::_('select.option','ktwo', JText::_("COM_IJOOMLA_SEO_MENU_KTWO") , 'id', 'name');
		 $types[] = JHTML::_('select.option','kunena', JText::_("COM_IJOOMLA_SEO_MENU_KUNENA") , 'id', 'name');
		 $types[] = JHTML::_('select.option','easyblog', JText::_("COM_IJOOMLA_SEO_MENU_EASYBLOG") , 'id', 'name');	
		 
		 $onchange = ' onchange= " javascript: getStats (this.options[this.options.selectedIndex].value, \'\');" ';
		 return JHTML::_('select.genericlist', $types, 'types',  $onchange , 'id', 'name', $default);
	}
	
	//complet new drop down for new component	
	function createSelect($option){
		$ijseo_keysource = JRequest::getVar("keysource", 0,'get','int');
		$menus = $this->get("Menus");				
		$array_options = array();
		
		switch($option){
			case "menuitems" : {
				foreach($menus as $key=>$value){
					$array_options[$menus[$key]->menutype]=$menus[$key]->title;
				}
				break;
			}
			case "mtree" : {
				$array_options["mt_list"] = JText::_("COM_IJOOMLA_SEO_MTREE_LISTING");
				$array_options["mt_cat"] = JText::_("COM_IJOOMLA_SEO_MTREE_CAETGORY");
				break;
			}
			case "sobi" : {
				$array_options["sobi-item"] = "LISTINGS";
				$array_options["sobi-cat"] = "CATEGORIES";
				break;
			}
			case "magazine" : {
				$array_options["mag-cat"] = "ISSUES";
				break;
			}
			case "digistore" : {
				$array_options["digi-prod"] = "PRODUCTS";
				$array_options["digi-cat"] = "CATEGORIES";
				break;
			}
			case "newsportal" : {
				$array_options["np-sec"] = "SECTIONS";
				$array_options["np-cat"] = "CATEGORIES";
				break;
			}
			case "ktwo" : {
				$array_options["k2-item"] = JText::_("COM_IJOOMLA_SEO_KTWO_ITEMS");
				$array_options["k2-cat"] = JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY");
				break;
			}
			case "easyblog" : {
				$array_options["easyblog-item"] = JText::_("COM_IJOOMLA_SEO_KTWO_ITEMS");
				$array_options["easyblog-cat"] = JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY");
				break;
			}
			case "kunena" : {
				$array_options["kunena-cat"] = JText::_("COM_IJOOMLA_SEO_KTWO_CAETGORY");
				break;
			}
			case "virtuemart" : {
				$array_options["virtuemart-prod"] = "PRODUCTS";
				$array_options["virtuemart-cat"] = "CATEGORIES";
				break;
			}
			case "zoo" : {
				$array_options["zoo_items"] = JText::_("COM_IJOOMLA_SEO_ZOO_ITEMS");
				$array_options["zoo_cats"] = JText::_("COM_IJOOMLA_SEO_ZOO_CAETGORY");
				break;
			}
			case "wordpress" : {
				$array_options["wordpress-item"] = "POSTS";
				$array_options["wordpress-cat"] = "CATEGORIES";
				break;
			}
			case "mighty" : {
				$array_options["mighty-item"] = "ITEMS";
				$array_options["mighty-cat"] = "CATEGORIES";
				break;
			}
		}
				
		$output = "";
		$output .= "<select name=\"".$option."\" id=\"".$option."\" onchange=\" getStats('".$option."', this.options[this.options.selectedIndex].value, ".$ijseo_keysource.")\" class=\"inputbox\">";
		$output .= 		"<option value=\" \">".JText::_("COM_IJOOMLA_SEO_SELECT")."</option>";
		foreach($array_options as $key=>$value){
			$output .= 	"<option value=\"".$key."\">".$value."</option>";
		}		
		$output .= "</select>";
		
		return $output;
	}
	
	function getVideoDetails($id){
		$return = array();
		$api_key = "AIzaSyAubrWOQhLLiujM6MMx630VKqDVSHxrMN8";
		
		$return["img"] = "http://img.youtube.com/vi/".$id."/default.jpg";
		$ytdataurl = "https://www.googleapis.com/youtube/v3/videos?part=snippet&id=".$id."&key=".$api_key;
		$result = file_get_contents($ytdataurl);
		$result = json_decode($result, true);
		
		$title = json_decode(json_encode($result["items"]["0"]["snippet"]["title"]), TRUE);
		$content = json_decode(json_encode($result["items"]["0"]["snippet"]["description"]), TRUE);
		
		$content = str_replace("http://seo.ijoomla.com/", "", $content);
		$content = str_replace("http://seo.ijoomla.com", "", $content);
		
		if(strlen($content) > 200){
			$content = substr($content, 0, 200)."...";
		}
		
		$return["title"] = trim($title);
		$return["content"] = trim($content);
		
		return $return;
	}
	
	function getKeysUp(){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__ijseo_keys where `mode` = 1";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		return intval(@$count["0"]);
	}
	
	function getKeysDown(){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__ijseo_keys where `mode` = 0";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		return intval(@$count["0"]);
	}
	
	function getKeysSame(){
		$db = JFactory::getDBO();
		$sql = "select count(*) from #__ijseo_keys where `mode` = -1";
		$db->setQuery($sql);
		$db->query();
		$count = $db->loadColumn();
		return intval(@$count["0"]);
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
	
}

?>
