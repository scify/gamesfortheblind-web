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

class joomailermailchimpintegrationControllerGroups extends joomailermailchimpintegrationController {

    public function __construct($config = array()) {
        parent::__construct($config);
        $this->registerTask('add' , 'edit');
    }

    public function edit() {
        JRequest::setVar('view', 'groups');
        JRequest::setVar('layout', 'form' );
        JRequest::setVar('hidemainmenu', 1);
        parent::display();
    }

    public function save() {
        $db	= JFactory::getDBO();

        $action = JRequest::getVar('action', 'add', 'post', 'string');
        $fieldId = JRequest::getVar('fieldId', '', 'post', 'string');
        $groupingId = JRequest::getVar('$groupingId', '', 'post', 'string');
        $listid = JRequest::getVar('listid',0, 'post', 'string');
        $name = ($action == 'add')?JRequest::getVar('name',  0, 'post', 'string', JREQUEST_ALLOWRAW):JRequest::getVar('nameOld',  0, 'post', 'string', JREQUEST_ALLOWRAW);

        if ($action != 'add') {
            throw new Exception('Updating groups not implemented');
        }

        $coreType = JRequest::getVar('coreType', 0, 'post', 'string');
        $CBfield  = JRequest::getVar('CBfield', 0, 'post', 'string', JREQUEST_ALLOWRAW);
        $JSfield  = JRequest::getVar('JSfield', 0, 'post', 'string', JREQUEST_ALLOWRAW);
        $VMfield  = JRequest::getVar('VMfield', 0, 'post', 'string', JREQUEST_ALLOWRAW);

        if ($coreType) {
            $type = $coreType;
            $framework = 'core';
            $db_field  = '';
        } else if ($CBfield) {
            $framework = 'CB';
            $CBfield = explode('|*|', $CBfield);
            $db_field = $CBfield[0];
            $db_id = $CBfield[1];
        } else if ($JSfield) {
            $framework = 'JS';
            $db_field = $JSfield;
        } else if ($VMfield) {
            $framework = 'VM';
        }

        // get options
        if ($CBfield || $JSfield) {
            $options = '';
        }
        if ($framework=='CB') {
            $query = $db->getQuery(true);
            $query->select($db->qn('type'))
                ->from('#__comprofiler_fields')
                ->where($db->qn('fieldid') . ' = ' . $db->q($db_id));
            $db->setQuery($query);
            $fieldType = $db->loadResult();

            if ($fieldType == 'select' || $fieldType == 'singleselect') {
                $type = 'dropdown';
            } else if ($fieldType == 'checkbox' || $fieldType == 'multicheckbox' || $fieldType == 'multiselect'){
                $type = 'checkboxes';
            } else if ($fieldType != 'radio'){
                $type = 'hidden';
            } else {
                $type = $fieldType;
            }

            $query = $db->getQuery(true);
            $query->select($db->qn('fieldtitle', 'options'))
                ->from('#__comprofiler_field_values')
                ->where($db->qn('fieldid') . ' = ' . $db->q($db_id));
            $db->setQuery($query);
            $fieldData = $db->loadObjectList();
        } else if ($framework == 'JS') {
            $query = $db->getQuery(true);
            $query->select($db->qn(array('type', 'options')))
                ->from('#__community_fields')
                ->where($db->qn('id') . ' = ' . $db->q($db_field));
            $db->setQuery($query);
            $fieldData = $db->loadObjectList();
        } else if ($framework=='VM') {
            $query = $db->getQuery(true);
            $query->select('*')
                ->from('#__vm_userfield_values')
                ->where($db->qn('fieldid') . ' = ' . $db->q($VMfield));
            $db->setQuery($query);
            $fieldData = $db->loadObjectList();

            $query = $db->getQuery(true);
            $query->select($db->qn(array('name', 'type')))
                ->from('#__vm_userfield')
                ->where($db->qn('fieldid') . ' = ' . $db->q($VMfield));
            $db->setQuery($query);
            $fieldInfo = $db->loadObjectList();
            $db_field  = $fieldInfo[0]->name;
            $fieldType = $fieldInfo[0]->type;

            if ($fieldType == 'select' || $fieldType == 'singleselect') {
                $type = 'dropdown';
            } else if ($fieldType == 'checkbox' || $fieldType == 'multicheckbox' || $fieldType == 'multiselect') {
                $type = 'checkboxes';
            } else if ($fieldType != 'radio') {
                $type = 'hidden';
            } else {
                $type = $fieldType;
            }
        }

        if ($framework == 'core') {
            $options = explode("\n", JRequest::getVar('coreOptions', '', 'post', 'string'));
            for ($i = 0; $i < count($options); $i++) {
                $options[$i] = trim($options[$i]);
            }
            $options = array_values(array_filter($options));
        } else if ($framework == 'CB') {
            if ($fieldType == 'checkbox') {
                $options[] = 'Yes';
            } else if ($fieldType == 'text' || $fieldType == 'textarea') {
                $options[] = '';
            } else {
                foreach ($fieldData as $o) {
                    $options[] = $o->options;
                }
            }
        } else if ($framework == 'JS') {
            foreach ($fieldData as $o) {
                $options = explode("\n", $o->options);
                $type = $o->type;
                if ($type == 'select' || $type == 'singleselect' || $type == 'country') {
                    $type = 'dropdown';
                } else if ($type == 'checkbox' || $type == 'multicheckbox' || $type == 'multiselect') {
                    $type = 'checkboxes';
                } else if ($type != 'radio') {
                    $type = 'hidden';
                }
            }
        } else if ($framework == 'VM') {
            foreach ($fieldData as $o) {
                $options[] = $o->fieldvalue;
            }
        }

        if (count($options) > 60) {
            $msg = JText::_('JM_TOO_MANY_OPTIONS');
            $msgType = 'error';
        } else {
            // create custom field using MC API
            if ($action == 'add') {
                $result = $this->getModel('groups')->getMcObject()->listInterestGroupingAdd($listid, $name, $type, $options);
            } else {
                $result = $this->getModel('groups')->getMcObject()->listInterestGroupingUpdate($groupingId, $name, $options);
            }
            if (!$this->getModel('groups')->getMcObject()->errorCode) {
                $groupingID = $result;
            }

            if (!$this->getModel('groups')->getMcObject()->errorCode) {
                // store field association in J! db
                if ($framework != 'core') {
                    if ($action == 'add') {
                        $query = $db->getQuery(true);
                        $query->insert('#__joomailermailchimpintegration_custom_fields')
                            ->set($db->qn('listID') . ' = ' . $db->q($listid))
                            ->set($db->qn('name') . ' = ' . $db->q($name))
                            ->set($db->qn('framework') . ' = ' . $db->q($framework))
                            ->set($db->qn('dbfield') . ' = ' . $db->q($db_field))
                            ->set($db->qn('grouping_id') . ' = ' . $db->q($groupingID))
                            ->set($db->qn('type') . ' = ' . $db->q('group'));
                    } else {
                        $query = $db->getQuery(true);
                        $query->update('#__joomailermailchimpintegration_custom_fields')
                            ->set($db->qn('listID') . ' = ' . $db->q($listid))
                            ->set($db->qn('name') . ' = ' . $db->q($name))
                            ->set($db->qn('framework') . ' = ' . $db->q($framework))
                            ->set($db->qn('dbfield') . ' = ' . $db->q($db_field))
                            ->set($db->qn('type') . ' = ' . $db->q('group'))
                            ->where($db->qn('grouping_id') . ' = ' . $db->q($groupingID));
                    }
                    $db->setQuery($query);
                    try {
                        $db->execute();
                        $msg = JText::_('JM_CUSTOM_FIELD_CREATED');
                        $msgType = 'message';
                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                        $msgType = 'error';
                    }
                }
            } else {
                $msg = MCerrorHandler::getErrorMsg($this->getModel('groups')->getMcObject());
                $msgType = 'error';
            }
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=groups&listid=' . $listid;
        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    public function remove() {
        $db	= JFactory::getDBO();
        $db->transactionStart();
        $listid = JRequest::getVar('listid',  0, '', 'string');
        $listName = JRequest::getVar('listName',  0, '', 'string');
        $cid = JRequest::getVar('cid',  0, '', 'array');

        foreach ($cid as $id) {
            try {
                $this->getModel('groups')->getMcObject()->listInterestGroupingDel($id);
                if ($this->getModel('groups')->getMcObject()->errorCode) {
                    throw new Exception (MCerrorHandler::getErrorMsg($this->getModel('groups')->getMcObject()));
                }

                // remove field association from J! db
                $query = $db->getQuery(true);
                $query->delete('#__joomailermailchimpintegration_custom_fields')
                    ->where($db->qn('grouping_id') . ' = ' . $db->q($id));
                $db->setQuery($query);
                $db->execute();

                $msg = JText::_('JM_CUSTOM_FIELD_DELETED');
                $msgType = 'message';
            } catch (Exception $e) {
                $db->transactionRollback();
                $msg = $e->getMessage();
                $msgType = 'error';
                break;
            }
        }

        $db->transactionCommit();

        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=groups&listid=' . $listid . '&name=' . $listName);
    }

    public function cancel() {
        $listid = JRequest::getVar('listid',  0, '', 'string');
        $listName = JRequest::getVar('listName',  0, '', 'string');
        $this->app->enqueueMessage(JText::_('JM_OPERATION_CANCELLED'), 'notice');
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=groups&listid=' . $listid . '&name=' . $listName);
    }

    function goToLists(){
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=lists');
    }
}
