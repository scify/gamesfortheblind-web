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

class iJoomla_SeoControllerIlinkscategory extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'ilinkscategory');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
		$this->registerTask('remove', 'remove');
		$this->registerTask('new', 'newCat');
		$this->registerTask('edit', 'editCat');
		$this->registerTask('remove', 'remove');
	}	    	
		
	function editCat(){
		$cids = JRequest::getVar('cid', array(0));
		$this->setRedirect('index.php?option=com_ijoomla_seo&controller=newilinkscategory&task=edit&id='.$cids[0]);
	}
	
	function newCat(){
		$this->setRedirect('index.php?option=com_ijoomla_seo&controller=newilinkscategory&task=edit');
	}
	
	function remove(){
		$model = $this->getModel('ilinkscategory');
		$result = $model->remove();
		$link = "index.php?option=com_ijoomla_seo&controller=ilinkscategory";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_REMOVE_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_REMOVE_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
		
	function publish(){
		$model = $this->getModel('ilinkscategory');
		$result = $model->publish();
		$link = "index.php?option=com_ijoomla_seo&controller=ilinkscategory";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_PUBLISHED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_PUBLISHED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}
	
	function unpublish(){
		$model = $this->getModel('ilinkscategory');
		$result = $model->unpublish();
		$link = "index.php?option=com_ijoomla_seo&controller=ilinkscategory";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_UNPUBLISHED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_UNPUBLISHED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}	
		
	function ilinkscategory(){
		JRequest::setVar( 'view', 'Ilinkscategory' );	
		parent::display();
	}		
	
	function cancel(){
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$this->setRedirect('index.php?option=com_ijoomla_seo', $msg);
	}
}

?>