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

class joomailermailchimpintegrationController extends jmController {

    public function display($cachable = false, $urlparams = false) {
            parent::display($cachable, $urlparams);
    }

    public function edit() {
        JRequest::setVar('layout', 'form');
        parent::display();
    }

    public function save() {
        // check for request forgeries
        JRequest::checkToken() or jexit('JINVALID_TOKEN');

        $user = JFactory::getUser();
        if (!$user->id) {
            $uri = JFactory::getURI();
            $this->app->enqueueMessage(JText::_('JM_ONLY_LOGGED_IN_USERS_CAN_VIEW_SUBSCRIPTIONS'), 'error');
            $this->app->redirect('index.php?option=com_users&view=login&return=' . base64_encode($uri->toString()));
        }

        $itemid = JRequest::getVar('itemid', '', 'post', 'string');
        $itemid = ($itemid) ? '&Itemid=' . $itemid : '';
        $redirectLink = 'index.php?option=com_joomailermailchimpintegration&view=subscriptions' . $itemid;

        $lists = JRequest::getVar('lists', array(), 'post', 'array');
        $isSub = JRequest::getVar('isSub', array(), 'post', 'array');

        if (!count($lists) || !count($isSub)) {
            $this->app->enqueueMessage(JText::_('JGLOBAL_VALIDATION_FORM_FAILED'), 'error');
            $this->app->redirect($redirectLink . '&task=edit');
        }

        $mergeVars = array();
        $names = explode(' ', $user->name);
        if (count($names) > 1) {
            $mergeVars['FNAME'] = $names[0];
            unset($names[0]);
            $mergeVars['LNAME'] = implode(' ', $names);
        } else {
            $mergeVars['FNAME'] = $user->name;
        }

        foreach ($lists as $listId => $subscribe) {
            if ($isSub[$listId] == $subscribe) {
                continue;
            }

            if ($subscribe) {
                $result = $this->getModel('subscriptions')->getMcObject()->listSubscribe($listId, $user->email, $mergeVars, '', false, true, false, false);
                $this->dbInsert($user->id, $user->email, $listId);
            } else {
                $result = $this->getModel('subscriptions')->getMcObject()->listUnsubscribe($listId, $user->email, false, false, false);
                $this->dbDelete($user->email, $listId);
            }
        }

        $this->app->enqueueMessage(JText::_('JM_SUBSCRIPTIONS_UPDATED'));
        $this->app->redirect($redirectLink);
    }

