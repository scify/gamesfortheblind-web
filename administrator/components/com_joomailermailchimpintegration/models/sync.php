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

class joomailermailchimpintegrationModelSync extends jmModel {

    private $data;
    private $total = null;
    private $pagination = null;

    public function __construct() {
        parent::__construct();

        $mainframe = JFactory::getApplication();
        $option = JRequest::getCmd('option');

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
        $db	= JFactory::getDBO();
        $filter_type = $mainframe->getUserStateFromRequest('filter_type', 'filter_type', 0, 'string');
        $search	= JString::strtolower($mainframe->getUserStateFromRequest('search', 'search', '', 'string'));
        $filter_date = $mainframe->getUserStateFromRequest('filter_date', 'filter_date', '', 'string');
        if ($filter_date == JText::_('Last visit after')) {
            $filter_date = false;
        }

        $limit		= $mainframe->getUserStateFromRequest('global.list.limit', 'limit', $mainframe->getCfg('list_limit'), 'int');
        $limitstart	= $mainframe->getUserStateFromRequest('limitstart', 'limitstart', 0, 'int');

        if (isset($search) && $search != '') {
            $searchEscaped = '"%' . $db->escape($search, true) . '%"';
            $where[] = ' username LIKE ' . $searchEscaped . ' OR email LIKE ' . $searchEscaped . ' OR name LIKE ' . $searchEscaped;
        }

        if ($filter_type > 1) {
            $where[] = ' um.group_id = '.$db->Quote($filter_type).' ';
        }

        $where[] = "block = '0'";

        $where = (count($where) ? ' WHERE (' . implode(') AND (', $where) . ')' : '');

        if ($filter_date && $filter_date != JText::_('JM_LAST_VISIT_AFTER')) {
            $where .= " AND lastvisitDate >= '" . $filter_date . "' ";
        }

        $query =  'SELECT a.*, ug.title AS groupname'
            . ' FROM #__users AS a'
            . ' INNER JOIN #__user_usergroup_map AS um ON um.user_id = a.id'
            . ' INNER JOIN #__usergroups AS ug ON ug.id = um.group_id'
            . $where
            . ' ORDER BY a.id';

        return $query;
    }

    public function getData() {
        if (empty($this->data)) {
            $query = $this->buildQuery();
            $this->data = $this->_getList($query, $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->data;
    }

    public function getUser($id) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn(array('u.id', 'u.name', 'u.username', 'u.email', 'u.block', 'g.title'),
            array('id', 'name', 'username', 'email', 'block', 'usergroup')))
            ->from($db->qn('#__users', 'u'))
            ->join('LEFT', '#__user_usergroup_map AS m ON (u.id = m.user_id)')
            ->join('LEFT', '#__usergroups AS g ON (g.id = m.group_id)')
            ->where($db->qn('u.id') . ' = ' . $db->q($id));

        return $this->_getList($query);
    }

    public function getUserByEmail($email) {
        $query = 'SELECT id,name,username,email,block,usertype FROM #__users WHERE email ="' . $email . '"';
        $this->data = $this->_getList($query);

        if (!isset($this->data[0])) {
            $cm = $this->cm_object();
            $lists = $this->getLists();
            foreach($lists['anyType']['List'] as $listID){
                if (isset($result) && isset($result['anyType']['Code']) && $result['anyType']['Code'] != 203) {
                    break;
                }

                $result = $cm->subscriberGetSingleSubscriber($listID['ListID'], $email);

                if (isset($result['anyType']['Name'])) {
                    $this->data[0] = new stdClass;
                    $this->data[0]->name = $result['anyType']['Name'];
                    $this->data[0]->email = $result['anyType']['EmailAddress'];
                }
            }
        }

        return $this->data;
    }

    public function getTotalUsers() {
        $db = JFactory::getDBO();
        $query = 'SELECT COUNT(id) FROM #__users WHERE block = 0';
        $db->setQuery($query);

        return $db->loadResult();
    }

    public function getTotal() {
        if (empty($this->total)) {
            $query = $this->buildQuery();
            $this->total = $this->_getListCount($query);
        }

        return $this->total;
    }

