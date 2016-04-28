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

class iJoomla_SeoControllerConfig extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'config');
		$this->registerTask('apply', 'apply');
		$this->registerTask('save', 'save');
	}	    	
	
	function config(){
		JRequest::setVar( 'view', 'Config' );	
		parent::display();
	}
	
	function apply(){
		$model = $this->getModel('config');
		$res = $model->save();
		$task2 = JRequest::getVar("task2", "general");
		$link = "index.php?option=com_ijoomla_seo&controller=config&task2=".$task2;
		
		if ($res === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_CONFIG_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($res === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_CONFIG_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function save(){		
		$model = $this->getModel('config');
		$res = $model->save();		
		$link = "index.php?option=com_ijoomla_seo";
		
		if ($res === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_CONFIG_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($res === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_CONFIG_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
}

?>