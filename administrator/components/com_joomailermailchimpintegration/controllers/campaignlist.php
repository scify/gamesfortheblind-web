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

class joomailermailchimpintegrationControllerCampaignlist extends joomailermailchimpintegrationController {

     public function __construct($config = array()) {
        parent::__construct($config);
        $this->registerTask('add' , 'edit');
    }

    public function edit() {
        $db = JFactory::getDBO();
        $cid = JRequest::getVar('cid', '', 'post', 'array');

        $query = $db->getQuery(true);
        $query->select($db->qn(array('cdata', 'folder_id')))
            ->from('#__joomailermailchimpintegration_campaigns')
            ->where($db->qn('creation_date') . ' = ' . $db->q($cid[0]));
        $db->setQuery($query);
        $result = $db->loadAssocList();
        $cdata = json_decode($result[0]['cdata']);

        JRequest::setVar('cid', $cid[0]);
        foreach ($cdata as $k => $v) {
            JRequest::setVar($k, $v);
        }
        JRequest::setVar('view',   'create');
        JRequest::setVar('action', 'edit');
        JRequest::setVar('layout', 'default' );
        JRequest::setVar('hidemainmenu', 0);
        JRequest::setVar('offset', 0);
        parent::display();
    }

    public function send() {
        $cid = JRequest::getVar('cid', '', 'post', 'array');
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=send&campaign=' . $cid[0]);
    }

    public function unschedule() {
        $msg = JText::_('JM_CAMPAIGNS_UNSCHEDULED');
        $msgType = 'message';

        $cid = JRequest::getVar('cid', '', 'post', 'array');
        if (!$cid) {
            $msg = JText::_('JM_INVALID_CAMPAIGNID');
            $msgType = 'error';
        } else {
            $MCerrorHandler = new MCerrorHandler();

            foreach ($cid as $c) {
                $result = $this->getModel('campaignlist')->getMcObject()->campaignDelete($c);
                if (!$result) {
                    $msg = $MCerrorHandler->getErrorMsg($this->getModel('campaignlist')->getMcObject());
                    $msgType = 'error';
                    break;
                } else {
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->update('#__joomailermailchimpintegration_campaigns')
                        ->set($db->qn('sent') . ' = ' . $db->q(0))
                        ->set($db->qn('cid') . ' = ""')
                        ->where($db->qn('cid') . ' = ' . $db->q($c));
                    $db->setQuery($query);
                    $db->execute();
                }
            }
        }

        if ($msgType != 'error') {
            $this->getModel('main')->emptyCache('joomlamailerReports');
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=campaignlist&filter_status=' . JRequest::getVar('type', 'sent');
        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    // you can only pause autoresponder and rss campaigns
    public function pause(){
        $msg = JText::_('JM_CAMPAIGNS_PAUSED');
        $msgType = 'message';
        $cid = JRequest::getVar('cid', '', 'post', 'array');

        if (!$cid){
            $msg = JText::_('JM_INVALID_CAMPAIGNID');
            $msgType = 'error';
        } else {
            $MCerrorHandler = new MCerrorHandler();

            foreach($cid as $c){
                $result = $this->getModel('campaignlist')->getMcObject()->campaignPause($c);
                if (!$result) {
                    $msg = $MCerrorHandler->getErrorMsg($this->getModel('campaignlist')->getMcObject());
                    $msgType = 'error';
                    break;
                }
            }
        }

        if ($msgType != 'error') {
            $this->getModel('main')->emptyCache('joomlamailerReports');
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=campaignlist&filter_status='.JRequest::getVar('type', 'sent');
        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    public function resume() {
        $msg = JText::_('JM_CAMPAIGNS_RESUMED');
        $msgType = 'message';
        $cid = JRequest::getVar('cid', '', 'post', 'array');

        if (!$cid) {
            $msg = JText::_('JM_INVALID_CAMPAIGNID');
            $msgType = 'error';
        } else {
            $MCerrorHandler = new MCerrorHandler();

            foreach ($cid as $c) {
                $result = $this->getModel('campaignlist')->getMcObject()->campaignResume($c);
                if (!$result) {
                    $msg = $MCerrorHandler->getErrorMsg($this->getModel('campaignlist')->getMcObject());
                    $msgType = 'error';
                    break;
                }
            }
        }

        if ($msgType != 'error') {
            $this->getModel('main')->emptyCache('joomlamailerReports');
        }

        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    public function copyCampaign() {
        $cid = JRequest::getVar('cid', '', 'post', 'array');
        JRequest::setVar('cid', $cid[0]);
        require_once(JPATH_COMPONENT . '/controllers/main.php');
        $mainController = new joomailermailchimpintegrationControllerMain();
        $mainController->copyCampaign();
    }

    public function remove() {
        $cid = JRequest::getVar('cid', '', 'post', 'array');
        $status = JRequest::getVar('filter_status', '', 'post', 'string');
        $msg = ($status == 'save') ? JText::_('JM_DRAFT_DELETED') : JText::_('JM_CAMPAIGNS_DELETED');
        $msgType = 'message';

        if (!$cid){
            $msg = JText::_('JM_INVALID_CAMPAIGNID');
            $msgType = 'error';
        } else {
            if ($status == 'save') {
                jimport('joomla.filesystem.file');
                jimport('joomla.client.helper');
                JClientHelper::setCredentialsFromRequest('ftp');
                $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
                $archiveDir = $params->get('params.archiveDir', '/administrator/components/com_joomailermailchimpintegration/archive');
                $path = JPATH_SITE . $archiveDir . '/';
                foreach($cid as $c){
                    $db = JFactory::getDBO();
                    $query = $db->getQuery(true);
                    $query->select($db->qn('name'))
                        ->from('#__joomailermailchimpintegration_campaigns')
                        ->where($db->qn('creation_date') . ' = ' . $db->q($c));
                    $db->setQuery($query);
                    $cName = $db->loadResult();
                    $cName = str_replace(' ', '_', $cName);
                    $cName = htmlentities($cName);

                    if ((JFile::exists($path . $cName . '.html') && !JFile::delete($path . $cName . '.html')) ||
                        (JFile::exists($path . $cName . '.txt') && !JFile::delete($path . $cName . '.txt')) ){
                        $msg = JText::_('JM_DELETE_FAILED');
                        $error = 'error';
                    } else {
                        $query = $db->getQuery(true);
                        $query->delete('#__joomailermailchimpintegration_campaigns')
                            ->where($db->qn('creation_date') . ' = ' . $db->q($c));
                        $db->setQuery($query);
                        $db->execute();
                    }
                }
            } else {
                $MCerrorHandler = new MCerrorHandler();

                foreach ($cid as $c) {
                    $result = $this->getModel('campaignlist')->getMcObject()->campaignDelete($c);
                    if (!$result) {
                        $msg = $MCerrorHandler->getErrorMsg($this->getModel('campaignlist')->getMcObject());
                        $msgType = 'error';
                        break;
                    }
                }
            }
        }

        if ($msgType != 'error') {
            $this->getModel('main')->purgeCache();
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=campaignlist&filter_status=' . JRequest::getVar('type', 'sent');
        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    public function create() {
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=create');
    }

    public function cancel() {
        $link = 'index.php?option=com_joomailermailchimpintegration&view=campaignlist&filter_status=' . JRequest::getVar('type', 'sent');
        $this->app->redirect($link);
    }
}
