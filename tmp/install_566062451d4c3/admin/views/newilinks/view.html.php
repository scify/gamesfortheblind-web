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
include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."meta.php");

class iJoomla_SeoViewNewilinks extends JViewLegacy {
	
	function display($tpl = null){		
		JToolBarHelper::title(JText::_('COM_IJOOMLA_SEO_COMPONENT_TITLE'));	
		JToolBarHelper::save();
		JToolBarHelper::apply();
		JToolBarHelper::cancel('cancel', 'Cancel');		
				
		parent::display($tpl);		
	}
	
	function getValues(){
		$value = $this->get("Value");
		return $value;
	}
	
	function selectAllCategories($catid){
		$result = $this->get("AllCategories");
		$category = array();
		$category[] = JHTML::_('select.option', '0',  "-- ".JText::_("COM_IJOOMLA_SEO_SELECT_CATEGORY")." --");
		if($result != NULL){
			foreach($result as $key=>$value){
				$category[] = JHTML::_('select.option', $value->id,  $value->name);
			}
		}		
		return  JHTML::_('select.genericlist', $category,  'catid',  'size = "1" ',  'value',  'text',  $catid);
	}
	
	function getType($default){
		$type = array(); 	                                  
		$type[] = JHTML::_('select.option', '1', JText::_("COM_IJOOMLA_SEO_ARTICLE"), 'id', 'name');
		$type[] = JHTML::_('select.option', '2', JText::_("COM_IJOOMLA_SEO_MENU_ITEM"), 'id', 'name');
		$type[] = JHTML::_('select.option', '3', JText::_("COM_IJOOMLA_SEO_EXTERNAL_URL"), 'id', 'name');
        $type[] = JHTML::_('select.option', '4', JText::_("COM_IJOOMLA_SEO_ANCHOR"), 'id', 'name');
		return JHTML::_('select.genericlist', $type, 'type', 'onchange="changeMenu(); return false;"', 'id', 'name', $default);
	}
	
	function getAllMenu($type){
		$menus = $this->get("AllMenus");
		$return = '<select name="menu_type" onchange="javascrip:getMenuItems(this.value);">';
		$return .= '<option value="">'.JText::_("COM_IJOOMLA_SEO_SELECT_MENU").'</option>';
		if(isset($menus) && count($menus) > 0){
			foreach($menus as $key=>$value){
				$selected = "";
				if($value->menutype == $type){
					$selected = 'selected="selected"';
				}
				$return .= '<option value="'.$value->menutype.'" '.$selected.'>'.$value->title.'</option>';
			}
		}
		$return .= '</select>';
		return $return;
	}
	
	function getAllMenuItems($menu_type, $loc_id){
		$model = $this->getModel("newilinks");
		$menus = $model->getAllMenuItems($menu_type);
		return JHTML::_('select.genericlist', $menus, 'loc_id', 'style="float:left;"', 'id', 'title', $loc_id);
	}
	
	function displayArticle($id, $type, $location){
		$value = "";
		$document = JFactory::getDocument();
		$html = "";
		if($id == 0){
			$ilinkType = 1;
		}	
		else{
			$ilinkType = $type;
		}		
		$article = JTable::getInstance('content');															
		if($id != "0"){
			$value = $id;
		}	
		else{
			$value = NULL;
		}
		
		if($value){
			$article->load($value);
		}							
		else{
			if($id != 0 && $ilinkType == 1){				
				$article->title = $location;
			}	
			else{ 
				$article->title = '';
			}	
		}
		
		$name = 'id';
		$link = 'index.php?option=com_content&amp;task=element&amp;tmpl=component&amp;object='.$name."&layout=modal";
		
		JHTML::_('behavior.modal', 'a.modal');
		
		$html .= "\n".'<div style="float: left;"><input style="background: #ffffff;" type="text" id="'.$name.'_name" value="'.htmlspecialchars($article->title, ENT_QUOTES, 'UTF-8').'" name="location" size="40" readonly="readonly"/></div>';
		$html .= '<div class="button2-left"><div class="blank"><a class="modal" title="'.JText::_("COM_IJOOMLA_SEO_SELECT_ARTICLE").'"  href="'.$link.'" rel="{handler: \'iframe\', size: {x: 850, y: 375}}">'.JText::_("COM_IJOOMLA_SEO_SELECT").'</a></div></div>'."\n";
		$html .= "\n".'<input type="hidden" id="'.$name.'_id" name="articleId" value="'.(int)$value.'" />';		
		echo $html;
	}
	
	function displayUrl($location2){
		echo '<input type="text" name="location2" size="50" value="'.$location2.'" />';
	}
	
	function openLink($location){
		$target = array(); 	                                  
		$target[] = JHTML::_('select.option', '1', JText::_("COM_IJOOMLA_SEO_TARGET_SAME"), 'id', 'name');
		$target[] = JHTML::_('select.option', '2', JText::_("COM_IJOOMLA_SEO_TARGET_BLANK"), 'id', 'name');
		return JHTML::_('select.genericlist', $target, 'target', '', 'id', 'name', $location);
	}
	
	function publishedOptions($published){
		$return = "";
		$yes = "";
		$no = "";
		if($published == "1"){
			$yes = 'checked="checked"';
		}
		elseif($published == "0"){
			$no = 'checked="checked"';
		}
		$return .= '<input type="radio" '.$yes.' value="1" id="published" name="published">'.JText::_("JYES")."&nbsp;&nbsp;&nbsp;";
		$return .= '<input type="radio" '.$no.' value="0" id="published" name="published">'.JText::_("JNO");		
		return $return;
	}
	
	function otherPhrases($other_phrases){
		$return = "";
		$yes = "";
		$no = "";
		if($other_phrases == "1"){
			$yes = 'checked="checked"';
		}
		elseif($other_phrases == "0"){
			$no = 'checked="checked"';
		}
		$return .= '<input type="radio" '.$yes.' value="1" id="other_phrases" name="other_phrases">'.JText::_("JYES")."&nbsp;&nbsp;&nbsp;";
		$return .= '<input type="radio" '.$no.' value="0" id="other_phrases" name="other_phrases">'.JText::_("JNO");		
		return $return;
	}
		
}

?>