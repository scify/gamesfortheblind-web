<?php
/**
 * Copyright (C) 2015  freakedout (www.freakedout.de)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die('Restricted Access');

class joomailermailchimpintegrationViewGroups extends jmView {

	public function display($tpl = null) {
        $layout = JRequest::getVar('layout',  0, 'post', 'string');

		$fields = $this->get('Data');
        if ($layout != 'form' && !$fields) {
            $this->app->enqueueMessage(JText::_('JM_NO_CUSTOM_FIELDS'), 'notice');
        }
		$this->assignRef('fields', $fields);

		$listName = JRequest::getVar('name', JRequest::getVar('listName', ''));
		$this->assignRef('listName', $listName);
        $title = ($listName) ? " ({$listName})" : '';

		if ($layout == 'form') {
			JToolBarHelper::title( JText::_('JM_NEWSLETTER_NEW_CUSTOM_FIELD') . $title, $this->getPageTitleClass());
			JToolBarHelper::save();
			JToolBarHelper::spacer();
			JToolBarHelper::cancel();

            $listId = JRequest::getVar('listid', '');
            $this->assignRef('listId', $listId);

            $CBfields = $this->get('CBfields');
            $this->assignRef('CBfields', $CBfields);
            $JSfields = $this->get('JSfields');
            $this->assignRef('JSfields', $JSfields);
            $VMfields = $this->get('VMfields');
            $this->assignRef('VMfields', $VMfields);
		} else {
			JToolBarHelper::title( JText::_('JM_NEWSLETTER_CUSTOM_FIELDS') . $title, $this->getPageTitleClass());
			JToolBarHelper::custom('goToLists', 'lists', 'lists', 'JM_LISTS', false, false);
			JToolBarHelper::spacer();
			JToolBarHelper::addNew();
			JToolBarHelper::spacer();
			JToolBarHelper::deleteList();
		}

		parent::display($tpl);
		require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
	}
}
