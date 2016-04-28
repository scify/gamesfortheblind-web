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

class iJoomla_SeoControllerLanguage extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'language');
		$this->registerTask('apply', 'apply');
		$this->registerTask('save', 'save');
	}		
		
	function language(){
		JRequest::setVar( 'view', 'Language' );	
		parent::display();
	}
	
	function save(){
	    $model = $this->getModel('language');
		if($model->save()){
			$msg = JText::_('COM_IJOOMLA_SEO_LANGUAGE_SAVED');
			$this->setRedirect("index.php?option=com_ijoomla_seo", $msg);
		} 
		else{
			$msg = JText::_('COM_IJOOMLA_SEO_LANGUAGE_NOT_SAVED');
			$this->setRedirect("index.php?option=com_ijoomla_seo", $msg, 'notice');
		}			
	}

	function apply(){
	    $model = $this->getModel('language');
		if($model->save()){
			$msg = JText::_('COM_IJOOMLA_SEO_LANGUAGE_SAVED');
			$this->setRedirect("index.php?option=com_ijoomla_seo&controller=language&id=english.ijoomla_seo&hidemainmenu=1", $msg);
		} 
		else{
			$msg = JText::_('COM_IJOOMLA_SEO_LANGUAGE_NOT_SAVED');
			$this->setRedirect("index.php?option=com_ijoomla_seo&controller=language&id=english.ijoomla_seo&hidemainmenu=1", $msg, 'notice');
		}			
	}	
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
}

?>