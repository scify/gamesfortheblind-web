<?php
/**
* Copyright (C) 2012  freakedout (www.freakedout.de)
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

class joomailermailchimpintegrationModelSubscriber extends jmModel {

    private static $data;
    private $db;
    protected $_total = null;
    protected $pagination = null;

    public function __construct() {
        parent::__construct();
        $mainframe = JFactory::getApplication();
        $this->db = JFactory::getDBO();

        // Get pagination request variables
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    private function buildQuery() {
        $mainframe = JFactory::getApplication();

        $filter_type = $mainframe->getUserStateFromRequest('filter_type', 'filter_type', 0, 'string');
        $search    = JString::strtolower($mainframe->getUserStateFromRequest('search', 'search', '', 'string'));

        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart    = $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

        $where = array();
        if ($search != '') {
            $searchEscaped = '"%' . $this->db->getEscaped($search, true) . '%"';
            $where[] = $this->db->qn('username') . ' LIKE ' . $this->db->q($searchEscaped)
                . ' OR ' . $this->db->qn('email') . ' LIKE ' . $this->db->q($searchEscaped)
                . ' OR ' . $this->db->qn('name') . ' LIKE ' . $this->db->q($searchEscaped);
        }

        if ($filter_type) {
            if ($filter_type == 'Public Frontend') {
                $where[] = $this->db->qn('usertype') . ' = ' . $this->db->q('Registered')
                    . ' OR ' . $this->db->qn('usertype') . ' = ' . $this->db->q('Author')
                    . ' OR ' . $this->db->qn('usertype') . ' = ' . $this->db->q('Editor')
                    . ' OR ' . $this->db->qn('usertype') . ' = ' . $this->db->q('Publisher');
            }
            else if ($filter_type == 'Public Backend') {
                $where[] = $this->db->qn('usertype') . ' = ' . $this->db->q('Manager')
                    . ' OR ' . $this->db->qn('usertype') . ' = ' . $this->db->q('Administrator')
                    . ' OR ' . $this->db->qn('usertype') . ' = ' . $this->db->q('Super Administrator');
            } else {
                $where[] = $this->db->qn('usertype') . ' = LOWER(' . $this->db->q($filter_type) . ')';
            }
        }

        $where[] = $this->db->qn('block') . ' = "0"';

        $where = (count($where) ? ' WHERE (' . implode(') AND (', $where) . ')' : '');

        $query = $this->db->getQuery(true);
        $query->select($this->db->quoteName(array('id', 'name', 'username', 'email', 'block', 'usertype')))
            ->from($this->db->qn('#__users'))
            ->where($where)
            ->order($this->db->qn('id'));

        return $query;
    }

    public function getData() {
        // Lets load the data if it doesn't already exist
        if (empty(joomailermailchimpintegrationModelSubscriber::$data)) {
            //$this->db->setQuery( $query, $pageNav->limitstart, $pageNav->limit );
            //$this->data = $this->db->loadObjectList();

            //$this->data = $this->getList( $query );
            $query = $this->buildQuery();
            joomailermailchimpintegrationModelSubscriber::$data =
                $this->getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }
        return joomailermailchimpintegrationModelSubscriber::$data;
    }

    public function getUser ($id) {
        $query = $this->db->getQuery(true);
        $query->select($this->db->qn(array('id', 'name', 'username', 'email', 'block', 'usertype')))
            ->from($this->db->qn('#__users'))
            ->where($this->db->qn('id') . ' = ' . $this->db->q($id));

        return $this->getList($query);
    }

    public function getClientDetails() {
        return $this->MC_object()->getAccountDetails();
    }

    public function getListsForEmail() {
        $email = str_replace(' ', '+', JRequest::getVar('email', 0, '', 'string', JREQUEST_ALLOWRAW));
        $listsForEmail = $this->MC_object()->listsForEmail($email);
        $lists = $this->MC_object()->lists();
        foreach ($lists as $list) {
            if (in_array($list['id'], $listsForEmail)) {
                $memberInfo = $this->MC_object()->listMemberInfo($list['id'], $email);
                $listsArray = $memberInfo;
                $listsArray['lists'][$list['id']] = array(
                    'id' => $list['id'],
                    'name' => $list['name'],
                    'member_count' => $list['member_count'],
                    'member_rating' => $memberInfo['member_rating']
                );
            }
        }

        return $listsArray;
    }

    public function getListMemberInfo($listId, $email) {
        return $this->MC_object()->listMemberInfo($listId, $email);
    }

    public function getSubscribed() {
        $query = $this->db->getQuery(true)
            ->select('*')
            ->from($this->db->qn('#__joomailermailchimpintegration'));
        $res = $this->getList($query);

        return $res;
    }

    public function getUsers() {
        $query = $this->db->getQuery(true);
        $query->select('*')
            ->from($this->db->qn('#__users'));
        $res = $this->getList($query);

        return $res;
    }

    public function getActive(){
        $MC = $this->MC_object();
        $listid = JRequest::getVar('listid',  0, '', 'string');
        $type = JRequest::getVar('type',  's', '', 'string');
        $option = JRequest::getCmd('option');

        $mainframe = JFactory::getApplication();
        $limit = $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart = $mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

        //        var_dump($limitstart);var_dump($limit);
        if ($limit == 0) {
            $limit = 15000;
            $limitstart = 0;
        } else {
            $limitstart = round($limitstart / $limit, 0);
        }
        //var_dump($limitstart);var_dump($limit);
        switch($type) {
            case 's':
                $result = $MC->listMembers( $listid, 'subscribed', '', $limitstart, $limit);
                break;
            case 'u':
                $result = $MC->listMembers( $listid, 'unsubscribed', '', $limitstart, $limit);
                break;
            case 'c':
                $result = $MC->listMembers( $listid, 'cleaned', '', $limitstart, $limit);
                break;
        }

        return ($result) ? $result : false;
    }

    public function getListDetails() {
        $MC = $this->MC_object();

        return $MC->lists();
    }

    public function campaignEmailStatsAIM($listId, $email) {
        $MC = $this->MC_object();

        return $MC->campaignEmailStatsAIM($listId, $email);
    }

    public function campaignOpenedAIM($campaignId) {
        $MC = $this->MC_object();

        return $MC->campaignOpenedAIM($campaignId);
    }

    public function getTotal() {
        $listId = JRequest::getVar('listid',  0, '', 'string');
        $type   = JRequest::getVar('type',  's', '', 'string');

        $lists = $this->getListDetails();
        foreach($lists as $list) {
            if ($list['id'] == $listId) {
                switch($type) {
                    case 's':
                        $total = $list['member_count'];
                        break;
                    case 'u':
                        $total = $list['unsubscribe_count'];
                        break;
                    case 'c':
                        $total = $list['cleaned_count'];
                        break;
                }
                break;
            }
        }

        return $total;
    }

    public function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->pagination)) {
            $limit        = $mainframe->getUserStateFromRequest( 'global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int' );
            $limitstart = $mainframe->getUserStateFromRequest( $option.'.limitstart', 'limitstart', 0, 'int' );
            if ($limit==0){
                $limit = 15000;
            }
            jimport('joomla.html.pagination');
            var_dump($this->getTotal());die;
            $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit') );
        }
        return $this->pagination;
    }

    public function getMemberInfo($id,$email) {
        $mc = $this->MC_object();

        return $mc->listMemberInfo($id,$email);
    }

    public function getHardBounces($cid) {
        $mc = $this->MC_object();

        return $mc->campaignHardBounces($cid);
    }

    public function getSoftBounces($cid) {
        $mc = $this->MC_object();

        return $mc->campaignSoftBounces($cid);
    }

    public function getCampaignsSince($date) {
        $mc = $this->MC_object();

        return $mc->campaigns(array('sendtime_start' => $date, 'status' => 'sent'));
    }

    public function getAmbraPayments() {
        $userId = JRequest::getInt('uid');

        $query = $this->db->getQuery(true);
        $query->select($thid->db->qn(array('u.created_datetime', 't.title', 't.value'), array('created_datetime', 'title', 'price')))
            ->from($this->db->qn('#__ambrasubs_users2types') . ' AS u')
            ->join('LEFT', $this->db->qn('#__ambrasubs_types') . ' AS t ON ' . $this->db->qn('u.typeid') . ' = ' . $this->db->qn('t.id'))
            ->where($this->db->qn('u.userid') . ' = ' . $this->db->q($userId));
        $this->db->setQuery($query);

        return $this->db->loadObjectList();
    }

    /**
    * Get either a Gravatar URL or complete image tag for a specified email address.
    *
    * @param boole $img True to return a complete IMG tag False for just the URL
    * @param string $s Size in pixels, defaults to 80px [ 1 - 512 ]
    * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
    * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
    * @param array $atts Optional, additional key/value attributes to include in the IMG tag
    * @return String containing either just a URL or a complete image tag
    * @source http://gravatar.com/site/implement/images/php/
    */
    public function getGravatar($default = '', $img = false, $s = 155, $d = 'mm', $r = 'g', $atts = array()) {
        $email = str_replace(' ', '+', JRequest::getVar('email'));
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($default) {
            $url .= '&amp;default=' . urlencode($default);
        }
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }

        return $url;
    }

    public function getJomSocialGroups() {
        $userId = JRequest::getInt('uid');

        if ($this->isJomSocialInstalled()) {
            $query = $this->db->getQuery(true);
            $query->select($this->db->qn(array('g.id', 'g.name')))
                ->from($this->db->qn('#__community_groups_members') . ' AS m')
                ->join('LEFT', $this->db->qn('#__community_groups') . ' AS g ON ' . $this->db->q('m.groupid') . ' = ' . $this->db->q('g.id'))
                ->where($this->db->qn('m.memberid') . ' = ' . $this->db->q($userId));
            $this->db->setQuery($query);

            return $this->db->loadObjectList();
        }

        return '';
    }

    public function getRecentJomSocialDiscussions() {
        $userId = JRequest::getInt('uid');

        if ($this->isJomSocialInstalled()) {
            $query = $this->db->getQuery(true);
            $query->select($this->db->qn(array('id', 'title', 'groupid')))
                ->from($this->db->qn('#__community_groups_discuss'))
                ->where($this->db->qn('creator') . ' = ' . $this->db->q($userId))
                ->order($this->db->qn('created DESC'));

            $this->db->setQuery($query, 0, 5);

            return $this->db->loadObjectList();
        }

        return '';
    }

    public function getTotalJomSocialDiscussionsOfUser() {
        $userId = JRequest::getInt('uid');

        if ($this->isJomSocialInstalled()) {
            $query = $this->db->getQuery(true);
            $query->select($this->db->qn('COUNT(*)', 'count'))
                ->from($this->db->qn('#__community_groups_discuss'))
                ->where($this->db->qn('creator') . ' = ' . $this->db->q($userId));
            $this->db->setQuery($query);

            return $this->db->loadObject()->count;
        }

        return '';
    }

    public function getKloutScore() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $kloutAPIkey = $params->get('params.KloutAPI');
        $twitterName = $this->getTwitterName();
        $kscore = 0;

        if ($twitterName != '') {
            $kloutXML = new DOMDocument();
            $kloutDataString = @file_get_contents('http://api.klout.com/1/klout.xml?key=' . $kloutAPIkey . '&users=' . $twitterName);
            if ($kloutDataString) {
                $kloutXML->loadXML($kloutDataString);
                $kscore = (int)$kloutXML->getElementsByTagName('kscore')->item(0)->nodeValue;
            }
        } else {
            $kscore = false;
        }

        return $kscore;
    }

    public function getTwitterName() {
        $userId = JRequest::getInt('uid');
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $twitterName = $params->get('params.jomsocial_twitter_name');

        if ($twitterName != '' && $this->isJomSocialInstalled()) {
            $query = $this->db->getQuery(true);
            $query->select($this->db->qn('v.value'))
                ->from($this->db->qn('#__community_fields') . ' AS f')
                ->join('LEFT', $this->db->qn('#__community_fields_values') . ' AS v ON ' . $this->db->qn('f.id') . ' = ' . $this->db->qn('v.field_id'))
                ->where($this->db->qn('fieldcode') . ' = ' . $this->db->q($twitterName))
                ->where($this->db->qn('v.user_id') . ' = ' . $this->db->q($userId));
            $this->db->setQuery($query);

            return $this->db->loadObject()->value;
        } else {
            return false;
        }
    }

    public function getFacebookName() {
        $userId = JRequest::getInt('uid');

        if($this->isJomSocialInstalled()) {
            $query = $this->db->getQuery(true);
            $query->select($this->db->qn('connectid'))
                ->from($this->db->qn('#__community_connect_users'))
                ->where($this->db->qn('userid') . ' = ' . $this->db->q($userId));
            $this->db->setQuery($query);
            $result = $this->db->loadObject();

            return ($result != NULL) ? $result->connectid : '';
        }

        return '';
    }

    public function isJomSocialInstalled() {
        return JFile::exists(JPATH_ADMINISTRATOR . '/components/com_community/admin.community.php');
    }


    private function MC_object() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');

        return new joomlamailerMCAPI($MCapi);
    }
}
