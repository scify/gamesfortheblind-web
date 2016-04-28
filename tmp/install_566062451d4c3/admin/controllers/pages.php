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

class iJoomla_SeoControllerPages extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'pages');
		$this->registerTask('apply', 'apply');
		$this->registerTask('save', 'save');
		$this->registerTask('edit_page', 'edit_page');
		$this->registerTask('savepage', 'savepage');
		$this->registerTask('outLinks', 'outLinks');
	}	    	
	
	function savepage(){
		$model = $this->getModel('pages');
		$res = $model->savepage();		
		$link = JURI::base()."index.php?option=com_ijoomla_seo&controller=pages";
		
		if ($res === TRUE) {	
			$msg = JText::_("COM_IJOOMLA_SEO_EDT_PAGE_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($res === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_EDT_PAGE_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
		
	function pages(){
		JRequest::setVar( 'view', 'Pages' );	
		parent::display();
	}		
	
	function edit_page(){		
		include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."pages.php");
		$page = new Page();
		$page->createEditPage();
	}
	
	function outLinks(){
		include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."pages.php");
		$page = new Page();
		$page->createOutLinks();
	}
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
}

?>