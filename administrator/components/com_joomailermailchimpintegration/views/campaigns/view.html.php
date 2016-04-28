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

jimport('joomla.cache.cache');

class joomailermailchimpintegrationViewCampaigns extends jmView {

    public function display($tpl = null) {
        if (!JOOMLAMAILER_MANAGE_REPORTS) {
            $this->app->enqueueMessage(JText::_('JERROR_ALERTNOAUTHOR'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration');
        }

        $this->setModel($this->getModelInstance('main'));
        $this->setModel($this->getModelInstance('campaignlist'));

        $option = JRequest::getCmd('option');
        $cacheGroup = 'joomlamailerReports';

        $cacheOptions = array();
        $cacheOptions['cachebase'] = JPATH_ADMINISTRATOR . '/cache';
        $cacheOptions['lifetime'] = 31556926;
        $cacheOptions['storage'] = 'file';
        $cacheOptions['defaultgroup'] = 'joomlamailerReports';
        $cacheOptions['locking'] = false;
        $cacheOptions['caching'] = true;

        $cache = new JCache($cacheOptions);
        require_once(JPATH_COMPONENT . '/helpers/JoomlamailerCache.php');

        $mainframe = JFactory::getApplication();
        $layout = JRequest::getVar('layout', '', '', 'string');
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($layout . '.limitstart', $layout . 'limitstart', 0, 'int');

        if ($layout == 'sharereport') {
            $document = JFactory::getDocument();
            $document->addStyleSheet(JURI::root() . 'media/com_joomailermailchimpintegration/backend/css/shareReport.css');
            $document->addScript(JURI::root() . 'media/com_joomailermailchimpintegration/backend/js/joomlamailer.shareReport.js');
            JToolBarHelper::title(JText::_('JM_NEWSLETTER_SHARE_REPORT'), $this->getPageTitleClass());
        } else {
            JToolBarHelper::title(JText::_('JM_NEWSLETTER_CAMPAIGN_STATS'), $this->getPageTitleClass());
        }

        if ($layout != '') {
            JToolBarHelper::custom('goToCampaigns', 'reports', 'reports', 'JM_REPORTS', false);
        }

        if ($layout == 'clickedlinks') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $clicked = $this->getModel()->getClicks($cid);
            foreach ($clicked as $index => $data) {
                if (!$data['clicks']) {
                    unset($clicked[$index]);
                }
            }
            $this->assignRef('clicked', $clicked);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            jimport('joomla.html.pagination');
            $pagination = new JPagination(count($clicked), $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'clickedlinkdetails') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $url = urldecode(JRequest::getVar('url', '', '', 'string'));
            $clicks = $this->getModel()->getClicksAIM($cid, $url);
            $this->assignRef('clicks', $clicks);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            jimport('joomla.html.pagination');
            $pagination = new JPagination(count($clicks), $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'clicked') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $clicked = $this->getModel()->getCampaignEmailStatsAIMAll($cid, $limitstart, 1000);

            $i = 0;
            $click = array();
            foreach ($clicked as $key => $value) {
                $unset = true;
                foreach ($value as $v) {
                    if ($v['action'] == 'click') {
                        $unset = false;
                    }
                }
                if (!$unset) {
                    $click[$key] = $clicked[$key];
                    $i++;
                }

                if ($i == $limit) {
                    break;
                }
            }
            $this->assignRef('clicked', $click);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            $total = $this->getModel()->getCampaignStats($cid);
            jimport('joomla.html.pagination');
            $pagination = new JPagination($total['unique_clicks'], $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'recipients') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $url = urldecode(JRequest::getVar('url', 0, '', 'string'));
            $clicked = $this->getModel()->getCampaignEmailStatsAIMAll($cid, $limitstart, $limit);
            $campaignStats = $this->getModel()->getCampaignStats($cid);
            $this->assignRef('clicked', $clicked);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            jimport('joomla.html.pagination');
            $pagination = new JPagination($campaignStats['emails_sent'], $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'opened') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $items = $this->getModel()->getOpens($cid);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            jimport('joomla.html.pagination');
            $pagination = new JPagination(count($items), $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'abuse') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $items = $this->getModel()->getAbuse($cid);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            jimport('joomla.html.pagination');
            $pagination = new JPagination(count($items), $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'unsubscribes') {
            $cid = JRequest::getVar('cid', 0, '', 'string');
            $items = $this->getModel()->getUnsubscribes($cid);
            $this->assignRef('limitstart', $limitstart);
            $this->assignRef('limit', $limit);
            jimport('joomla.html.pagination');
            $pagination = new JPagination(count($items), $limitstart, $limit, $layout);
            $this->assignRef('pagination', $pagination);

        } else if ($layout == 'sharereport') {
            $cid = JRequest::getVar('cid', '', 'get', 'string');

            $this->setModel($this->getModelInstance('campaigns'));
            $cData = $this->getModel('campaigns')->getCampaignData($cid);
            $name = (isset($cData[0]->name)) ? $cData[0]->name : $cData[0]['title'];
            $this->assignRef('name', $name);
            $data = $this->getModel('campaigns')->getShareReport($cid, JText::_('JM_CAMPAIGN_REPORT') . ': ' . $name);
            $this->assignRef('data', $data);
            $this->setModel($this->getModelInstance('templates'));
            $palettes = $this->getModel('templates')->getPalettes();
            $this->assignRef('palettes', $palettes);

        } else {
            $document = JFactory::getDocument();
            $document->addStyleSheet(JURI::root() . 'media/com_joomailermailchimpintegration/backend/css/campaigns.css');

            $JoomlamailerMC = new JoomlamailerMC();
            if (!$JoomlamailerMC->pingMC()) {
                $user = JFactory::getUser();
                if ($user->authorise('core.admin', 'com_joomailermailchimpintegration')) {
                    JToolBarHelper::preferences('com_joomailermailchimpintegration', '450');
                    JToolBarHelper::spacer();
                }
            } else {
                JToolBarHelper::custom('shareReport', 'shareReport', 'shareReport', 'JM_SEND_REPORT', true, false);
                JToolBarHelper::spacer();
                JToolBarHelper::custom('analytics', 'reports', 'reports', 'Analytics360°', false, false);
                JToolBarHelper::spacer();
                $user = JFactory::getUser();
                if ($user->authorise('core.admin', 'com_joomailermailchimpintegration')) {
                    JToolBarHelper::preferences('com_joomailermailchimpintegration', '450');
                    JToolBarHelper::spacer();
                }

                //	JToolBarHelper::custom('delete', 'delete', 'delete', 'JM_DELETE_REPORT', true, false);
                $tmp = array(array('folder_id' => 0, 'name' => '- ' . JText::_('JM_SELECT_FOLDER') . ' -'));
                $folders = $this->getModel('campaignlist')->getFolders();
                $folders = array_merge($tmp, $folders);

                $folder_id = JRequest::getVar('folder_id', 0, '', 'int');
                $foldersDropDown = JHTML::_('select.genericlist', $folders, 'folder_id', 'onchange="document.adminForm.submit();"', 'folder_id', 'name' , $folder_id);
                $this->assignRef('foldersDropDown', $foldersDropDown);

                $limit = JRequest::getVar('limit', 5, '', 'int');

                $cacheID = 'sent_campaigns';
                if (!$cache->get($cacheID, $cacheGroup)) {
                    $campaigns = array();
                    $res = 'not empty';
                    $page = 0;
                    while (!empty($res)) {
                        $res = $this->getModel()->getCampaigns(array('status' => 'sent'), $page, 1000);
                        if ($res){
                            $campaigns = array_merge($campaigns, $res);
                            if (count($res) < 1000) {
                                break;
                            }
                            $page++;
                        }
                    }

                    if (count($campaigns)) {
                        foreach ($campaigns as $c) {
                            $stats = $this->getModel()->getCampaignStats($c['id']);
                            $advice = $this->getModel()->getAdvice($c['id']);
                            if ($stats) {
                                $items[$c['id']]['folder_id'] = $c['folder_id'];
                                $items[$c['id']]['title'] = $c['title'];
                                $items[$c['id']]['subject'] = $c['subject'];
                                $items[$c['id']]['send_time'] = $c['send_time'];
                                $items[$c['id']]['emails_sent'] = $c['emails_sent'];
                                $items[$c['id']]['stats'] = $stats;
                                $items[$c['id']]['advice'] = $advice;
                                $items[$c['id']]['archive_url'] = $c['archive_url'];
                                $items[$c['id']]['twitter'] = $this->getModel()->getTwitterStats($c['id']);
                                $items[$c['id']]['geo'] = $this->getModel()->getGeoStats($c['id']);
                            } else {
                                $items[$c['id']]['folder_id'] = $c['folder_id'];
                                $items[$c['id']]['title'] = $c['title'];
                                $items[$c['id']]['subject'] = $c['subject'];
                                $items[$c['id']]['send_time'] = $c['send_time'];
                                $items[$c['id']]['emails_sent'] = $c['emails_sent'];
                                $items[$c['id']]['stats'] = '';
                                $items[$c['id']]['advice'] = '';
                                $items[$c['id']]['archive_url'] = $c['archive_url'];
                                $items[$c['id']]['twitter'] = $this->getModel()->getTwitterStats($c['id']);
                                $items[$c['id']]['geo'] = $this->getModel()->getGeoStats($c['id']);
                            }
                        }
                    }
                    $cache->store(json_encode($items), $cacheID, $cacheGroup);
                }
                $campaigns = json_decode($cache->get($cacheID, $cacheGroup), true);

                // get timestamp of when the cache was modified
                $joomlamailerCache = new JoomlamailerCache('file');
                $cacheDate = $joomlamailerCache->getCreationTime($cacheID, $cacheGroup);
                $this->assignRef('cacheDate', $cacheDate);

                if ($folder_id) {
                    foreach ($campaigns as $k => $v) {
                        if ($v['folder_id'] != $folder_id) {
                            unset($campaigns[$k]);
                        }
                    }
                }

                $total = count($campaigns);

                $items = array();
                $x = 0;
                if ($total) {
                    foreach ($campaigns as $k => $v) {
                        if ($x == ($limitstart + $limit)) {
                            break;
                        }
                        if ($x >= $limitstart) {
                            $items[$k] = $v;
                        }
                        $x++;
                    }
                }

                jimport('joomla.html.pagination');
                $pagination = new JPagination($total, $limitstart, $limit, $layout);
                $this->assignRef('pagination', $pagination);
            }
        }

        $this->assignRef('items', $items);

        parent::display($tpl);
        require_once(JPATH_COMPONENT . '/helpers/jmFooter.php');
    }
}
