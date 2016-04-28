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

class iJoomla_SeoViewKeys extends JViewLegacy {
	
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
		$return = "&nbsp;&nbsp;";		

		$default = JRequest::getVar("sticky", "0");
		$temp = JRequest::getVar("value", "", "get");
		if($temp != ""){
			$default = $temp;
		}
		$sticky = array();
		$sticky[] = JHTML::_('select.option', '0',  "-- ".JText::_("COM_IJOOMLA_SEO_SHOW_ALL")." --");
		$sticky[] = JHTML::_('select.option', '1',  JText::_("COM_IJOOMLA_SEO_SHOW_STICKY"));

		$return .= JHTML::_('select.genericlist', $sticky,  'sticky',  'class = "inputbox" size = "1" onchange = "document.adminForm.submit();"',  'value',  'text',  $default );
		
		return $return;		
	}	
	
	function countArticles($title){
		$db = JFactory::getDBO();		
		$query = $db->getQuery(true);
		$query->clear();		
		$query->select('count(*)');
		$query->from('#__menu');
		$query->where("params like '%\"metakey\":\"".trim(addslashes($title)).",%' or params like '%\"metakey\":\"".trim(addslashes($title))."\"%' or params like '%,".trim(addslashes($title)).",%' or params like '%,".trim(addslashes($title))."\"%'");		
		$db->setQuery($query);		
		$db->query();
		$temp = $db->loadColumn();
		return @$temp["0"];
	}	
}

?>