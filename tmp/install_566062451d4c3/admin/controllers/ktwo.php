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

class iJoomla_SeoControllerKtwo extends iJoomla_SeoController {
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'ktwo');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('copy_key_title', 'copyKeyToTitle');
		$this->registerTask('copy_title_key', 'copyTitleToKey');
		$this->registerTask('copy_article_key', 'copyArticleToKey');
		$this->registerTask('copy_article_title', 'copyArticleToTitle');
		$this->registerTask('gen_metadesc', 'genMetadesc');
	}
	
	function genMetadesc(){
		$model = $this->getModel('ktwo');
		$result = $model->genMetadesc();
		
		$and = "";		
		$filter = JRequest::getVar("filter", "");
		$filter_missing = JRequest::getVar("value", "");
		if(trim($filter) != ""){
			$and = "&filter=".$filter."&value=".$filter_missing;
		}
		$link = "index.php?option=com_ijoomla_seo&controller=ktwo".$and;
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_GEN_METADESC_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_GEN_METADESC_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function copyArticleToTitle(){
		$model = $this->getModel('ktwo');
		$result = $model->copyArticleToTitle();	
		$link = "index.php?option=com_ijoomla_seo&controller=ktwo";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_ARTICLE_TITLE_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_ARTICLE_TITLE_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}	    	
	
	function copyArticleToKey(){
		$model = $this->getModel('ktwo');
		$result = $model->copyArticleToKey();	
		$link = "index.php?option=com_ijoomla_seo&controller=ktwo";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_ARTICLE_KEY_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_ARTICLE_KEY_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function copyTitleToKey(){
		$model = $this->getModel('ktwo');
		$result = $model->copyTitleToKey();	
		$link = "index.php?option=com_ijoomla_seo&controller=ktwo";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_TITLE_KEY_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_TITLE_KEY_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function copyKeyToTitle(){
		$model = $this->getModel('ktwo');
		$result = $model->copyKeyToTitle();	
		$link = "index.php?option=com_ijoomla_seo&controller=ktwo";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_KEY_TITLE_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_KEY_TITLE_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function ktwo() {
		$model = $this->getModel('ktwo');
		
		if ($model->existsK2()) {
			JRequest::setVar( 'view', 'ktwo' );
			parent::display();
		} else {
			$link = "index.php?option=com_ijoomla_seo&controller=menus";
			$msg = JText::_("COM_IJOOMLA_SEO_K2_ABSENT");
			$this->setRedirect($link, $msg, 'notice');		
		}
	}		
	
	function save() {	
		$model = $this->getModel('ktwo');
		$result = $model->save();
		$task = JRequest::getVar("task");
		if($task == "apply"){
			$link = "index.php?option=com_ijoomla_seo&controller=ktwo&ktwo=".JRequest::getVar('ktwo', '');
		}
		elseif($task == "save"){
			$link = "index.php?option=com_ijoomla_seo";
		}
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_META_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_META_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
}

?>