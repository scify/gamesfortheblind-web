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

class joomailermailchimpintegrationModelSubscribers extends jmModel {

    private static $data;
    private $pagination = null;
    protected $mainframe, $db;

    public function __construct() {
        parent::__construct();

        $this->mainframe = JFactory::getApplication();
        $this->db = JFactory::getDBO();

        $option = JRequest::getCmd('option');

        // Get pagination request variables
        $limit = $this->mainframe->getUserStateFromRequest('global.list.limit', 'limit', $this->mainframe->getCfg('list_limit'), 'int');
        $limitstart = JRequest::getVar('limitstart', 0, '', 'int');

        // In case limit has been changed, adjust it
        $limitstart = ($limit != 0 ? (floor($limitstart / $limit) * $limit) : 0);

        $this->setState('limit', $limit);
        $this->setState('limitstart', $limitstart);
    }

    private function buildQuery() {
        $filter_type = $this->mainframe->getUserStateFromRequest('filter_type',	'filter_type', 0, 'string');
        $search	     = JString::strtolower($this->mainframe->getUserStateFromRequest('search', 'search', '', 'string'));
        $limit	     = $this->mainframe->getUserStateFromRequest('global.list.limit', 'limit', $this->mainframe->getCfg('list_limit'), 'int');
        $limitstart  = $this->mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

        $where = array();
        if (isset($search) && $search != '') {
            $searchEscaped = '"%' . $this->db->escape($search, true) . '%"';
            $where[] = $this->db->qn('username') . ' LIKE ' . $searchEscaped .
                ' OR ' . $this->db->qn('email') . ' LIKE ' . $searchEscaped .
                ' OR ' . $this->db->qn('name') . ' LIKE ' . $searchEscaped;
        }

        if ($filter_type) {
            if ($filter_type == 'Public Frontend') {
                $where[] = $this->db->qn('usertype') . ' IN ("Registered","Author","Editor","Publisher")';
            } else if ($filter_type == 'Public Backend') {
                $where[] = $this->db->qn('usertype') . ' IN ("Manager","Administrator","Super Administrator")';
            } else {
                $where[] = $this->db->qn('usertype') . ' = LOWER(' . $this->db->q($filter_type) . ')';
            }
        }

        $where[] = $this->db->qn('block') . ' = ' . $this->db->q('0');

        $query = $this->db->getQuery(true);
        $query->select($this->db->qn(array('id', 'name', 'username', 'email', 'block', 'usertype')))
            ->from($this->db->qn('#__users'))
            ->where($where)
            ->order($this->db->qn('id'));

        return $query;
    }

    public function getData() {
        if (empty(joomailermailchimpintegrationModelSubscribers::$data)) {
            $query = $this->buildQuery();
            joomailermailchimpintegrationModelSubscribers::$data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return joomailermailchimpintegrationModelSubscribers::$data;
    }

    public function getUser($id) {
        $query = $this->db->getQuery(true)
            ->select($this->db->qn(array('id', 'name', 'username', 'email', 'block', 'usertype')))
            ->from($this->db->qn('#__users'))
            ->where($this->db->qn('id') . ' = ' . $this->db->q($id));

        return $this->_getList($query);
    }

    public function addJoomlaUserData(&$users) {
        if (!count($users)) {
            return;
        }
        $emails = array();
        foreach ($users as $user) {
            $emails[] = $user['email'];
        }

        $query = $this->db->getQuery(true);
        $query->select($this->db->qn(array('id', 'email')))
            ->from($this->db->qn('#__users'))
            ->where($this->db->qn('email') . ' IN ("' . implode('","', $emails) . '")');
        $this->db->setQuery($query);
        $res = $this->db->loadObjectList();

        $jUsers = array();
        foreach ($res as $r) {
            $jUsers[$r->email] = $r->id;
        }

        foreach ($users as $index => $user) {
            if (isset($jUsers[$user['email']])) {
                $users[$index] = JFactory::getUser($jUsers[$user['email']]);
                $users[$index]->timestamp = $user['timestamp'];
            } else {
                $users[$index] = new stdClass();
                $users[$index]->id = '';
                $users[$index]->name = '';
                $users[$index]->email = $user['email'];
                $users[$index]->timestamp = $user['timestamp'];
            }
        }
    }

    public function getSubscribed() {
        $query = $this->db->getQuery(true);
        $query->select($this->db->qn('*'))
            ->from($this->db->qn('#__joomailermailchimpintegration'));

        return $this->_getList($query);
    }

    /* not used
    public function getUsers() {
        $query = 'SELECT * FROM #__users';
        $this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        return $this->data;
    }*/

    public function getActive() {
        $listid = JRequest::getVar('listid',  0, '', 'string');
        $type = JRequest::getVar('type',  's', '', 'string');
        $option = JRequest::getCmd('option');

        $limit= $this->mainframe->getUserStateFromRequest('global.list.limit', 'limit', $this->mainframe->getCfg('list_limit'), 'int');
        $limitstart = $this->mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');

        if ($limit == 0) {
            $limit = 15000; $limitstart = 0;
        } else {
            $limitstart = round($limitstart / $limit, 0);
        }

        switch ($type) {
            case 's':
                $result = $this->getMcObject()->listMembers($listid, 'subscribed', '', $limitstart, $limit);
                break;
            case 'u':
                $result = $this->getMcObject()->listMembers($listid, 'unsubscribed', '', $limitstart, $limit);
                break;
            case 'c':
                $result = $this->getMcObject()->listMembers($listid, 'cleaned', '', $limitstart, $limit);
                break;
        }

        return ($result) ? $result : array();
    }

    public function getUserDetails($email, $list) {
        return $this->getMcObject()->listMemberInfo($list, $email);
    }

    public function getLists() {
        return $this->getModel('lists')->getLists();
    }


    public function getTotal() {
        $listId = JRequest::getVar('listid',  0, '', 'string');
        $type   = JRequest::getVar('type',  's', '', 'string');

        $lists = $this->getLists();
        foreach ($lists as $list) {
            if ($list['id'] == $listId) {
                switch ($type) {
                    case 's':
                        return $list['member_count'];
                    case 'u':
                        return $list['unsubscribe_count'];
                    case 'c':
                        return $list['cleaned_count'];
                }
            }
        }
    }

    public function getPagination() {
        // Load the content if it doesn't already exist
        if (empty($this->pagination)) {
            $option = JRequest::getCmd('option');
            $limit = $this->mainframe->getUserStateFromRequest('global.list.limit', 'limit', $this->mainframe->getCfg('list_limit'), 'int');
            $limitstart = $this->mainframe->getUserStateFromRequest($option.'.limitstart', 'limitstart', 0, 'int');
            if ($limit == 0){
                $limit = 15000;
            }
            jimport('joomla.html.pagination');
            $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->pagination;
    }
}
