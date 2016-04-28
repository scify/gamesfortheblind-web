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

class iJoomla_SeoViewIlinks extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	
	function display($tpl = null){		
		
		JToolBarHelper::title(JText::_('COM_IJOOMLA_SEO_COMPONENT_TITLE'));	
		JToolBarHelper::addNew('new');
		JToolBarHelper::editList('edit');
		JToolBarHelper::deleteList(JText::_("COM_IJOOMLA_SEO_SURE_DELETE_KEYWORD_LINKING"));
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::cancel ('cancel', 'Cancel');			
		
		$items 	= $this->get('Items');
		$pagination = $this->get('Pagination');
		
		$this->items = $items;
		$this->pagination = $pagination; 
				
		$this->state = $this->get('State');
			
		parent::display($tpl);		
	}
	
	function selectAllCategories(){
		$result = $this->get("AllCategories");
		$default = JRequest::getVar("cat_filter", "0");
		$cat_filter = array();
		$cat_filter[] = JHTML::_('select.option', '0',  "-- ".JText::_("COM_IJOOMLA_SEO_SELECT_CATEGORY")." --");
		foreach($result as $key=>$value){
			$cat_filter[] = JHTML::_('select.option', $value->id,  $value->name);
		}		
		return  JHTML::_('select.genericlist', $cat_filter,  'cat_filter',  'size = "1" onchange = "document.adminForm.submit();"',  'value',  'text',  $default);
	}
	
	function getLocation($id){
		$db = JFactory::getDBO();
		$sql = "select `link` from #__menu where id=".$id;
		$db->setQuery($sql);
		$db->query();
		$link_location = $db->loadColumn();
		$link_location = @$link_location["0"];
		if($link_location != NULL){
			return $link_location;
		}
		return "";
	}
}

?>