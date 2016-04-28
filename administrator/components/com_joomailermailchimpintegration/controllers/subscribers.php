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

class joomailermailchimpintegrationControllerSubscribers extends joomailermailchimpintegrationController {

    public function __construct() {
        parent::__construct();
    }

    public function unsubscribe() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi  = $params->get('params.MCapi');
        $MC = new joomlamailerMCAPI($MCapi);

        $emails = JRequest::getVar('emails', array(), 'post', 'array');
        $listId = JRequest::getVar('listid', 0, 'post', 'string');

        $i=0;
        if (isset($emails[0]) && $listId) {
            foreach ($emails as $email) {
                $unsubscribe = $MC->listUnsubscribe($listId, $email, false, false, false);
                if (!$MC->errorCode) $i++;
            }
        }

        if ($MC->errorCode) {
            $msg = MCerrorHandler::getErrorMsg($MC);
        } else {
            $msg = $i . ' ' . JText::_('JM_USER_UNSUBSCRIBED');
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=subscribers&type=s&listid=' . $listId;
        $this->setRedirect($link, $msg);
    }

    public function delete() {
        $db = JFactory::getDBO();
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi  = $params->get('params.MCapi');
        $MC = new joomlamailerMCAPI($MCapi);

        $emails = JRequest::getVar('emails', array(), 'post', 'array');
        $listId = JRequest::getVar('listid', 0, 'post', 'string');

        $deleted = array();
        if (isset($emails[0]) && $listId) {
            foreach ($emails as $email) {
                $unsubscribe = $MC->listUnsubscribe($listId, $email, true, false, false);
                if (!$MC->errorCode) {
                    $deleted[] = $email;
                }
            }
        }

        if ($MC->errorCode) {
            $msg = MCerrorHandler::getErrorMsg($MC);
        } else {
            $query = $db->getQuery(true)
                ->delete('#__joomailermailchimpintegration')
                ->where($db->qn('listid') . ' = ' . $db->q($listId))
                ->where($db->qn('email') . ' IN ("' . implode('","', $deleted) . '")');
            $db->setQuery($query);
            $db->execute();

            // clean cache
            $this->getModel('main')->emptyCache('joomlamailerMisc');

            $msg = count($deleted) . ' ' . JText::_('JM_USER_DELETED');
        }

        $link = 'index.php?option=com_joomailermailchimpintegration&view=subscribers&type=s&listid=' . $listId;
        $this->setRedirect($link, $msg);
    }

    public function resubscribe() {
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi  = $params->get('params.MCapi');
        $MC = new joomlamailerMCAPI($MCapi);

        $listId = JRequest::getVar('listid', 0, 'post', 'string');
        $emails = JRequest::getVar('emails', array(), 'post', 'array');

        $i = 0;
        if (isset($emails[0]) && $listId) {
            foreach ($emails as $email) {
                $memberInfo = $MC->listMemberInfo($listId, $email);
                $resubscribe = $MC->listSubscribe($listId, $email, $memberInfo, $memberInfo['email_type'], false, true, false, false );
                if (!$MC->errorCode) $i++;
            }
        }

        if ($MC->errorCode) {
            $msg = MCerrorHandler::getErrorMsg($MC);
        } else {
            $msg = $i . ' ' . JText::_('JM_USER_RESUBSCRIBED');
        }

        $this->setRedirect('index.php?option=com_joomailermailchimpintegration&view=lists', $msg);
    }

    public function cancel() {
        $this->setRedirect('index.php?option=com_joomailermailchimpintegration&view=templates', JText::_('JM_OPERATION_CANCELLED'));
    }

    public function goToLists() {
        $this->setRedirect('index.php?option=com_joomailermailchimpintegration&view=lists');
    }
}
