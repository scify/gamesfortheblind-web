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

class joomailermailchimpintegrationControllerFields extends joomailermailchimpintegrationController {

    public function __construct() {
        parent::__construct();
        $this->registerTask('add', 'edit');
    }

    public function edit() {
        JRequest::setVar('view', 'fields');
        JRequest::setVar('layout', 'form');
        JRequest::setVar('hidemainmenu', 1);

        parent::display();
    }

    public function save() {
        $db	= JFactory::getDBO();
        $action = JRequest::getVar('action', 'add', 'post', 'string');
        $listid = JRequest::getVar('listid',0, 'post', 'string');

        $name = JRequest::getVar('name', 'Untitled', 'post', 'string', JREQUEST_ALLOWRAW);
        $field_type = JRequest::getVar('field_type', 0, 'post', 'string');
        $options['req'] = JRequest::getVar('req', 0, 'post', 'string');
        $newtag = JRequest::getVar('tag', '', 'post', 'string');
        $oldtag = JRequest::getVar('oldtag', '', 'post', 'string');

        $tag = ($newtag != $oldtag) ? $newtag : $oldtag;
        $tag = strtoupper($tag);
        $options['tag'] = $tag;

        $CBfield = JRequest::getVar('CBfield', 0, 'post', 'string', JREQUEST_ALLOWRAW);
        $JSfield = JRequest::getVar('JSfield', 0, 'post', 'string', JREQUEST_ALLOWRAW);
        $VMfield = JRequest::getVar('VMfield', 0, 'post', 'string', JREQUEST_ALLOWRAW);

        if ($field_type && $action == 'add') {
            $type = $field_type;
            $framework = 'core';
            $db_field = '';
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
        } else {
            $framework = 'core';
        }

        // get options
        if ($CBfield || $JSfield) {
            $options['choices'] = '';
        }
        if ($framework == 'CB') {
            $query = $db->getQuery(true)
                ->select($db->qn('type'))
                ->from($db->qn('#__comprofiler_fields'))
                ->where($db->qn('fieldid') . ' = ' . $db->q($db_id));
            $db->setQuery($query);
            $fieldType = $db->loadResult();

            if ($fieldType == 'predefined') {
                $type = 'text';
            } else if ($fieldType == 'select' || $fieldType == 'singleselect') {
                $type = 'dropdown';
            } else if ($fieldType == 'checkbox' || $fieldType == 'multicheckbox' || $fieldType == 'multiselect') {
                $type = 'checkboxes';
            } else if ($fieldType != 'radio') {
                $type = 'hidden';
            } else {
                $type = $fieldType;
            }

            $query = $db->getQuery(true)
                ->select($db->qn('fieldtitle', 'options'))
                ->from($db->qn('#__comprofiler_field_values'))
                ->where($db->qn('fieldid') . ' = ' . $db->q($db_id));
            $db->setQuery($query);
            $fieldData = $db->loadObjectList();
        } else if ($framework == 'JS') {
            $query = $db->getQuery(true)
                ->select($db->qn(array('type', 'options')))
                ->from($db->qn('#__community_fields'))
                ->where($db->qn('id') . ' = ' . $db->q($db_field));
            $db->setQuery($query);
            $fieldData = $db->loadObjectList();
        } else if ($framework == 'VM'){
            $query = $db->getQuery(true)
                ->select($db->qn(array('name', 'type')))
                ->from($db->qn('#__virtuemart_userfields'))
                ->where($db->qn('virtuemart_userfield_id') . ' = ' . $db->q($VMfield));
            $db->setQuery($query);
            $fieldInfo = $db->loadObjectList();
            $db_field = $fieldInfo[0]->name;
            if ($db_field == 'title' || $db_field == 'country' || $db_field == 'state') {
                $fieldType = 'text';
            } else {
                $fieldType = $fieldInfo[0]->type;
            }

            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->qn('#__virtuemart_userfield_values'))
                ->where($db->qn('virtuemart_userfield_id') . ' = ' . $db->q($VMfield));
            $db->setQuery($query);
            $fieldData = $db->loadObjectList();
        }

        if ($framework == 'core') {
            $options['choices'] = explode("\n", JRequest::getVar('coreOptions',  '', 'post', 'string'));
            for ($i = 0; $i < count($options['choices']); $i++) {
                $options['choices'][$i] = trim($options['choices'][$i]);
            }
            //$options = array_values(array_filter($options['choices']));

        } else if ($framework == 'CB') {
            if ($fieldType == 'checkbox') {
                $options['choices'][] = '1';
                $field_type = 'radio';
            } else if ($fieldType == 'text' || $fieldType == 'textarea') {
                $options['choices'][] = '';
                $field_type = 'text';
            } else if ($fieldType == 'datetime' || $fieldType == 'date') {
                $field_type = 'date';
            } else {
                foreach ($fieldData as $o) {
                    $options['choices'][] = $o->options;
                }
                $field_type = 'dropdown';
            }
        } else if ($framework == 'JS') {
            foreach ($fieldData as $o) {
                $options['choices'] = explode("\n", $o->options);
                if ($o->type == 'select' || $o->type == 'singleselect' || $o->type == 'country') {
                    $o->type = 'dropdown';
                } else if ($o->type == 'text' || $o->type == 'textarea') {
                    $o->type = 'text';
                } else if ($o->type != 'radio' && $o->type != 'date'){
                    $o->type = 'hidden';
                }
                $field_type = $o->type;
            }
        } else if ($framework == 'VM') {
            if ($fieldType == 'checkbox') {
                $options['choices'][] = JText::_('JM_NO');
                $options['choices'][] = JText::_('JM_YES');
                $field_type = 'dropdown';
            } else if (in_array($fieldType, array('text', 'textarea', 'euvatid', 'editorta'))) {
                $options['choices'][] = '';
                $field_type = 'text';
            } else if ($fieldType == 'webaddress') {
                $field_type = 'url';
            } else  if ($fieldType == 'age_verification') {
                $field_type = 'date';
            } else {
                foreach ($fieldData as $o) {
                    $options['choices'][] = $o->fieldvalue;
                }
                if ($fieldType == 'radio') {
                    $field_type = 'radio';
                } else {
                    $field_type = 'dropdown';
                }
            }
        }

