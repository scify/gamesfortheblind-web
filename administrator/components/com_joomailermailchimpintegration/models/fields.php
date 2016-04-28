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

class joomailermailchimpintegrationModelFields extends jmModel {

    public function __construct($config = array()) {
        parent::__construct($config);
        jimport('joomla.filesystem.file');
        jimport('joomla.application.component.helper');
    }

    public function getData() {
        $listid	= JRequest::getVar('listid', 0, '', 'string');
        return $this->getMcObject()->listMergeVars($listid);
    }

    public function getCBfields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/comprofiler.xml')) {
            return false;
        }
        $cHelper = JComponentHelper::getComponent('com_comprofiler', true);
        if (!$cHelper->enabled) {
            return false;
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->qn('#__comprofiler_fields'))
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' IN (' . implode(',', $db->q(array(
                'predefined',
                'checkbox',
                //'multicheckbox',
                'select',
                //'multiselect',
                'radio',
                'text',
                'textarea',
                'datetime',
                'date'
            ))) . ')');
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        return ($fields) ? $fields : false;
    }

    public function getJSfields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_community/community.xml')) {
            return false;
        }
        $cHelper = JComponentHelper::getComponent('com_community', true);
        if (!$cHelper->enabled) {
            return false;
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from($db->qn('#__community_fields'))
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' IN (' . implode(',', $db->q(array(
                'country',
                'select',
                'singleselect',
                'radio',
                'date',
                'textarea',
                'text'
            ))) . ')');
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        return ($fields) ? $fields : false;
    }

    public function getVMfields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/virtuemart.xml')) {
            return false;
        }
        $cHelper = JComponentHelper::getComponent('com_virtuemart', true);
        if (!$cHelper->enabled) {
            return false;
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn(array('virtuemart_userfield_id', 'name'), array('id', 'name')))
            ->from($db->qn('#__virtuemart_userfields'))
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('registration') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' NOT IN (' . implode(',', $db->q(array(
                'delimiter',
                'password',
                'multiselect',
                'checkbox',
                'multicheckbox',
                'textarea',
                'text'
            ))) . ')')
            ->order($db->qn('ordering'));
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        return ($fields) ? $fields : false;
    }
}
