<?php
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

jimport('joomla.application.component.view');

/**
 * View to edit a page.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.5
 */
class OnepageViewPage extends JViewLegacy
{
	protected $form;
	protected $item;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{  
        $doc = JFactory::getDocument(); 
		// Initialiase variables.
		$this->form		= $this->get('Form');
		$this->item		= $this->get('Item');
		$this->state	= $this->get('State');
        $this->id=JRequest::getVar('id',0); 
        $model=$this->getModel(); 
        $this->url = JURI::root().'administrator/components/com_onepage/assets/';
        $doc->addStyleSheet($this->url.'css/jquery-ui.css');
        $doc->addStyleSheet($this->url.'css/style.css');
        if(JVERSION<3){       
            $this->document->addStyleSheet($this->url . "css/style.design.2.5.css");
            $this->document->addStyleSheet($this->url . "css/bootstrap.min.css");
            $this->document->addScript($this->url . "js/jquery.min.js");
            $this->document->addScript($this->url . "js/jquery-noconflict.js");
            $this->document->addScript($this->url . "js/bootstrap.min.js");
            
        }  
        $this->document->addStyleSheet($this->url . "css/xp-box.css");
        
        //Get Data
        $this->pageitem = $model->getItemcode(); 
        $this->listModule = $model->getListModule();
        $this->listPageitem = $model->getListPageitem($this->id);
        $this->listItem = $model->getListitem($this->id);
        // Get Design
        $this->design = $model->getDesign($this->id);
        
        $this->name="";
        $this->name= $model->getPageName($this->id);
                                
		// Check for errors.
		if (count($errors = $this->get('Errors'))) {
			JError::raiseError(500, implode("\n", $errors));
			return false;
		}
        

		$this->addToolbar();    
		parent::display($tpl);
	}

	/**
	 * Add the page title and toolbar.
	 *
	 * @since	1.6
	 */
	protected function addToolbar()
	{
		JRequest::setVar('hidemainmenu', true);

		$user		= JFactory::getUser();
		$userId		= $user->get('id');
		$isNew		= ($this->item->id == 0);
		$checkedOut	= !($this->item->checked_out == 0 || $this->item->checked_out == $userId);
		$canDo		= OnepageHelper::getActions($this->state->get('filter.category_id'));

		JToolBarHelper::title($isNew ? JText::_('COM_ONEPAGE_MANAGER_PAGE_NEW') : JText::_('COM_ONEPAGE_MANAGER_PAGE_EDIT'), '#xs#.png');

		// If not checked out, can save the item.
		if (!$checkedOut && ($canDo->get('core.edit') || count($user->getAuthorisedCategories('com_onepage', 'core.create')) > 0)) {
			JToolBarHelper::apply('page.apply');
			JToolBarHelper::save('page.save');

			if ($canDo->get('core.create')) {
				//JToolBarHelper::save2new('page.save2new');
			}
		}

		// If an existing item, can save to a copy.
		if (!$isNew && $canDo->get('core.create')) {
			//JToolBarHelper::save2copy('page.save2copy');
		}

		if (empty($this->item->id))  {
			JToolBarHelper::cancel('page.cancel');
		}
		else {
			JToolBarHelper::cancel('page.cancel', 'JTOOLBAR_CLOSE');
		}
	}
}
