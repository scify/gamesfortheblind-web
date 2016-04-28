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

class joomailermailchimpintegrationModelArchive extends jmModel {
    var $_data;

    public function getCampaigns() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $menuitemid = JRequest::getInt('Itemid', 0);
        if ($menuitemid) {
            $jSite = new JSite();
            $menu = $jSite->getMenu();
            $menuparams = $menu->getParams($menuitemid);
            $params->merge($menuparams);
        }

        $filters = array('status' => 'sent');
        $page = 0;
        $limit = $params->get('limit', 10);

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
}
