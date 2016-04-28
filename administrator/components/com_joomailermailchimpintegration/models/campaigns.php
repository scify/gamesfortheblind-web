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

class joomailermailchimpintegrationModelCampaigns extends jmModel {

    public function getClientDetails() {
        return $this->getModel('main')->getClientDetails();
    }

    public function getCampaigns($filters = array(), $page = 0, $limit = 5) {
        $cacheGroup = 'joomlamailerReports';
        $cacheID = 'Campaigns_' . implode('_', $filters) . '_' . $page . '_' . $limit;

        if (!$this->cache($cacheGroup)->get($cacheID, $cacheGroup)) {
            $campaigns = $this->getMcObject()->campaigns($filters, $page, $limit);

            $Jconfig = JFactory::getConfig();
            $tzoffset = $Jconfig->get('offset');
            if ($tzoffset != 'UTC') {
                foreach ($campaigns as $index => $campaign) {
                    date_default_timezone_set('Europe/London');
                    $datetime = new DateTime($campaign['send_time']);
                    $timeZone = new DateTimeZone($tzoffset);
                    $datetime->setTimezone($timeZone);
                    $campaigns[$index]['send_time'] = $datetime->format('Y-m-d H:i:s');
                }
            }

            $this->cache($cacheGroup)->store(json_encode($campaigns), $cacheID, $cacheGroup);
        }

        return json_decode($this->cache($cacheGroup)->get($cacheID, $cacheGroup), true);
    }

    public function getCampaignStats($campaignId) {
        return $this->getModel('main')->getCampaignStats($campaignId);
    }

    public function getOpens($cid) {
        return $this->getMcObject()->campaignOpenedAIM($cid);
    }

    public function getClicksAIM($cid, $url) {
        $results = $this->getMcObject()->campaignClickDetailAIM($cid, $url);
        uasort($results, array($this, 'cmp'));

        return $results;
    }

    public function getClicks($cid) {
        $results = $this->getMcObject()->campaignClickStats($cid);
        arsort($results);

        return $results;
    }

    public function getShareReport($cid, $title) {
        $opts = array(
            'secure' => false,
            'header_type' => 'text',
            'header_data' => $title,
            'password' => ''
        );

        return $this->getMcObject()->campaignShareReport($cid, $opts);
    }

    public function getCampaignData($cid) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__joomailermailchimpintegration_campaigns')
            ->where($db->qn('cid') . ' = ' . $db->q($cid));
        $db->setQuery($query);
        $result = $db->loadObjectList();

        if (!$result) {
            $result = $this->getMcObject()->campaigns(array('campaign_id' => $cid));
        }

        return $result;
    }

    public function getUserDetails($email) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn('id'))
            ->from('#__users')
            ->where($db->qn('email') . ' = ' . $db->q($email));
        $db->setQuery($query);
        $id = $db->loadResult();

        return ($id) ? JFactory::getUser($id) : false;
    }

    public function getAbuse($cid) {
        return $this->getMcObject()->campaignAbuseReports($cid);
    }

    public function getUnsubscribes($cid) {
        return $this->getMcObject()->campaignUnsubscribes($cid);
    }

    public function getAdvice($cid) {
        return $this->getMcObject()->campaignAdvice($cid);
    }

    public function getEmailDomainPerformance($cid) {
        return $this->getMcObject()->campaignEmailDomainPerformance($cid);
    }

    public function getClickStats($cid) {
        return $this->getMcObject()->campaignClickStats($cid);
    }

    public function getGeoOpens($cid) {
        return $this->getMcObject()->campaignGeoOpens($cid);
    }

    public function getHardBounces($cid) {
        return $this->getMcObject()->campaignHardBounces($cid);
    }

    public function getSoftBounces($cid) {
        return $this->getMcObject()->campaignSoftBounces($cid);
    }

    public function getCampaignEmailStatsAIMAll($cid, $page = 0, $limit = 1000) {
        return $this->getMcObject()->campaignEmailStatsAIMAll($cid, (int)$page, (int)$limit);
    }

    public function getGeoStats($cid) {
        $results = $this->getMcObject()->campaignGeoOpens($cid);

        if (is_array($results)){
            uasort($results, array($this, 'cmpGeo'));
        }

        return $results;
    }

    public function getTwitterStats($cid) {
        return $this->getMcObject()->campaignEepUrlStats($cid);
    }

    private function cmp($a, $b) {
        if ($a["clicks"] == $b["clicks"]) {
            return 0;
        }
        return ($a["clicks"] > $b["clicks"]) ? -1 : 1;
    }

    private function cmpGeo($a, $b) {
        if ($a["opens"] == $b["opens"]) {
            return 0;
        }
        return ($a["opens"] > $b["opens"]) ? -1 : 1;
    }
}