        if ($action == 'add'){
            $options['field_type'] = $field_type;
            $this->getModel('fields')->getMcObject()->listMergeVarAdd($listid, $tag, $name, $options);
        } else {
            $options['name'] = $name;
            $this->getModel('fields')->getMcObject()->listMergeVarUpdate($listid, $oldtag, $options);
        }

        if (!$this->getModel('fields')->getMcObject()->errorCode) {
            if ($framework != 'core') {
                try {
                    //Check to see if field associations are stored locally
                    $query = $db->getQuery(true)
                        ->select($db->qn('id'))
                        ->from($db->qn('#__joomailermailchimpintegration_custom_fields'))
                        ->where($db->qn('grouping_id') . ' = ' . $db->q($tag));
                    $db->setQuery($query);
                    $cfid = $db->loadResult();
                    // store field association in J! db
                    if ($action == 'add' || !$cfid) {
                        $query = $db->getQuery(true)
                            ->insert($db->qn('#__joomailermailchimpintegration_custom_fields'))
                            ->set($db->qn('listID') . ' = ' . $db->q($listid))
                            ->set($db->qn('name') . ' = ' . $db->q($name))
                            ->set($db->qn('framework') . ' = ' . $db->q($framework))
                            ->set($db->qn('dbfield') . ' = ' . $db->q($db_field))
                            ->set($db->qn('grouping_id') . ' = ' . $db->q($tag))
                            ->set($db->qn('type') . ' = ' . $db->q('field'));
                    } else {
                        $query = $db->getQuery(true)
                            ->update($db->qn('#__joomailermailchimpintegration_custom_fields'))
                            ->set($db->qn('listID') . ' = ' . $db->q($listid))
                            ->set($db->qn('name') . ' = ' . $db->q($name))
                            ->set($db->qn('framework') . ' = ' . $db->q($framework))
                            ->set($db->qn('dbfield') . ' = ' . $db->q($db_field))
                            ->set($db->qn('type') . ' = ' . $db->q('field'))
                            ->where($db->qn('grouping_id') . ' = ' . $db->q($tag));
                    }
                    $db->setQuery($query);

                    $db->execute();
                    $msg = ($action == 'add') ? JText::_('JM_MERGE_FIELD_CREATED') : JText::_('JM_MERGE_FIELD_UPDATED');
                    $msgType = 'message';
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    $msgType = 'error';

                    // remove field again from MC as we couldn't store it in the J! db
                    $this->getModel('fields')->getMcObject()->listMergeVarDel($listid, $tag);
                }
            }
        } else {
            $msg = MCerrorHandler::getErrorMsg($this->getModel('fields')->getMcObject());
            $msgType = 'error';
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=fields&listid=' . $listid;
        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    public function remove() {
        $db	= JFactory::getDBO();
        $listid = JRequest::getVar('listid', 0, '', 'string');
        $cid = JRequest::getVar('cid', 0, '', 'array');

        foreach ($cid as $id) {
            $attribs = explode(';', $id);
            $tag = $attribs[1];

            $this->getModel('fields')->getMcObject()->listMergeVarDel($listid, $tag);

            if ($this->getModel('fields')->getMcObject()->errorCode) {
                $msg = MCerrorHandler::getErrorMsg($this->getModel('fields')->getMcObject());
                $msgType = 'error';
                break;
            } else {
                // remove field association from J! db
                $query = $db->getQuery(true)
                    ->delete($db->qn('#__joomailermailchimpintegration_custom_fields'))
                    ->where($db->qn('grouping_id') . ' = ' . $db->q($tag));
                $db->setQuery($query);
                try {
                    $db->execute();
                    $msg = JText::_('JM_MERGE_FIELDS_DELETED');
                    $msgType = 'message';
                } catch (Exception $e) {
                    $msg = $e->getMessage();
                    $msgType = 'error';
                    break;
                }
            }
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=fields&listid=' . $listid;
        $this->app->enqueueMessage($msg, $msgType);
        $this->app->redirect($link);
    }

    public function cancel() {
        $listid = JRequest::getVar('listid', 0, '', 'string');
        $this->app->enqueueMessage(JText::_('JM_OPERATION_CANCELLED'), 'notice');
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=fields&listid=' . $listid);
    }

    function goToLists() {
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=lists');
    }
}
