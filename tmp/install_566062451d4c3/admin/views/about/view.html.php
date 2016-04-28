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

class iJoomla_SeoViewAbout extends JViewLegacy {
	
	function display($tpl = null){		
		JToolBarHelper::title(JText::_( 'COM_IJOOMLA_SEO_COMPONENT_TITLE'));	
		JToolBarHelper::cancel ('cancel', 'Cancel');
		
		parent::display($tpl);		
	}
    
    function vimeo($tpl = null) {
        $id = JRequest::getVar('id', '0');
        $this->assignRef('id', $id);
        parent::display($tpl);
    }
	
	function youtube($tpl = null) {
        $v = JRequest::getVar('v', '0');
        $this->assignRef('v', $v);
        parent::display($tpl);
    }
	
	function getVersion($path, $defaultName){ 
		$dates = array("name"=>$defaultName, "version"=>"version N/A", "installed"=>JText::_("COM_IJOOMLA_SEO_NOT_INSTALLED"));
			
		if (file_exists($path)){
			$dates["installed"] = JText::_("COM_IJOOMLA_SEO_INSTALLED");
		}
		if(file_exists($path)) {
			$data = implode ("", file($path));
			$pos1 = strpos ($data,"<version>");
	        $pos2 = strpos ($data,"</version>");
	        $dates["version"] = 'version '.substr ($data, $pos1+strlen("<version>"), $pos2-$pos1-strlen("<version>"));
		}
		else{
			print JText::_("COM_IJOOMLA_SEO_CANT_READ_VERSION");
		}
		return $dates;
	}
}

?>