    public function getPagination() {
        if (empty($this->pagination)) {
            jimport('joomla.html.pagination');
            $this->pagination = new JPagination($this->getTotal(), $this->getState('limitstart'), $this->getState('limit'));
        }

        return $this->pagination;
    }

    public function getGroups() {
        require_once(JPATH_ADMINISTRATOR . '/components/com_users/helpers/users.php');

        return UsersHelper::getGroups();
    }

    public function getConfig($crm) {
        $db	= JFactory::getDBO();
        $query = $db->getQUery(true);
        $query->select('params')
            ->from($db->qn('#__joomailermailchimpintegration_crm'))
            ->where($db->qn('crm') . ' = ' . $db->q($crm));
        $db->setQuery($query);

        return json_decode($db->loadResult());
    }

    public function getJSFields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_community/admin.community.php')) {
            return array();
        }
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->qn('#__community_fields'))
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' != ' . $db->q('group'));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getCBFields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/admin.comprofiler.php')) {
            return array();
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select(array('fieldid', 'name', 'title'), array('id', 'name', 'title'))
            ->from($db->qn('#__comprofiler_fields'))
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('profile') . ' = ' . $db->q(1))
            ->where($db->qn('readonly') . ' = ' . $db->q(0))
            ->where($db->qn('calculated') . ' = ' . $db->q(0));
        $db->setQuery($query);

        return $db->loadObjectList();
    }

    public function getSugarFields() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $sugar_name = $params->get('params.sugar_name');
        $sugar_pwd  = $params->get('params.sugar_pwd');
        $sugar_url  = $params->get('params.sugar_url');

        require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/sugar.php');
        $sugar = new SugarCRMWebServices;
        $sugar->SugarCRM($sugar_name, $sugar_pwd, $sugar_url);
        $sugar->login();

        $fields = $sugar->getModuleFields('Contacts');

        $disallowedFields = array(
            'id',
            'date_entered',
            'date_modified',
            'modified_user_id',
            'modified_by_name',
            'created_by',
            'created_by_name',
            'deleted',
            'assigned_user_id',
            'assigned_user_name',
            'email1'
        );

        for ($x = 0; $x < count($fields); $x++) {
            if (in_array($fields[$x]['name'], $disallowedFields)){
                unset($fields[$x]);
            }
        }

        return $fields;
    }

    public function buildFieldsDropdown($name, $JS, $CB, $config, $email = false) {
        $html = '<select name="crmFields[' . $name . ']" id="' . $name . '" style="min-width: 200px;">';
        $html .= '<option value="">do not sync</option>';

        if ($email) {
            $selected = (isset($config->{$name}) && $config->{$name} == 'default') ? ' selected="selected"' : '';
            $html .= '<option value="default"' . $selected . '>Joomla User Account Email</option>';
        }

        if ($JS) {
            $html .= '<optgroup label="JomSocial">';
            foreach ($JS as $field) {
                $selected = (isset($config->{$name}) && $config->{$name} == 'js;' . $field->id) ? ' selected="selected"' : '';
                $html .= '<option value="js;' . $field->id . '"' . $selected . '>' . $field->name . '</option>';
            }
            $html .= '</optgroup>';
        }
        if ($CB){
            $html .= '<optgroup label="Community Builder">';
            foreach ($CB as $field) {
                $selected = (isset($config->{$name}) && $config->{$name} == 'cb;'.$field->name) ? ' selected="selected"' : '';
                $html .= '<option value="cb;' . $field->name . '"' . $selected . '>' . $field->title . '</option>';
            }
            $html .= '</optgroup>';
        }

        $html .= '</select>';

        return $html;
    }

    public function getCRMusers() {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn(array('crm', 'user_id')))
            ->from($db->qn('#__joomailermailchimpintegration_crm_users'));
        $db->setQuery($query);
        $data = $db->loadObjectList();

        if (count($data)) {
            foreach ($data as $d) {
                $result[$d->crm][] = $d->user_id;
            }

            return $result;
        } else {
            return false;
        }
    }

}
