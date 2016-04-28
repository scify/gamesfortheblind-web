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

class joomailermailchimpintegrationModelMain extends jmModel {

    public $cacheGroup = 'joomlamailerMisc';

    public function __construct() {
        parent::__construct();
    }

    public function setupInfo() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('value')
            ->from('#__joomailermailchimpintegration_misc')
            ->where($db->qn('type') . ' = ' . $db->q('setup_info'));
        $db->setQuery($query);
        $showInfo = $db->loadResult();
        $hide = ($showInfo) ? ' style="display:none;"' : '';

        $msg = '<br /><div id="setupInfo"' . $hide . '>
            <script type="text/javascript">var baseUrl = "' . JURI::base() . '";</script>
            <div class="alert alert-info">
                <div id="hideSetupInfo">' . JText::_('JM_HIDE') . '</div>
                <p>' . JText::_('JM_SETUP_INFO') . '</p>
            </div>
            </div>';

        return $msg;
    }

    public function getDrafts() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__joomailermailchimpintegration_campaigns')
            ->where($db->qn('sent') . ' = 0')
            ->order($db->qn('creation_date') . ' DESC');
        $db->setQuery($query, 0, 5);

        return $db->loadObjectList();
    }

    public function getMailChimpDataCenter() {
        list($key, $dc) = explode('-', $this->getMcObject()->api_key, 2);
        if (!$dc) {
            $dc = 'us1';
        }

        return $dc;
    }

    public function getClientDetails() {
        $cacheID = 'AccountDetails';
        if (!$this->cache($this->cacheGroup)->get($cacheID, $this->cacheGroup)) {
            $data = $this->getMcObject()->getAccountDetails();
            $this->cache($this->cacheGroup)->store(json_encode($data), $cacheID, $this->cacheGroup);
        }

        return json_decode($this->cache($this->cacheGroup)->get($cacheID, $this->cacheGroup), true);
    }

    public function getCampaigns() {
        return $this->getModel('campaigns')->getCampaigns(array(), 0, 25);
    }

    public function getCampaignStats($campaignId) {
        $cacheID = 'CampaignStats_' . $campaignId;
        if (!$this->cache($this->cacheGroup)->get($cacheID, $this->cacheGroup)) {
            $data = $this->getMcObject()->campaignStats($campaignId);
            $this->cache($this->cacheGroup)->store(json_encode($data), $cacheID, $this->cacheGroup);
        }

        return json_decode($this->cache($this->cacheGroup)->get($cacheID, $this->cacheGroup), true);
    }

    public function getChimpChatter() {
        $cacheID = 'ChimpChatter';
        if (!$this->cache($this->cacheGroup)->get($cacheID, $this->cacheGroup)) {
            $data = $this->getMcObject()->chimpChatter();
            $this->cache($this->cacheGroup)->store(json_encode($data), $cacheID, $this->cacheGroup);
        }

        return json_decode($this->cache($this->cacheGroup)->get($cacheID, $this->cacheGroup), true);
    }

    public function emptyCache($cacheGroup) {
        $this->cache($cacheGroup)->clean($cacheGroup);
    }

    public function purgeCache() {
        $cacheGroups = array(
            'joomlamailerMisc',
            'joomlamailerReports'
        );

        foreach ($cacheGroups as $group) {
            $this->emptyCache($group);
        }
    }
}
