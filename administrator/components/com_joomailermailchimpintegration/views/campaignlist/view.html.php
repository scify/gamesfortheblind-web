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

class joomailermailchimpintegrationViewCampaignlist extends jmView {

    public function display($tpl = null) {
        if (!JOOMLAMAILER_CREATE_DRAFTS && !JOOMLAMAILER_MANAGE_CAMPAIGNS) {
            $this->app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration');
        }

        JToolBarHelper::title(JText::_('JM_NEWSLETTER_CAMPAIGNS'), $this->getPageTitleClass());

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        $JoomlamailerMC = new JoomlamailerMC();

        if ($MCapi && $JoomlamailerMC->pingMC()) {
            if (JOOMLAMAILER_CREATE_DRAFTS) {
                JToolBarHelper::custom('create', 'create-campaign', 'create-campaign', 'JM_CREATE_CAMPAIGN', false, false);
                JToolBarHelper::spacer();
            }

            $filter = JRequest::getVar('filter_status', 'sent', '', 'string');
            if (JOOMLAMAILER_CREATE_DRAFTS && !JOOMLAMAILER_MANAGE_CAMPAIGNS) {
                $filter = 'save';
                JRequest::setVar('filter_status', 'save');
            } else if (!JOOMLAMAILER_CREATE_DRAFTS && JOOMLAMAILER_MANAGE_CAMPAIGNS) {
                if ($filter == 'save') {
                    JRequest::setVar('filter_status', 'sent');
                }
            }
            if ($filter == 'save') {
                if (JOOMLAMAILER_CREATE_DRAFTS) {
                    JToolBarHelper::editList();
                    JToolBarHelper::spacer();
                }
                if (JOOMLAMAILER_MANAGE_CAMPAIGNS) {
                    JToolBarHelper::custom('send', 'send', 'send', 'JM_SEND', true, false);
                    JToolBarHelper::spacer();
                }
                if (JOOMLAMAILER_CREATE_DRAFTS) {
                    JToolBarHelper::deleteList(JText::_('JM_ARE_YOU_SURE_TO_DELETE_THE_SELECTED_CAMPAIGNS'));
                    JToolBarHelper::spacer();
                }
            } else if ($filter == 'schedule') {
                JToolBarHelper::custom('unschedule', 'unschedule', 'unschedule', 'JM_UNSCHEDULE', true, false);
                JToolBarHelper::spacer();
                // you can only pause autoresponder and rss campaigns
                //  JToolBarHelper::custom('pause', 'pause', 'pause', 'Pause', true, false);
                if (JOOMLAMAILER_MANAGE_CAMPAIGNS) {
                    JToolBarHelper::deleteList(JText::_('JM_ARE_YOU_SURE_TO_DELETE_THE_SELECTED_CAMPAIGNS'));
                    JToolBarHelper::spacer();
                }
            } else if ($filter == 'sent') {
                JToolBarHelper::custom('copyCampaign', 'copy', 'copy', 'JM_REPLICATE', true, false);
                JToolBarHelper::spacer();
                if (JOOMLAMAILER_MANAGE_REPORTS) {
                    JToolBarHelper::deleteList(JText::_('JM_ARE_YOU_SURE_TO_DELETE_THE_SELECTED_CAMPAIGNS'));
                    JToolBarHelper::spacer();
                }
            } else if ($filter == 'sending') {
                if (JOOMLAMAILER_MANAGE_CAMPAIGNS) {
                    JToolBarHelper::custom('pause', 'pause', 'pause', 'JM_PAUSE', true, false);
                    JToolBarHelper::spacer();
                }
                if (JOOMLAMAILER_MANAGE_REPORTS) {
                    JToolBarHelper::deleteList(JText::_('JM_ARE_YOU_SURE_TO_DELETE_THE_SELECTED_CAMPAIGNS'));
                    JToolBarHelper::spacer();
                }
            } else if ($filter == 'paused') {
                if (JOOMLAMAILER_MANAGE_CAMPAIGNS) {
                    JToolBarHelper::custom('resume', 'resume', 'resume', 'JM_RESUME', true, false);
                    JToolBarHelper::spacer();
                }
                if (JOOMLAMAILER_MANAGE_REPORTS) {
                    JToolBarHelper::deleteList(JText::_('JM_ARE_YOU_SURE_TO_DELETE_THE_SELECTED_CAMPAIGNS'));
                    JToolBarHelper::spacer();
                }
            }
        }

        if (JOOMLAMAILER_MANAGE_REPORTS) {
            $folders = $this->get('Folders');
            $undefined[0] = array('folder_id' => 0, 'name' => JText::_('JM_UNFILED'));
            $folder_id = JRequest::getVar('folder_id', 0, '', 'int');
            $folders = array_merge($undefined, $folders);
            $foldersDropDown = JHTML::_('select.genericlist', $folders, 'folder_id', 'onchange="document.adminForm.submit();"', 'folder_id', 'name' , $folder_id);
            $this->assignRef('foldersDropDown', $foldersDropDown);
        }

        // Get data from the model
        $campaigns = $this->get('Campaigns');
        $this->assignRef('campaigns', $campaigns);

        $page = $this->get('Pagination');
        $this->assignRef('pagination', $page);

        parent::display($tpl);
        require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
    }
}
