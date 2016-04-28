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

class joomailermailchimpintegrationModelSend extends jmModel {

    public function getClientDetails() {
        return $this->getModel('main')->getClientDetails();
    }

    public function getDrafts() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__joomailermailchimpintegration_campaigns')
            ->where($db->qn('sent') . ' != ' . $db->q(2))
            ->order($db->qn('creation_date') . ' DESC');
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getMClists() {
        return $this->getMcObject()->lists();
    }

    public function getSentCampaigns() {
        $filters = array('status' => 'sent');
        return $this->getMcObject()->campaigns($filters);
    }

    public function getInterestGroupings($listId) {
        return $this->getMcObject()->listInterestGroupings($listId);
    }

    public function getMergeVars($listId) {
        return $this->getMcObject()->listMergeVars($listId);
    }

    public function getMergeFields($listId){
        return $this->getMcObject()->listMergeVars($listId);
    }

    /**
    * getAecAmbraVm
    *
    * check if either extension is installed: AEC, Ambra subscriptions, VirtueMart
    */
    public function getAecAmbraVm(){
        if (JFile::exists(JPATH_ADMINISTRATOR . '/components/com_acctexp/admin.acctexp.php')
            || JFile::exists(JPATH_ADMINISTRATOR . '/components/com_ambrasubs/ambrasubs.php')
            || JFile::exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/admin.virtuemart.php')){
            return true;
        } else {
            return false;
        }
    }

}
