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

class iJoomla_SeoControllerZoo extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'zoo');
		$this->registerTask('apply', 'save');
		$this->registerTask('save', 'save');
		$this->registerTask('copy_key_title', 'copyKeyToTitle');
		$this->registerTask('copy_title_key', 'copyTitleToKey');
		$this->registerTask('copy_article_key', 'copyArticleToKey');
		$this->registerTask('copy_article_title', 'copyArticleToTitle');
		$this->registerTask('gen_metadesc', 'genMetadesc');
	}
	
	function zoo(){
		$model = $this->getModel('zoo');
		if ($model->existsZoo()) {
			JRequest::setVar('view', 'zoo');
			JRequest::setVar('layout', 'default');
			parent::display();
		} else {
			$link = "index.php?option=com_ijoomla_seo&controller=menus";
			$msg = JText::_("COM_IJOOMLA_SEO_ZOO_ABSENT");
			$this->setRedirect($link, $msg, 'notice');			
		}		
	}	
	
	function save(){
		$zoo = JRequest::getVar("zoo", "0");
		$model = $this->getModel('zoo');
		$result = $model->save();
		$task = JRequest::getVar("task");
		$link = "";
		if($task == "apply"){
			$link = "index.php?option=com_ijoomla_seo&controller=zoo&zoo=".$zoo;
		}
		elseif($task == "save"){
			$link = "index.php?option=com_ijoomla_seo";
		}
		
		if ($result === TRUE){
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
	
	function genMetadesc(){
		$zoo = JRequest::getVar("zoo", "0");
		$model = $this->getModel('zoo');
		$result = $model->genMetadesc();
		$link = "index.php?option=com_ijoomla_seo&controller=zoo&zoo=".$zoo;
		
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
		$zoo = JRequest::getVar("zoo", "0");
		$model = $this->getModel('zoo');
		$result = $model->copyArticleToTitle();
		$link = "index.php?option=com_ijoomla_seo&controller=zoo&zoo=".$zoo;
		
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
		$zoo = JRequest::getVar("zoo", "0");
		$model = $this->getModel('zoo');
		$result = $model->copyArticleToKey();
		$link = "index.php?option=com_ijoomla_seo&controller=zoo&zoo=".$zoo;
		
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
		$zoo = JRequest::getVar("zoo", "0");
		$model = $this->getModel('zoo');
		$result = $model->copyTitleToKey();
		$link = "index.php?option=com_ijoomla_seo&controller=zoo&zoo=".$zoo;
		
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
		$zoo = JRequest::getVar("zoo", "0");
		$model = $this->getModel('zoo');
		$result = $model->copyKeyToTitle();
		$link = "index.php?option=com_ijoomla_seo&controller=zoo&zoo=".$zoo;
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_KEY_TITLE_SAVED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_KEY_TITLE_SAVED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}	
}

?>