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

class iJoomla_SeoControllerIlinks extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'ilinks');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('edit', 'editCat');
		$this->registerTask('new', 'newCat');
		$this->registerTask('remove', 'remove');
		$this->registerTask('publish', 'publish');
		$this->registerTask('unpublish', 'unpublish');
	}					
	
	function ilinks(){
		JRequest::setVar( 'view', 'Ilinks' );	
		parent::display();
	}		
	
	function editCat(){
		$app = JFactory::getApplication('administrator');
		$cids = JRequest::getVar('cid', array(0));
		$app->redirect('index.php?option=com_ijoomla_seo&controller=newilinks&task=edit&id='.$cids[0]);
	}
	
	function newCat(){
		$app = JFactory::getApplication('administrator');
		$app->redirect('index.php?option=com_ijoomla_seo&controller=newilinks&task=edit');
	}
	
	function remove(){
		$app = JFactory::getApplication('administrator');
		$model = $this->getModel('ilinks');
		$result = $model->remove();
		$link = "index.php?option=com_ijoomla_seo&controller=ilinks";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_REMOVE_SUCCESSFULLY");
			$app->redirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_REMOVE_UNSUCCESSFULLY");		
			$app->redirect($link, $msg, 'notice');
		}
	}		
	
	function cancel(){
		$app = JFactory::getApplication('administrator');
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$app->redirect('index.php?option=com_ijoomla_seo', $msg);
	}    	
	
		
	function publish(){
		$model = $this->getModel('ilinks');
		$result = $model->publish();
		$link = "index.php?option=com_ijoomla_seo&controller=ilinks";
		
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
		$model = $this->getModel('ilinks');
		$result = $model->unpublish();
		$link = "index.php?option=com_ijoomla_seo&controller=ilinks";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_UNPUBLISHED_SUCCESSFULLY");
			$this->setRedirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_UNPUBLISHED_UNSUCCESSFULLY");		
			$this->setRedirect($link, $msg, 'notice');
		}
	}		
}

?>