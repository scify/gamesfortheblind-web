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

class iJoomla_SeoControllerRedirect extends iJoomla_SeoController{
	
	function __construct() {	  
		parent::__construct();
		// Register Extra tasks		
		$this->registerTask('', 'redirect_view');
		$this->registerTask('cancel', 'cancel');
		$this->registerTask('reset_hit', 'resetHit');
		$this->registerTask('edit', 'editCat');
		$this->registerTask('new', 'newCat');
		$this->registerTask('remove', 'remove');
		$this->registerTask('about', 'about');
	}					
	
	function redirect_view(){
		JRequest::setVar( 'view', 'Redirect' );	
		parent::display();
	}		
	
	function editCat(){
		$app = JFactory::getApplication('administrator');
		$cids = JRequest::getVar('cid', array(0));
		$app->redirect('index.php?option=com_ijoomla_seo&controller=newredirect&task=edit&id='.$cids[0]);
	}		
	
	function newCat(){
		$app = JFactory::getApplication('administrator');
		$app->redirect('index.php?option=com_ijoomla_seo&controller=newredirect&task=edit');
	}
	
	function about(){
		$app = JFactory::getApplication('administrator');
		$app->redirect('index.php?option=com_ijoomla_seo&controller=about');
	}
	
	function remove(){
		$app = JFactory::getApplication('administrator');
		$model = $this->getModel('redirect');
		$result = $model->remove();
		$link = "index.php?option=com_ijoomla_seo&controller=redirect";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_REMOVE_SUCCESSFULLY");
			$app->redirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_REMOVE_UNSUCCESSFULLY");		
			$app->redirect($link, $msg, 'notice');
		}
	}
	
	function testredirect() {
		$app = JFactory::getApplication('administrator');
		$id =  JRequest::getInt('id');
		$db = JFactory::getDBO();
		$sql = "SELECT * FROM #__ijseo WHERE id = '{$id}' LIMIT 1";
		$db->setQuery($sql);
		$redirect = $db->loadObject();
		echo "<script>document.location = '" . $redirect->links_to. "';</script>";
		die();
	}
	
	function resetHit(){
		$app = JFactory::getApplication('administrator');
		$model = $this->getModel('redirect');
		$result = $model->resetHit();
		$link = "index.php?option=com_ijoomla_seo&controller=redirect";
		
		if ($result === TRUE) {
			$msg = JText::_("COM_IJOOMLA_SEO_RESETHIT_SUCCESSFULLY");
			$app->redirect($link, $msg);
		} 
		elseif($result === FALSE) {
		 	$msg = JText::_("COM_IJOOMLA_SEO_RESETHIT_UNSUCCESSFULLY");		
			$app->redirect($link, $msg, 'notice');
		}
	}
	
	function cancel(){
		$app = JFactory::getApplication('administrator');
		$msg = JText::_('COM_IJOOMLA_SEO_OPERATION_CANCELED');
		$app->redirect('index.php?option=com_ijoomla_seo', $msg);
	}		
}

?>