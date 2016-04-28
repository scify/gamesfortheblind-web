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

class joomailermailchimpintegrationModelGroups extends jmModel {

    public function __construct() {
        jimport('joomla.filesystem.file');
        parent::__construct();
    }

    public function getData() {
        $listid	= JRequest::getVar('listid', 0, '', 'string');
        return $this->getMcObject()->listInterestGroupings($listid);
    }

    public function getCBfields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_comprofiler/comprofiler.xml')) {
            return false;
        }
        jimport('joomla.application.component.helper');
        $cHelper = JComponentHelper::getComponent('com_comprofiler', true);
        if (!$cHelper->enabled) {
            return false;
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__comprofiler_fields')
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' IN ('
                . $db->q('checkbox') . ','
                . $db->q('multicheckbox') . ','
                . $db->q('select') . ','
                . $db->q('multiselect') . ','
                . $db->q('radio') . ')');
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        return ($fields) ? $fields : false;
    }

    public function getJSfields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_community/community.xml')) {
            return false;
        }
        jimport('joomla.application.component.helper');
        $cHelper = JComponentHelper::getComponent('com_community', true);
        if (!$cHelper->enabled) {
            return false;
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('*')
            ->from('#__community_fields')
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' IN ('
                . $db->q('checkbox') . ','
                . $db->q('list') . ','
                . $db->q('select') . ','
                . $db->q('multiselect') . ','
                . $db->q('radio') . ')');
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        return ($fields) ? $fields : false;
    }

    public function getVMfields() {
        if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_virtuemart/virtuemart.xml')) {
            return false;
        }

        jimport('joomla.application.component.helper');
        $cHelper = JComponentHelper::getComponent('com_virtuemart', true);
        if (!$cHelper->enabled){
            return false;
        }

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn(array('virtuemart_userfield_id', 'name'), array('id', 'name')))
            ->from('#__virtuemart_userfields')
            ->where($db->qn('published') . ' = ' . $db->q(1))
            ->where($db->qn('registration') . ' = ' . $db->q(1))
            ->where($db->qn('type') . ' NOT IN ('
                . $db->q('delimiter') . ','
                . $db->q('password') . ','
                . $db->q('emailaddress') . ','
                . $db->q('text') . ','
                . $db->q('euvatid') . ','
                . $db->q('editorta') . ','
                . $db->q('textarea') . ','
                . $db->q('webaddress') . ','
                . $db->q('age_verification') . ')')
            ->oder('ordering');
        $db->setQuery($query);
        $fields = $db->loadObjectList();

        return ($fields) ? $fields : false;
    }

    public function store() {
        return true;
    }

}
