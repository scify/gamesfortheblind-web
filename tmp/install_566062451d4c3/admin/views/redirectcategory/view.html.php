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

class iJoomla_SeoViewRedirectcategory extends JViewLegacy {
	
	protected $items;
	protected $pagination;
	
	function display($tpl = null){		
		
		JToolBarHelper::title(JText::_('COM_IJOOMLA_SEO_COMPONENT_TITLE'));	
		JToolBarHelper::addNew('new');
		JToolBarHelper::editList('edit');
		JToolBarHelper::deleteList('COM_IJOOMLA_SEO_SURE_DELETE_CATEGORY');
		JToolBarHelper::divider();
		JToolBarHelper::publishList();
		JToolBarHelper::unpublishList();
		JToolBarHelper::cancel ('cancel', 'Cancel');		
		
		$items 	= $this->get('Items');
		$pagination = $this->get('Pagination');
		
		$this->items = $items;
		$this->pagination = $pagination; 
				
		$this->state = $this->get('State');
			
		parent::display($tpl);		
	}
	
	function count($id){
		$db = JFactory::getDBO();
		$query = $db->getQuery(true);
		$query->select('count(*)');
		$query->from('#__ijseo');
		$query->where('catid='.$id);
		$db->setQuery($query);
		$db->query();
		$result = $db->loadColumn();
		$result = @$result["0"];
		return $result;
	}		
}

?>