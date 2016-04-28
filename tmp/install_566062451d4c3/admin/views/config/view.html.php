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

class iJoomla_SeoViewConfig extends JViewLegacy {
	
	function display($tpl = null){		
		
		JToolBarHelper::title(JText::_( 'COM_IJOOMLA_SEO_COMPONENT_TITLE'));
		JToolBarHelper::apply();
		JToolBarHelper::save();		
		JToolBarHelper::cancel ('cancel', 'Cancel');
		
		$params = $this->getParams();
		$this->params = $params;
		
		parent::display($tpl);
	}
	
	function getParams(){
		$params = $this->get("Params");
		return json_decode($params);
	}
}

?>
