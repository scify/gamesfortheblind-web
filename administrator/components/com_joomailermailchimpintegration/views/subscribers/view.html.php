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

class joomailermailchimpintegrationViewSubscribers extends jmView {

    public function display($tpl = null) {
        JToolBarHelper::title(  JText::_('JM_NEWSLETTER_SUBSCRIBERS'), $this->getPageTitleClass());

        $option = JRequest::getCmd('option');
        $limit = $this->app->getUserStateFromRequest('global.list.limit', 'limit', $this->app->getCfg('list_limit'), 'int');
        $limitstart = $this->app->getUserStateFromRequest($option . '.limitstart', 'limitstart', 0, 'int');

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        $JoomlamailerMC = new JoomlamailerMC();

        if ($MCapi && $JoomlamailerMC->pingMC()) {
            JToolBarHelper::custom('goToLists', 'lists', 'lists', 'JM_LISTS', false, false);
            JToolBarHelper::spacer();
            if (JRequest::getVar('type') == 's') {
                JToolBarHelper::custom('unsubscribe', 'unsubscribe', 'unsubscribe', 'JM_UNSUBSCRIBE', true, false);
                JToolBarHelper::spacer();
                JToolBarHelper::custom('delete', 'unsubscribe', 'unsubscribe', 'JM_DELETE', true, false);
                JToolBarHelper::spacer();
            } else if (JRequest::getVar('type') == 'u') {
                //		JToolBarHelper::custom('resubscribe', 'resubscribe', 'resubscribe', 'Resubscribe', false, false);
            }
        }

        // Get data from the model
        $active	= $this->get('Active');
        $this->getModel()->addJoomlaUserData($active);
        $this->assignRef('active', $active);

        $lists = $this->get('Lists');
        $this->assignRef('lists', $lists);

        $total = $this->get('Total');
        $this->assignRef('total', $total);

        //		if ($total<$limit) $limit = $total;
        $this->assignRef('limitstart', $limitstart);
        $this->assignRef('limit', $limit);

        jimport('joomla.html.pagination');
        $pagination = new JPagination($total, $limitstart, $limit);
        $this->assignRef('pagination', $pagination);

        parent::display($tpl);
        require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
    }
}
