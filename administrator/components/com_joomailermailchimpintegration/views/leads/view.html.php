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

class joomailermailchimpintegrationViewLeads extends jmView {

    public function display($tpl = null) {
        JToolBarHelper::title(JText::_('JM_Newsletter : Leads'), $this->getPageTitleClass());

        $params    = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $subdomain = $params->get('subdomain');
        $domain    = $params->get('domain');
        $username  = $params->get('username');
        $pw        = $params->get('pw');

        if ($subdomain && $domain && $username && $pw) {
            JToolBarHelper::back();
            JToolBarHelper::custom('sync_leads', 'sync_selected', 'sync_selected', 'JM_ADD_SELECTED_USERS', false, false);
            JToolBarHelper::custom('sync_all', 'sync', 'sync', 'JM_ADD_ALL_USERS', false, false);
        }

        // Get data from the model
        $items= $this->get('Data');
        $this->assignRef('items', $items);

        $campaign = $this->get('Campaign');
        $this->assignRef('campaign', $campaign);

        $lists = $this->get('Lists');
        $this->assignRef('lists', $lists);

        parent::display($tpl);
        require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
    }
}