    private function dbInsert($id, $email, $listId) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->insert('#__joomailermailchimpintegration')
            ->set($db->qn('userid') . ' = ' . $db->q($id))
            ->set($db->qn('email') . ' = ' . $db->q($email))
            ->set($db->qn('listid') . ' = ' . $db->q($listId));
        $db->setQuery($query);
        $db->query();
    }

    private function dbDelete($email, $listId) {
        $db = JFactory::getDBO();
        $query = $db->getQuery(true)
            ->delete('#__joomailermailchimpintegration')
            ->where($db->qn('email') . ' = ' . $db->q($email))
            ->where($db->qn('listid') . ' = ' . $db->q($listId));
        $db->setQuery($query);
        $db->query();
    }

    public function cancel() {
        $itemid = JRequest::getVar('itemid', '', 'post', 'string');
        $itemid = ($itemid) ? '&Itemid=' . $itemid : '';
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=subscriptions' . $itemid);
    }

    public function signup() {
        $response = array();

        if (!JSession::checkToken()) {
            $response['html'] = 'Invalid Token';
            $response['error'] = true;
            echo json_encode($response);
            exit;
        }

        require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        if (!$MCapi) {
            $response['html'] = 'No MailChimp API key';
            $response['error'] = true;
            echo json_encode($response);
            exit;
        }
        $MC = new joomlamailerMCAPI($MCapi);

        // set Itemid so we can retrieve the correct module parameters below
        $jinput = JFactory::getApplication()->input;
        $jinput->set('Itemid', JRequest::getVar('itemId', '', 'post', 'int'));

        jimport('joomla.application.module.helper');
        $module = JModuleHelper::getModule('mod_mailchimpsignup', JRequest::getVar('title', '', 'post', 'string'));
        $params = new JRegistry();
        $params->loadString($module->params);
        $listId = $params->get('listid');

        // make sure SIGNUPAPI field exists to record signup date
        $this->checkSignupApiField($MC, $listId);

        $user = JFactory::getUser();
        $userId = $user->id;
        $fields = JRequest::getVar('fields', array(), 'post', 'array');

        $email = $fields['EMAIL'];
        unset($fields['EMAIL']);

        $mergeVars = array();
        foreach ($fields as $fieldName => $field) {
            if (is_array($field)) {
                foreach ($field as $key => $value) {
                    if ($key == 'phone') {
                        $mergeVars[$fieldName] = implode('-', $value);
                        break;
                    } else {
                        $mergeVars[$fieldName][$key] = $value;
                    }
                }
            } else {
                if ($fieldName == 'FNAME' && !isset($fields['LNAME']) && strpos($field, ' ') !== false) {
                    $tmp = explode(' ', $field);
                    $field = $tmp[0];
                    unset($tmp[0]);
                    $mergeVars['LNAME'] = implode(' ', $tmp);
                }
                $mergeVars[$fieldName] = $field;
            }
        }

        $interests = JRequest::getVar('interests', array(), 'post', 'array');
        foreach ($interests as $id => $values) {
            $values = array_filter($values);
            if (count($values)) {
                $values = implode(',', str_replace(',', '\,', $values));
                $mergeVars['GROUPINGS'][] = array(
                    'id' => $id,
                    'groups' => $values
                );
            }
        }

        $userLists = $MC->listsForEmail($email);
        if ($userLists && in_array($listId, $userLists)) {
            $update = true;
        } else {
            $update = false;
            // add signup date (new subscriber)
            $mergeVars['SIGNUPAPI'] = date('Y-m-d');
            $mergeVars['OPTINIP'] = JRequest::getVar('ip', '', 'post', 'string');
        }

        // if we did not collect any merge vars we have to submit an empty string rather than an empty array
        if (!count($mergeVars)) {
            $mergeVars = '';
        }

        // email type
        $emailType = JRequest::getVar('email_type', 'html', 'post', 'string');

        // use double option for guests only
        $doubleOptin = $sendWelcome = ($update || ($user->id && $user->email == $email)) ? false : true;

        // submit to API
        $MC->listSubscribe($listId, $email, $mergeVars, $emailType, $doubleOptin, true, true, $sendWelcome);

        if ($MC->errorCode) {
            $response['html'] = $MC->errorMessage;
            $response['error'] = true;
        } else {
            $db = JFactory::getDBO();
            $query = $db->getQuery(true)
                ->select($db->qn('userid'))
                ->from($db->qn('#__joomailermailchimpintegration'))
                ->where($db->qn('email') . ' = ' . $db->q($email))
                ->where($db->qn('listid') . ' = ' . $db->q($listId));
            $db->setQuery($query);
            $userIdSubscribed = $db->loadResult();

            if ($userIdSubscribed === null) {
                $query = $db->getQuery(true)
                    ->insert($db->qn('#__joomailermailchimpintegration'))
                    ->set($db->qn('userid') . ' = ' . $db->q($user->id))
                    ->set($db->qn('email') . ' = ' . $db->q($email))
                    ->set($db->qn('listid') . ' = ' . $db->q($listId));
                $db->setQuery($query);
                $db->Query();
            }

            $response['html'] = ($update) ? $params->get('updateMsg') : $params->get('thankyou');
            $response['error'] = false;
        }

        echo json_encode($response);
    }

    private function checkSignupApiField($MC, $listId) {
        // create hidden signup date mergevar if it doesn't exist
        $cacheGroup = 'mod_mailchimpsignup';
        $cacheID = 'SIGNUPAPI_' . $listId;
        jimport('joomla.cache.cache');
        $cacheOptions = array();
        $cacheOptions['lifetime'] = 525949;
        $cacheOptions['defaultgroup'] = $cacheGroup;

        $cacheOptions['caching'] = true;
        $cache = new JCache($cacheOptions);

        if (!$cache->get($cacheID, $cacheGroup)) {
            $createSignupdateMerge = true;
            $listMergeVars = $MC->listMergeVars($listId);
            foreach ($listMergeVars as $lmv) {
                if ($lmv['tag'] == 'SIGNUPAPI') {
                    $createSignupdateMerge = false;
                    break;
                }
            }
            if ($createSignupdateMerge) {
                $MC->listMergeVarAdd($listId, 'SIGNUPAPI', 'date added (API)', array(
                    'field_type' => 'date',
                    'req' => false,
                    'public' => false,
                    'show' => true
                ));
            }
            $cache->store(true, $cacheID, $cacheGroup);
        }
    }
}
