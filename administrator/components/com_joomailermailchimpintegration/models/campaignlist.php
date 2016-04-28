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

class joomailermailchimpintegrationModelCampaignlist extends jmModel {

    private $cacheGroup = 'joomlamailerMisc';
    private static $total;

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->app = JFactory::getApplication();
    }

    public function getCampaigns() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $archiveDir = $params->get('params.archiveDir', '/administrator/components/com_joomailermailchimpintegration/archive');
        $filter = JRequest::getVar('filter_status', 'sent', '', 'string');
        $folder_id = JRequest::getVar('folder_id', 0, 'post', 'int');

        if ($filter == 'save') {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true);
            $where = array();
            $where[] = $db->qn('sent') . ' != ' . $db->q(1);
            if ($folder_id) {
                $where[] = $db->qn('folder_id') . ' = ' . $db->q($folder_id);
            }
            $query->select('SQL_CALC_FOUND_ROWS *, FROM_UNIXTIME(creation_date) AS date')
                ->from($db->qn('#__joomailermailchimpintegration_campaigns'))
                ->where($where)
                ->order($db->q('creation_date') . ' DESC');
            $db->setQuery($query);
            $data = $db->loadObjectList();

            $campaigns = array();
            $i = 0;
            if ($data) {
                foreach($data as $dat) {
                    $campaigns[$i]['id'] = $dat->creation_date;
                    $campaigns[$i]['title'] = $dat->name;
                    $campaigns[$i]['subject'] = $dat->subject;
                    $campaigns[$i]['creation_date'] = $dat->creation_date;

                    $campaignNameSafe = JApplication::stringURLSafe($dat->name);
                    if (JFile::exists(JPATH_SITE . '/' . (substr($archiveDir, 1)) . '/' . $campaignNameSafe . '.html')) {
                        $campaigns[$i]['archive_url'] = JURI::root() . substr($archiveDir, 1) . '/' . $campaignNameSafe . '.html';
                    } else {
                        $campaigns[$i]['archive_url'] = JURI::root() . substr($archiveDir, 1) . '/' . $campaignNameSafe . '.txt';
                    }
                    $i++;
                }

                $query = 'SELECT FOUND_ROWS()';
                $db->setQuery($query);
                joomailermailchimpintegrationModelCampaignlist::$total = $db->loadResult();
            } else {
                $campaigns = array();
                joomailermailchimpintegrationModelCampaignlist::$total = 0;
            }
        } else {
            $filters = array();
            $filters['status'] = $filter;
            if ($folder_id) {
                $filters['folder_id'] = $folder_id;
            }
            $limit = $this->app->getUserStateFromRequest('campaignlist_limit', 'campaignlist_limit', $this->app->getCfg('list_limit'), 'int');
            $limitstart = $this->app->getUserStateFromRequest('campaignlist_limitstart', 'campaignlist_limitstart', 0, 'int');

            $campaigns = $this->getModel('campaigns')->getCampaigns($filters, 0, 100);
            joomailermailchimpintegrationModelCampaignlist::$total = count($campaigns);
            if (count($campaigns) > $limit) {
                $tmp = $campaigns;
                $campaigns = array();
                for ($i = $limitstart; $i < ($limitstart + $limit); $i++) {
                    if (!isset($tmp[$i])) {
                        break;
                    }
                    $campaigns[] = $tmp[$i];
                }
            }
        }

        return $campaigns;
    }

    public function getCampaignStats($campaignId) {
        return $this->getModel('main')->getCampaignStats($campaignId);
    }

    public function getPagination() {
        jimport('joomla.html.pagination');
        $limit = $this->app->getUserStateFromRequest('campaignlist_limit', 'campaignlist_limit', $this->app->getCfg('list_limit'), 'int');
        $limitstart = $this->app->getUserStateFromRequest('campaignlist_limitstart', 'campaignlist_limitstart', 0, 'int');

        return new JPagination(joomailermailchimpintegrationModelCampaignlist::$total, $limitstart, $limit, 'campaignlist_');
    }

    public function getFolders() {
        $cacheGroup = 'joomlamailerMisc';
        $cacheID = 'Folders';
        if (!$this->cache($cacheGroup)->get($cacheID, $cacheGroup)) {
            $data = $this->getMcObject()->campaignFolders();
            $this->cache($cacheGroup)->store(json_encode($data), $cacheID, $cacheGroup);
        }
        $folders = json_decode($this->cache($cacheGroup)->get($cacheID, $cacheGroup), true);

        return ($folders) ? $folders : array();
    }
}
