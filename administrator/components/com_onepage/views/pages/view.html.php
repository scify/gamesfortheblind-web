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
 * View class for a list of pages.
 *
 * @package		Joomla.Administrator
 * @subpackage	com_onepage
 * @since		1.6
 */
class OnepageViewPages extends JViewLegacy
{
	protected $categories;
	protected $items;
	protected $pagination;
	protected $state;

	/**
	 * Display the view
	 */
	public function display($tpl = null)
	{
		// Initialise variables.
		$this->categories	= $this->get('CategoryOrders');
		$this->items		= $this->get('Items');
		$this->pagination	= $this->get('Pagination');
		$this->state		= $this->get('State');
        $model = $this->getModel();  
		for ($i = 0; $i < count($this->items); $i++) {
            $this->items[$i]->totalitem = $model->countItems($this->items[$i]->id); 
        }

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
		$canDo		= OnepageHelper::getActions($this->state->get('filter.category_id'));
		$user		= JFactory::getUser();
		JToolBarHelper::title(JText::_('COM_ONEPAGE_MANAGER_PAGES'), 'page.png');

		if ($canDo->get('core.create') || (count($user->getAuthorisedCategories('com_onepage', 'core.create'))) > 0 ) {
			JToolBarHelper::addNew('page.add');
		}

		if (($canDo->get('core.edit')) || ($canDo->get('core.edit.own'))) {
			JToolBarHelper::editList('page.edit');
		}

		if ($canDo->get('core.edit.state')) {
			JToolBarHelper::divider();
			JToolBarHelper::publish('pages.publish', 'JTOOLBAR_PUBLISH', true);
			JToolBarHelper::unpublish('pages.unpublish', 'JTOOLBAR_UNPUBLISH', true);
			JToolBarHelper::divider();
			JToolBarHelper::archiveList('pages.archive');
			JToolBarHelper::checkin('pages.checkin');
		}

		if ($this->state->get('filter.state') == -2 && $canDo->get('core.delete')) {
			JToolBarHelper::deleteList('', 'pages.delete','JTOOLBAR_EMPTY_TRASH');			
		}
		else if ($canDo->get('core.edit.state')) {
			JToolBarHelper::trash('pages.trash');
		}

		if ($canDo->get('core.admin')) {
			JToolBarHelper::divider();
			JToolBarHelper::preferences('com_onepage');			
		}
	}
}
