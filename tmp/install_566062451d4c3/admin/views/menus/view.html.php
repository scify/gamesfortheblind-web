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

class iJoomla_SeoViewMenus extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	
	function display($tpl = null){		
		
		$meta = new Meta();
		$meta->ToolBar();
		
		$items 	= $this->get('Items');
		$pagination = $this->get('Pagination');
		
		$this->items = $items;
		$this->pagination = $pagination; 		
				
		$this->state = $this->get('State');
		
		$params = $meta->getParams();
		$this->params = $params;
			
		parent::display($tpl);		
	}	
	
	function getParams(){
		$params = $this->get("Params");
		return $params;
	}
	
	function createCriterias(){
		$return = "";		
		
		$default = JRequest::getVar("atype", "0");
		$temp = JRequest::getVar("value", "", "get");
		if($temp != ""){
			$default = $temp;
		}
		$atypeop[] = JHTML::_('select.option', '0',  "-- ".JText::_("COM_IJOOMLA_SEO_SHOW_ALL_ITEMS")." --");
		$atypeop[] = JHTML::_('select.option', '1',  JText::_("COM_IJOOMLA_SEO_MISSING_TITLE"));
		$atypeop[] = JHTML::_('select.option', '2',  JText::_("COM_IJOOMLA_SEO_MISSING_KEYWORDS"));
		$atypeop[] = JHTML::_('select.option', '3',  JText::_("COM_IJOOMLA_SEO_MISSING_DESCRIPTIONS"));
		$atypeop[] = JHTML::_('select.option', '4',  JText::_("COM_IJOOMLA_SEO_MISSING_ANY_TAG"));

		$return .= JHTML::_('select.genericlist', $atypeop,  'atype',  'class = "inputbox" size = "1" onchange = "document.adminForm.submit();"',  'value',  'text',  $default );
		
		$return .= "&nbsp;&nbsp;";
		
		$default = JRequest::getVar("filter_state", "0");
		$state[] = JHTML::_('select.option', '0',  "-- ".JText::_("COM_IJOOMLA_SEO_SELECT_STATE")." --");
		$state[] = JHTML::_('select.option', '1',  JText::_("COM_IJOOMLA_SEO_PUBLISHED"));
		$state[] = JHTML::_('select.option', '2',  JText::_("COM_IJOOMLA_SEO_UNPUBLISHED"));
		$state[] = JHTML::_('select.option', '3',  JText::_("COM_IJOOMLA_SEO_TRASHED"));

		$return .= JHTML::_('select.genericlist', $state,  'filter_state',  'class = "inputbox" size = "1" onchange = "document.adminForm.submit();"',  'value',  'text',  $default );
		
		return $return;		
	}				
}

?>