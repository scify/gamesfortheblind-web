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

class iJoomla_SeoViewNewredirect extends JViewLegacy {
	
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
	
	function getAllCategories($default){		
		$result=$this->get("AllCategories");
		for($i=0;$i<count($result);$i++){
			$categories[] = JHTML::_('select.option', $result[$i]->id, $result[$i]->name, 'id', 'name');
		}	
		$lists['catid'] = JHTML::_('select.genericlist', $categories, "catid", "", 'id', 'name', $default);
		return $lists['catid'];
	}
		
}

?>

