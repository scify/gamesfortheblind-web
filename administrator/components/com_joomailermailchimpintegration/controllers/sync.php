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

class joomailermailchimpintegrationControllerSync extends joomailermailchimpintegrationController {

    public function __construct() {
        parent::__construct();

        // Register Extra tasks
        $this->registerTask('add' , 'sync');
        $this->registerTask('backup' , 'sync');
    }

    public function sugar() {
        JRequest::setVar('view', 'sync');
        JRequest::setVar('layout', 'sugar' );
        JRequest::setVar('hidemainmenu', 0);
        parent::display();
    }

    public function highrise() {
        JRequest::setVar('view', 'sync');
        JRequest::setVar('layout', 'highrise' );
        JRequest::setVar('hidemainmenu', 0);
        parent::display();
    }

    public function sync()	{
        $db = JFactory::getDBO();
        $mainframe = JFactory::getApplication();

        $listId = JRequest::getVar('listId',  false, '', 'string');
        if (!$listId) {
            $this->app->enqueueMessage(JText::_('JM_INVALID_LISTID'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
        }

        //total number of elements to process
        $elements = JRequest::getVar('boxchecked', 0, '', 'int');

        if (!$elements) {
            $this->app->enqueueMessage(JText::_('JM_NO_USERS_SELECTED'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
        }

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi = $params->get('params.MCapi');
        $MC = new joomlamailerMCAPI($MCapi);
        $MCerrorHandler = new MCerrorHandler();

        $query = $db->getQuery(true)
            ->select($db->qn('userid'))
            ->from($db->qn('#__joomailermailchimpintegration'))
            ->where($db->qn('listid') . ' = ' . $db->q($listId));
        $db->setQuery($query);
        $exclude = $db->loadColumn();
        if ($exclude == null) {
            $exclude = array();
        }

        // gather custom fields data
        $query = $db->getQuery(true)
            ->select('*')
            ->from($db->qn('#__joomailermailchimpintegration_custom_fields'))
            ->where($db->qn('listid') . ' = ' . $db->q($listId));
        $db->setQuery($query);
        $customFields = $db->loadObjectList();

        if (!isset($customFields[0])) {
            $customFields = false;
        }

        $data = JRequest::getVar('cid', 0, '', 'array');
        if (!$data) {
            $data = JRequest::getVar('cid[]', 0, '', 'array');
        }

        $batch = $failed = array();

        $i = $k = $errorcount = 0;
        $msg = $error_msg = false;
        foreach ($data as $dat) {
            if (in_array($dat, $exclude)) {
                continue;
            }
            $i++;
            $user = $this->getModel('sync')->getUser($dat);
            $userIds[$user[0]->email] = $user[0]->id;
            $batch[$k]['EMAIL'] = $user[0]->email;
            // name
            $names = explode(' ', $user[0]->name);
            if ($names[0] && isset($names[1])) {
                $batch[$k]['FNAME'] = $names[0];
                for ($i = 1; $i < count($names); $i++) {
                    $batch[$k]['LNAME'] .= $names[$i] . ' ';
                }
            } else {
                $batch[$k]['FNAME'] = $user[0]->name;
            }

            $custom = array();

            if ($customFields) {
                foreach ($customFields as $field) {
                    $query = $db->getQuery(true);

                    if ($field->framework == 'CB') {
                        $query->select($db->qn($field->dbfield))
                            ->from($db->qn('#__comprofiler'))
                            ->where($db->qn('user_id') . ' = ' . $db->q($user[0]->id));
                    } else if ($field->framework =='JS') {
                        $query->select($db->qn('value'))
                            ->from($db->qn('#__community_fields_values'))
                            ->where($db->qn('field_id') . ' = ' . $db->q($field->dbfield))
                            ->where($db->qn('user_id') . ' = ' . $db->q($user[0]->id));
                    }
                    $db->setQuery($query);
                    $fieldValue = $db->loadResult();
                    if ($field->framework == 'CB') {
                        $fieldValue = str_replace('|*|', ',', $fieldValue);
                    }
                    if ($field->framework == 'JS') {
                        $fieldValue = (substr($fieldValue, strlen($fieldValue) - 1) == ',') ?
                            $fieldValue = substr($fieldValue, 0, -1) : $fieldValue;
                        if ($fieldValue==NULL) {
                            $fieldValue = '';
                        }
                    }
                    if ($field->type == 'group') {
                        $batch[$k]['GROUPINGS'][] = array('id' => $field->grouping_id, 'groups' => $fieldValue);
                    } else {
                        $batch[$k][$field->grouping_id] = $fieldValue;
                    }
                }
            }

            $query = $db->getQuery(true)
                ->insert($db->qn('#__joomailermailchimpintegration'))
                ->set($db->qn('userid') . ' = ' . $db->q($user[0]->id))
                ->set($db->qn('email') . ' = ' . $db->q($user[0]->email))
                ->set($db->qn('listid') . ' = ' . $db->q($listId));
            $db->setQuery($query);
            $db->execute();

            $k++;
        }

        if (count($batch)) {
            $optin = false; // do not send optin emails
            $updateExisting = true; // yes, update currently subscribed users
            $replaceInterests = true; // false = add interest, don't replace

            $result = $MC->listBatchSubscribe($listId, $batch, $optin, $updateExisting, $replaceInterests);

            $msg = $result['success_count'] . ' ' . JText::_('JM_RECIPIENTS_SAVED');
            //$resubscribeLink = '';

            if ($result['error_count']) {
                //		var_dump($result['errors']);die;
                foreach ($result['errors'] as $e) {
                    //			$errorMsg .= '"'.$e['message'].'", ';
                    $tmp = new stdClass();
                    $tmp->errorCode = $e['code'];
                    $tmp->errorMessage = $e['message'];
                    $errorMsg .= '"' . $MCerrorHandler->getErrorMsg($tmp) . ' => ' . $e['row']['EMAIL'] . '", ';
                    /*if ($tmp->errorCode == 212){  // => do not allow admins to resubscribe unsubscribed users to prevent spam complaints
                        $resubscribeLink = ' <a href="index.php?option=com_joomailermailchimpintegration&view=subscribers&listid='.$listId.'&type=u">'.
                        JText::_('JM_RESUBSCRIBE_LINK').'</a>';
                    }*/
                    $failed[] = $e['row']['EMAIL'];
                }
                $errorMsg = substr($errorMsg, 0, -2);
                $msg .= ' (' . $result['error_count'] . ' ' . JText::_('Errors') . ': ' . $errorMsg . ')';
                //$msg .= $resubscribeLink;
            }

            // clean cache
            $this->getModel('main')->emptyCache('joomlamailerMisc');
        } else {
            $msg = JText::_('JM_ALL_USERS_ALREADY_ADDED', true);
        }

        foreach ($failed as $fail) {
            $query = $db->getQuery(true);
            $query->delete($db->qn('#__joomailermailchimpintegration'))
                ->where($db->qn('listis') . ' = ' . $db->q($listId))
                ->where($db->qn('email') . ' = ' . $db->q($fail));
            $db->setQuery($query);
            $db->execute();
        }

        $this->app->enqueueMessage($msg);
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
    }

    function sync_all() {
        $mainframe = JFactory::getApplication();
        $db = JFactory::getDBO();
        JHTMLBehavior::keepalive();

        $listId = JRequest::getVar('listid',  0, '', 'string');
/////
        $query = 'SELECT userid FROM #__joomailermailchimpintegration WHERE listid = "' . $listId . '"';
        $db->setQuery($query);
        $exclude = $db->loadResultArray();
        $exclude = implode('","', $exclude);
        $exclude = '"' . $exclude . '"';

        $query = 'SELECT id FROM #__users WHERE id NOT IN (' . $exclude . ') and block = 0';
        $db->setQuery($query);
        $data = $db->loadObjectList();
        $elements = count($data);

        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $MCapi  = $params->get('params.MCapi');
        $MC = new MCAPI($MCapi);
        $MCerrorHandler = new MCerrorHandler();

        // gather custom fields data
        $db	= JFactory::getDBO();
        $query = "SELECT * FROM #__joomailermailchimpintegration_custom_fields WHERE listid = '" . $listId . "' ";
        $db->setQuery($query);
        $customFields = $db->loadObjectList();
        if (!isset($customFields[0])) $customFields = false;

        $m = 0;
        $successCount = $errorcount = $msgErrorsCount = 0;
        $msg = $msgErrors = false;

        $step = 100;
        //	foreach ($data as $dat){
        for ($x=0;$x<count($data);$x+=$step){
            $k=0;
            $batch = array();
            for ($y=$x;$y<($x+$step);$y++){

                $dat = $data[$y];
                if ($dat){
                    $user = $this->getModel('sync')->getUser($dat->id);
                    $batch[$k]['EMAIL'] = $user[0]->email;
                    // name
                    $names = explode(' ', $user[0]->name);
                    if ($names[0] && $names[1]) {
                        $batch[$k]['FNAME'] = $names[0];
                        for($i=1;$i<count($names);$i++){
                            $batch[$k]['LNAME'] .= $names[$i].' ';
                        }
                    } else {
                        $batch[$k]['FNAME'] = $user[0]->name;
                    }

                    $custom = array();

                    if ($customFields){
                        foreach($customFields as $field){
                            if ($field->framework == 'CB') {
                                $query = "SELECT ".$field->dbfield." FROM #__comprofiler WHERE user_id = '".$user[0]->id."' ";
                            } else {
                                $query = "SELECT value FROM #__community_fields_values WHERE field_id = ".$field->dbfield." AND user_id = '".$user[0]->id."' ";
                            }
                            $db->setQuery($query);
                            $fieldValue = $db->loadResult();
                            if ($field->framework == 'CB') $fieldValue = str_replace('|*|', ',', $fieldValue);
                            if ($field->framework == 'JS') {
                                $fieldValue = (substr($fieldValue, strlen($fieldValue) - 1)==',')? $fieldValue = substr($fieldValue,0,-1):$fieldValue;
                                if ($fieldValue==NULL) $fieldValue = '';
                            }
                            $batch[$k]['GROUPINGS'][] = array('id' => (int)$field->grouping_id, 'groups' => $fieldValue);
                        }
                    }

                    $query = 'INSERT INTO #__joomailermailchimpintegration (userid,email,listid) VALUES ("'.$user[0]->id.'", "'.$user[0]->email.'", "'.$listId.'")';
                    $db->setQuery($query);
                    $db->execute();
                    $k++;
                } else {
                    break;
                }
            }

            if ($batch){
                $optin = false; // do not send optin emails
                $updateExisting = true; // yes, update currently subscribed users
                $replaceInterests = true; // false = add interest, don't replace

                $result = $MC->listBatchSubscribe($listId, $batch, $optin, $updateExisting, $replaceInterests);

                $successCount = $successCount + $result['success_count'];

                if ($result['error_count']) {
                    foreach ($result['errors'] as $e) {
                        $tmp = new stdClass();
                        $tmp->errorCode = $e['code'];
                        $tmp->errorMessage = $e['message'];
                        $errorMsg .= '"' . $MCerrorHandler->getErrorMsg($tmp) . '", ';

                        $query->getQuery(true);
                        $query->delete($db->qn('#__joomailermailchimpintegration'))
                            ->where($db->qn('listid') . ' = ' . $db->q($listId))
                            ->where($db->qn('email') . ' = ' . $db->q($e['row']['EMAIL']));
                        $db->setQuery($query, 0, 1);
                        $db->execute();
                    }
                    $msgErrorsCount += $result['error_count'];
                }
            }
        }

        if ($errorMsg) {
            $msgErrors = substr($errorMsg, 0, -2);
            $msgErrors = ' (' . $msgErrorsCount . ' ' . JText::_('JM_ERRORS') . ': ' . $msgErrors . ')';
        }
        $msg = $successCount . ' ' . JText::_('JM_RECIPIENTS_SAVED') . $msgErrors;

        $this->app->enqueueMessage($msg);
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
    }

    public function ajaxSyncAll() {
        $done = JRequest::getVar('done', '', 'POST', 'int');

        if ($done == 0) {
            $_SESSION['abortAJAX'] = 0;
            unset($_SESSION['addedUsers']);
        }

        if ($_SESSION['abortAJAX'] != 1) {
            $db = JFactory::getDBO();
            $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
            $MCapi = $params->get('params.MCapi');
            $MC = new joomlamailerMCAPI($MCapi);
            $MCerrorHandler = new MCerrorHandler();

            $listId = JRequest::getVar('listId', '', 'POST', 'string');
            $total = JRequest::getVar('total', 0, 'POST', 'int');
            $errors = JRequest::getVar('errors', 0, 'POST', 'int');
            $step = JRequest::getVar('step', 100, 'POST', 'int');
            $failed = JRequest::getVar('failed', array(), 'POST');
            $addedUsers = JRequest::getVar('addedUsers', array(), 'POST');
            $errorMsg = JRequest::getVar('errorMsg', '', 'POST', 'string');

            if (!$addedUsers) {
                $addedUsers = array();
            }
            // create hidden signup date merge var if it doesn't exist
            $createSignupdateMerge = true;
            $mergeVars = $MC->listMergeVars($listId);
            foreach ($mergeVars as $mv) {
                if ($mv['tag'] == 'SIGNUPAPI') {
                    $createSignupdateMerge = false;
                    break;
                }
            }
            if ($createSignupdateMerge) {
                $MC->listMergeVarAdd($listId, 'SIGNUPAPI', 'date added (API)', array('date', false, false, true));
            }

            if (isset($_SESSION['addedUsers'])) {
                $exclude = $_SESSION['addedUsers'];
            } else {
                $exclude = array();
            }

            if (!$failed) {
                $failed = array();
            }
            if (count($failed)) {
                $exclude = array_merge($exclude, $failed);
            }

            $query = $db->getQuery(true)
                ->select($db->qn(array('id', 'email')))
                ->from($db->qn('#__users'))
                ->where($db->qn('block') . ' = ' . $db->q(0))
                ->where($db->qn('id') . ' NOT IN ("' . implode('","', $exclude) . '")');
            $db->setQuery($query, 0, $step);
            $users = $db->loadObjectList();

            $userIds = array();
            foreach ($users as $user) {
                $userIds[$user->email] = $user->id;
            }

            // gather custom fields data
            $query = $db->getQuery(true)
                ->select('*')
                ->from($db->qn('#__joomailermailchimpintegration_custom_fields'))
                ->where($db->qn('listid') . ' = ' . $db->q($listId));
            $db->setQuery($query);
            $customFields = $db->loadObjectList();

            $m = $successCount = $errorcount = $msgErrorsCount = $counter = 0;
            $msg = $msgErrors = false;
            $ids = '';
            for ($x = 0; $x < count($users); $x += $step) {
                if ($_SESSION['abortAJAX'] == 1) {
                    unset($_SESSION['addedUsers']);
                    break;
                }
                $k = $errorcount = $msgErrorsCount = 0;
                $batch = array();

                for ($y = $x; $y < ($x + $step); $y++) {
                    if ($_SESSION['abortAJAX'] == 1) {
                        unset($_SESSION['addedUsers']);
                        break;
                    }

                    $dat = (isset($users[$y])) ? $users[$y] : false;

                    if ($dat) {
                        $user = $this->getModel('sync')->getUser($dat->id);
                        $addedUsers[] = $user[0]->id;
                        $batch[$k]['EMAIL'] = $user[0]->email;
                        // name
                        $names = explode(' ', $user[0]->name);
                        if (isset($names[0]) && isset($names[1])) {
                            $batch[$k]['FNAME'] = $names[0];
                            $batch[$k]['LNAME'] = '';
                            for ($i = 1; $i < count($names); $i++) {
                                $batch[$k]['LNAME'] .= $names[$i] . ' ';
                            }
                        } else {
                            $batch[$k]['FNAME'] = $user[0]->name;
                        }

                        if (count($customFields)) {
                            foreach ($customFields as $field) {
                                $query = $db->getQuery(true);
                                if ($field->framework == 'CB') {
                                    $query->select($db->qn($field->dbfield))
                                        ->from($db->qn('#__comprofiler'))
                                        ->where($db->qn('user_id') . ' = ' . $db->q($user[0]->id));
                                } else {
                                    $query->select($db->qn('value'))
                                        ->from($db->qn('#__community_fields_values'))
                                        ->where($db->qn('field_id') . ' = ' . $db->q($field->dbfield))
                                        ->where($db->qn('user_id') . ' = ' . $db->q($user[0]->id));
                                }
                                $db->setQuery($query);
                                $fieldValue = $db->loadResult();
                                if ($field->framework == 'CB') {
                                    $fieldValue = str_replace('|*|', ',', $fieldValue);
                                }
                                if ($field->framework == 'JS') {
                                    if ($fieldValue == NULL) {
                                        $fieldValue = '';
                                    } else {
                                        $fieldValue = (substr($fieldValue, strlen($fieldValue) - 1) == ',') ?
                                            $fieldValue = substr($fieldValue, 0, -1) : $fieldValue;
                                    }
                                }
                                if ($field->type == 'group') {
                                    $batch[$k]['GROUPINGS'][] = array('id' => $field->grouping_id, 'groups' => $fieldValue);
                                } else {
                                    $batch[$k][$field->grouping_id] = $fieldValue;
                                }
                            }
                        }

                        // add signup date
                        $batch[$k]['SIGNUPAPI'] = date('Y-m-d');

                        $query = $db->getQuery(true);
                        try {
                            $query->insert($db->qn('#__joomailermailchimpintegration'))
                                ->set($db->qn('userid') . ' = ' . $db->q($user[0]->id))
                                ->set($db->qn('email') . ' = ' . $db->q($user[0]->email))
                                ->set($db->qn('listid') . ' = ' . $db->q($listId));
                            $db->setQuery($query);
                            $db->execute();
                        } catch(Exception $e) {}
                        $k++;
                    } else {
                        break;
                    }
                }
                if ($batch) {
                    $optin = false; // do not send optin emails
                    $updateExisting = true; // yes, update currently subscribed users
                    $replaceInterests = true; // false = add interest, don't replace

                    $result = $MC->listBatchSubscribe($listId, $batch, $optin, $updateExisting, $replaceInterests);

                    $successCount = $successCount + $result['success_count'];

                    if ($result['error_count']) {
                        foreach ($result['errors'] as $e) {
                            $tmp = new stdClass();
                            $tmp->errorCode = $e['code'];
                            $tmp->errorMessage = $e['message'];
                            $errorMsg .= '"' . $MCerrorHandler->getErrorMsg($tmp) . ' => ' . $e['row']['EMAIL'] . '", ';

                            $query->getQuery(true);
                            $query->delete($db->qn('#__joomailermailchimpintegration'))
                                ->where($db->qn('listid') . ' = ' . $db->q($listId))
                                ->where($db->qn('email') . ' = ' . $db->q($e['row']['EMAIL']));
                            $db->setQuery($query, 0, 1);
                            $db->execute();

                            $addedUsers = array_diff($addedUsers, array($userIds[$e['row']['EMAIL']]));

                            $failed[] = $userIds[$e['row']['EMAIL']];
                            $errorcount++;
                        }
                        $msgErrorsCount += $result['error_count'];
                    }
                }
            }

            if (!count($users)) {
                $done = $total;
                unset($_SESSION['addedUsers']);
                $percent = 100;
            } else {
                $done = count($addedUsers);
                $_SESSION['addedUsers'] = $addedUsers;
                $percent = ($done / $total) * 100;
            }

            $response['msg'] = '<div id="bg"></div>' .
                '<div id="progressBarContainer">' .
                    '<div id="progressBarTitle">' . JText::_('JM_ADDING_USERS') . ' (' . $done . '/' . $total . ' ' . JText::_('JM_DONE') . ')</div>' .
                    '<div id="progressBarBg">' .
                        '<div id="progressBarCompleted" style="width: ' . round($percent) . '%;"></div>' .
                        '<div id="progressBarNumber">' . round($percent) . ' %</div>' .
                    '</div>' .
                    '<a id="sbox-btn-close" href="javascript:joomlamailerJS.sync.abortAJAX();">abort</a>' .
                '</div>';

            $response['done'] = $done;

            //	$msg = $successCount.' '.JText::_('JM_RECIPIENTS_SAVED').$msgErrors;

            $response['errors']	= count($failed);
            $response['errorMsg']	= $errorMsg;
            $response['addedUsers']	= array_values($addedUsers);
            $response['failed']	= $failed;

            if (($done + count($failed) + $errors) >= $total){
                $response['finished'] = 1;

                if ($errorMsg) {
                    $errorMsg  = substr($errorMsg, 0, -2);
                    $msgErrors = ' (' . count($failed) . ' ' . JText::_('JM_ERRORS') . ': ' . $errorMsg . ')';
                }
                if (!$msg) {
                    $msg = $done . ' ' . JText::_('JM_RECIPIENTS_SAVED');
                }
                if ($msgErrors) {
                    $msg .= $msgErrors;
                }
                $response['finalMessage'] = $msg;
            } else {
                $response['finished'] = 0;
                $response['finalMessage'] = '';
            }
            $response['abortAJAX'] = $_SESSION['abortAJAX'];
            echo json_encode($response);
        } else {
            unset($_SESSION['addedUsers']);
            $response['finished'] = 1;
            $response['addedUsers'] = '';
            $response['abortAJAX'] = $_SESSION['abortAJAX'];
            echo json_encode($response);
        }
    }

    public function abortAJAX() {
        $_SESSION['abortAJAX'] = 1;
        echo json_encode(array(
            'finalMessage' => JText::_('JM_OPERATION_CANCELLED')
        ));
    }

    public function getTotal() {
        $listId = JRequest::getVar('listId');

        $db = JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select('COUNT(' . $db->qn('id') . ')')
            ->from($db->qn('#__users'))
            ->where($db->qn('block') . ' = ' . $db->q(0));
        $db->setQuery($query);

        echo $db->loadResult();
    }

    public function getListSubscribers() {
        $listId = JRequest::getVar('listId', '', 'POST', 'string');
        $db	= JFactory::getDBO();
        $query = $db->getQuery(true);
        $query->select($db->qn('userid'))
            ->from($db->qn('#__joomailermailchimpintegration'))
            ->where($db->qn('listid') . ' = ' . $db->q($listId))
            ->group('userid');
        $db->setQuery($query);

        echo json_encode($db->loadAssocList());
    }

    public function setConfig() {
        $crm = JRequest::getVar('crm');

        $crmFields = JRequest::getVar('crmFields');
        $params = json_encode($crmFields);

        $db	= JFactory::getDBO();
        $query = "DELETE FROM #__joomailermailchimpintegration_crm WHERE crm = '$crm'";
        $db->setQuery($query);
        $db->execute();
        $query = "INSERT INTO #__joomailermailchimpintegration_crm (crm, params) VALUES ('$crm', '".$params."')";
        $db->setQuery($query);
        $db->execute();

        $msg = JText::_('JM_CONFIGURATION_SAVED');
        $this->app->enqueueMessage($msg);
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
    }

    function sync_highrise()
    {
        $db	=& JFactory::getDBO();
        $params =& JComponentHelper::getParams('com_joomailermailchimpintegration');
        $highrise_url = $params->get('params.highrise_url');
        $highrise_api_token = $params->get('params.highrise_api_token');

        $config = $this->getModel('sync')->getConfig('highrise');

        if ($config == NULL){
            jimport('joomla.application.component.helper');
            $cHelper = JComponentHelper::getComponent('com_comprofiler', false);
            $cbInstalled = $cHelper->enabled;

            $config = new stdClass();
            $config->{'first-name'} = ($cbInstalled) ? 'CB' : 'core';
            $config->email_work = 'default';
        }

        $validator = new EmailAddressValidator;

        $elements = JRequest::getVar('elements', '', 'request', 'string');
        $elements = json_decode($elements);
        if ($elements->done == 0) {
            $_SESSION['abortAJAX'] = 0;
            unset($_SESSION['addedUsers']);
        }

        $failed = $elements->errors;
        $errorMsg = $elements->errorMsg;
        $step = $elements->step;

        if ($_SESSION['abortAJAX'] != 1){

            if (isset($_SESSION['addedUsers'])){
                $exclude = $_SESSION['addedUsers'];
            } else {
                $exclude = array();
            }

            $addedUsers = $exclude;
            if (isset($exclude[0])){
                $exclude = implode('","', $exclude);
                $exclude = '"'.$exclude.'"';
                $excludeCond = 'AND id NOT IN ('.$exclude.') ';
            } else {
                $excludeCond = '';
            }

            if ($elements->range == 'all'){
                $query = 'SELECT * FROM #__users '
                .'WHERE block = 0 '
                .$excludeCond
                .'ORDER BY id '
                .'LIMIT '.$step;
            } else {
                $idList = implode(" OR id = ", $elements->cid);
                $query = 'SELECT * FROM #__users '
                .'WHERE block = 0 '
                .$excludeCond
                .'AND (id = '.$idList.') '
                .'ORDER BY id ';
            }
            $db->setQuery($query);
            $users = $db->loadObjectList();

            $queryJS = false;
            $queryCB = false;
            $JSand = array();
            foreach($config as $k => $v){
                if ($k != 'first-name' && $k != 'last-name'){
                    $vEx = explode(';', $v);
                    if ($vEx[0] == 'js') {
                        $queryJS = true;
                        $JSand[] = $vEx[1];
                    } else if ($vEx[0] == 'CB') {
                        $queryCB = true;
                    }
                }
            }
            $JSand = implode("','", array_unique($JSand));

            require_once(JPATH_ADMINISTRATOR.'/components/com_joomailermailchimpintegration/libraries/push2Highrise.php');
            $highrise = new Push_Highrise($highrise_url, $highrise_api_token);

            $data = array();
            $emails = array();
            $x = 0;
            $new = $elements->new;
            $updated = $elements->updated;
            $userIDs = array();
            foreach($users as $user){
                if ($validator->check_email_address($user->email)){
                    $request = array();
                    $userCB = false;

                    $names = explode(' ', $user->name);
                    $firstname = $names[0];
                    $lastname = '';
                    if (isset($names[1])){
                        for($i=1;$i<count($names);$i++){
                            $lastname .= $names[$i].' ';
                        }
                    }
                    $lastname = trim($lastname);

                    if ($config->{'first-name'} != 'core') {
                        $query = "SELECT * FROM #__comprofiler WHERE user_id = '$user->id'";
                        $db->setQuery($query);
                        $userCB = $db->loadObjectList();

                        $firstname = ($userCB[0]->firstname) ? $userCB[0]->firstname : $firstname;
                        $lastname  = ($userCB[0]->lastname) ? $userCB[0]->lastname : $lastname;
                        if ($userCB[0]->middlename != ''){
                            $lastname = $userCB[0]->middlename.' '.$lastname;
                        }
                    }

                    $highriseUser = $highrise->person_in_highrise(array('first-name' => $firstname, 'last-name' => $lastname));
                    $request['id'] = $highriseUser->id;
                    //	    var_dump($highriseUser);die;

                    if ($queryJS){
                        $query = "SELECT field_id, value FROM #__community_fields_values ".
                        "WHERE user_id = '$user->id' ".
                        "AND field_id IN ('$JSand')";
                        $db->setQuery($query);
                        $JSfields = $db->loadObjectList();
                        $JSfieldsArray = array();
                        foreach($JSfields as $jsf){
                            $JSfieldsArray[$jsf->field_id] = $jsf->value;
                        }
                    }

                    if ($queryCB){
                        if (!$userCB){
                            $query = "SELECT * FROM #__comprofiler WHERE user_id = '$user->id'";
                            $db->setQuery($query);
                            $userCB = $db->loadObjectList();
                        }
                    }

                    $xml =  "<person>\n";

                    if ((int)$highriseUser->id > 0){
                        $xml .= '<id>'.$highriseUser->id."</id>\n";
                    }

                    $xml .=  "<first-name>".htmlspecialchars($firstname)."</first-name>\n"
                    ."<last-name>".htmlspecialchars($lastname)."</last-name>";


                    if (isset($config->title) && $config->title != ''){
                        $conf = explode(';', $config->title);
                        $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                        $xml .= "\n<title>".htmlspecialchars($value)."</title>";
                    }
                    if (isset($config->background) && $config->background != ''){
                        $conf = explode(';', $config->background);
                        $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                        $xml .= "\n<background>".htmlspecialchars($value)."</background>";
                    }
                    if (isset($config->company) && $config->company != ''){
                        $conf = explode(';', $config->company);
                        $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                        $xml .= "\n<company-name>".htmlspecialchars($value).'</company-name>';
                    }


                    $xml .= "\n<contact-data>";
                    $xml .= "\n<email-addresses>";

                    $emailTypes = array('work', 'home', 'other');
                    foreach ($emailTypes as $et){

                        if (isset($config->{'email_'.$et}) && $config->{'email_'.$et} != ''){
                            if ($config->{'email_'.$et} == 'default'){
                                $value = $user->email;
                            } else {
                                $conf = explode(';', $config->{'email_'.$et});
                                $value = ($conf[0] == 'js') ?  $JSfieldsArray[$conf[1]] : $userCB[0]->{$conf[1]};
                            }

                            $fieldId = '';
                            if (isset($highriseUser->{'contact-data'}->{'email-addresses'}->{'email-address'})){
                                foreach($highriseUser->{'contact-data'}->{'email-addresses'} as $hu){
                                    foreach($hu->{'email-address'} as $ea){
                                        if ($ea->location == ucfirst($et)){
                                            $fieldId = '<id type="integer">'.$ea->id[0]."</id>\n";
                                            break;
                                        }
                                    }
                                }
                            }
                            $xml .= "\n<email-address>\n"
                            .$fieldId
                            ."<address>".htmlspecialchars($value)."</address>\n"
                            ."<location>".ucfirst($et)."</location>\n"
                            ."</email-address>";
                        }


                    }

                    $xml .= "\n</email-addresses>\n";

                    $xml .= "\n<phone-numbers>\n";
                    $phoneTypes = array('work','mobile','fax','pager','home','skype','other');
                    foreach($phoneTypes as $pt){
                        if ($config->{'phone_'.$pt} != NULL && $config->{'phone_'.$pt} != ''){
                            $conf = explode(';', $config->{'phone_'.$pt});
                            $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');

                            $fieldId = '';
                            if (isset($highriseUser->{'contact-data'}->{'phone-numbers'}->{'phone-number'})){
                                foreach($highriseUser->{'contact-data'}->{'phone-numbers'} as $hu){
                                    foreach($hu->{'phone-number'} as $pn){
                                        if ($pn->location == ucfirst($pt)){
                                            $fieldId = '<id type="integer">'.$pn->id[0]."</id>\n";
                                            break;
                                        }
                                    }
                                }
                            }
                            $xml .= "<phone-number>\n"
                            .$fieldId
                            ."<number>".htmlspecialchars($value)."</number>\n"
                            ."<location>".ucfirst($pt)."</location>\n"
                            ."</phone-number>";
                        }
                    }
                    $xml .= "\n</phone-numbers>\n";

                    $xml .= "\n<instant-messengers>\n";
                    $imTypes = array('AIM','MSN','ICQ','Jabber','Yahoo','Skype','QQ','Sametime','Gadu-Gadu','Google Talk','Other');
                    foreach($imTypes as $im){
                        if (isset($config->{$im}) && $config->{$im} != ''){
                            $value = false;
                            if ($config->{$im} == 'default'){
                                $value = $user->email;
                            } else if ($config->{$im} != ''){
                                $conf = explode(';', $config->{$im});
                                $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                            }
                            if ($value){
                                $fieldId = '';
                                if (isset($highriseUser->{'contact-data'}->{'instant-messengers'}->{'instant-messenger'})){
                                    foreach($highriseUser->{'contact-data'}->{'instant-messengers'} as $imx){
                                        foreach($imx->{'instant-messenger'} as $ia){
                                            if ($ia->protocol == $im){
                                                $fieldId = '<id type="integer">'.$ia->id[0]."</id>\n";
                                                break;
                                            }
                                        }
                                    }
                                }
                                $xml .= "<instant-messenger>\n"
                                .$fieldId
                                ."<address>".htmlspecialchars($value)."</address>\n"
                                ."<location>Work</location>\n"
                                ."<protocol>".$im."</protocol>\n"
                                ."</instant-messenger>";
                            }
                        }
                    }
                    $xml .= "\n</instant-messengers>\n";

                    if (isset($config->website) && $config->website != ''){
                        $xml .= "\n<web-addresses>\n";
                        $conf = explode(';', $config->website);
                        $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');

                        $fieldId = '';
                        if (isset($highriseUser->{'contact-data'}->{'web-addresses'}->{'web-address'})){
                            foreach($highriseUser->{'contact-data'}->{'web-addresses'} as $ws){
                                foreach($ws->{'web-address'} as $wa){
                                    if ($wa->location == 'Work'){
                                        $fieldId = '<id type="integer">'.$wa->id[0]."</id>\n";
                                        break;
                                    }
                                }
                            }
                        }
                        $xml .= "<web-address>\n"
                        .$fieldId
                        ."<url>".htmlspecialchars($value)."</url>\n"
                        ."<location>Work</location>\n"
                        ."</web-address>";
                        $xml .= "\n</web-addresses>\n";
                    }

                    if (isset($config->twitter) && $config->twitter != ''){
                        $xml .= "\n<twitter-accounts>\n";
                        $conf = explode(';', $config->twitter);
                        $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                        $value = removeSpecialCharacters($value);
                        $fieldId = '';
                        if (isset($highriseUser->{'contact-data'}->{'twitter-accounts'}->{'twitter-account'})){
                            foreach($highriseUser->{'contact-data'}->{'twitter-accounts'} as $tac){
                                foreach($tac->{'twitter-account'} as $ta){
                                    if ($ta->location == 'Personal'){
                                        $fieldId = '<id type="integer">'.$ta->id[0]."</id>\n";
                                        break;
                                    }
                                }
                            }
                        }
                        $xml .= "<twitter-account>\n"
                        .$fieldId
                        ."<username>".htmlspecialchars(str_replace(' ','',$value))."</username>\n"
                        ."<location>Personal</location>\n"
                        ."</twitter-account>";
                        $xml .= "\n</twitter-accounts>\n";
                    }

                    if (   (isset($config->street) && $config->street != '')
                        || (isset($config->city)   && $config->city != ''  )
                        || (isset($config->zip)    && $config->zip != ''   )
                        || (isset($config->state)  && $config->state != '' )
                        || (isset($config->country)&& $config->country != '')
                    ){
                        $xml .= "\n<addresses>\n";
                        $xml .= "<address>\n";

                        $fieldId = '';
                        if (isset($highriseUser->{'contact-data'}->addresses->address)){
                            foreach($highriseUser->{'contact-data'}->addresses as $ads){
                                foreach($ads->address as $ad){
                                    if ($ad->location == 'Work'){
                                        $fieldId = '<id type="integer">'.$ad->id[0]."</id>\n";
                                        break;
                                    }
                                }
                            }
                        }
                        $xml .= $fieldId;

                        if (isset($config->street) && $config->street != '') {
                            $conf = explode(';', $config->street);
                            $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                            $xml .= "<street>".htmlspecialchars($value)."</street>\n";
                        }
                        if (isset($config->city)   && $config->city != '') {
                            $conf = explode(';', $config->city);
                            $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                            $xml .= "<city>".htmlspecialchars($value)."</city>\n";
                        }
                        if (isset($config->zip)    && $config->zip != '') {
                            $conf = explode(';', $config->zip);
                            $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                            $xml .= "<zip>".htmlspecialchars($value)."</zip>\n";
                        }
                        if (isset($config->state)  && $config->state != '') {
                            $conf = explode(';', $config->state);
                            $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                            $xml .= "<state>".htmlspecialchars($value)."</state>\n";
                        }
                        if (isset($config->country) && $config->country != '') {
                            $conf = explode(';', $config->country);
                            $value = ($conf[0] == 'js') ?  ((isset($JSfieldsArray[$conf[1]]))?$JSfieldsArray[$conf[1]]:'') : ((isset($userCB[0]->{$conf[1]}))?$userCB[0]->{$conf[1]}:'');
                            $xml .= "<country>".htmlspecialchars($value)."</country>\n";
                        }

                        $xml .= "<location>Work</location>\n";
                        $xml .= "</address>\n";
                        $xml .= "</addresses>\n";
                    }



                    $xml .= "\n</contact-data>";

                    $xml .= "\n</person>";

                    $request['xml'] = $xml;

                    $apiResult = $highrise->pushContact($request);

                    if ($apiResult['status'] != 200 && $apiResult['status'] != 201){
                        // error
                        $failed++;
                        $errorMsg .= '"Server returned error code '.$apiResult['status'].' for user '.$user->name.' (ID '.$user->id.')", ';
                        $apiResult['newContacts'] = 0;
                        $apiResult['updated'] = 0;
                    } else {
                        // success
                        $query = "INSERT INTO #__joomailermailchimpintegration_crm_users "
                        ."(crm, user_id) VALUES "
                        ."('highrise', '$user->id.')";
                        $db->setQuery($query);
                        $db->execute();

                        $addedUsers[] = $user->id;
                    }

                } else {
                    $failed++;
                    $errorMsg .= '"Invalid email => '.$user->email.' ('.$user->name.' - ID '.$user->id.')", ';
                    $apiResult['newContacts'] = 0;
                    $apiResult['updated'] = 0;
                }
            }

        } else {
            unset($_SESSION['addedUsers']);
            $response['finished'] = 1;
            $response['addedUsers'] = '';
            $response['abortAJAX'] = $_SESSION['abortAJAX'];
            echo json_encode($response);
        }

        if (!count($users)) {
            $done = $elements->total;
            unset($_SESSION['addedUsers']);
            $percent = 100;
        } else {
            $done = count($addedUsers);
            $_SESSION['addedUsers'] = $addedUsers;
            $percent = ($done / $elements->total) * 100;
        }

        $response['msg'] = '<div id="bg"></div>' .
                '<div id="progressBarContainer">' .
                    '<div id="progressBarTitle">' . JText::_('JM_ADDING_USERS') . ' (' . $done . '/' . $total . ' ' . JText::_('JM_DONE') . ')</div>' .
                    '<div id="progressBarBg">' .
                        '<div id="progressBarCompleted" style="width: ' . round($percent) . '%;"></div>' .
                        '<div id="progressBarNumber">' . round($percent) . ' %</div>' .
                    '</div>' .
                    '<a id="sbox-btn-close" href="javascript:joomlamailerJS.sync.abortAJAX();">abort</a>' .
                '</div>';

        $response['done']	    = $done;
        $response['newContacts']= $new + $apiResult['new'];
        $response['updated']    = $updated + $apiResult['updated'];
        $response['errors']	    = $failed;
        $response['errorMsg']   = $errorMsg;


        if (($done + $failed) >= $elements->total){
            unset($_SESSION['addedUsers']);
            $response['finished'] = 1;

            if ($errorMsg) {
                $errorMsg  = substr($errorMsg,0,-2);
                $msgErrors = ' ; '.$failed.' '.JText::_('JM_ERRORS').': '.$errorMsg.' ';
            }
            $msg = ($done + $failed).' '.JText::_('JM_USERS_PROCESSED');

            $msg .= ' ('.$response['newContacts'].' '.JText::_('JM_NEW').' ; '.$response['updated'].' '.JText::_('JM_UPDATED').' ';
            if (isset($msgErrors) && $msgErrors) { $msg .= $msgErrors; }
            $msg .= ')';
            $response['finalMessage'] = $msg;

        } else {
            $response['finished'] = 0;
            $response['finalMessage'] = '';
        }
        $response['abortAJAX'] = $_SESSION['abortAJAX'];

        echo json_encode($response);
    }

    function ajax_sync_sugar()
    {
        $db	=& JFactory::getDBO();
        $params =& JComponentHelper::getParams('com_joomailermailchimpintegration');
        $paramsPrefix = (version_compare(JVERSION,'1.6.0','ge')) ? 'params.' : '';
        $sugar_name = $params->get('params.sugar_name');
        $sugar_pwd  = $params->get('params.sugar_pwd');
        $sugar_url  = $params->get('params.sugar_url');

        $config = $this->getModel('sync')->getConfig('sugar');

        if ($config == NULL){
            jimport('joomla.application.component.helper');
            $cHelper = JComponentHelper::getComponent('com_comprofiler', true);
            $cbInstalled = $cHelper->enabled;

            $config = new stdClass();
            $config->first_name = ($cbInstalled) ? 'CB' : 'core';
        }

        $validator = new EmailAddressValidator;

        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_joomailermailchimpintegration'.DS.'libraries'.DS.'sugar.php');

        $sugar = new SugarCRMWebServices;
        $sugar->SugarCRM($sugar_name, $sugar_pwd, $sugar_url);
        $sugar->login();

        $elements = JRequest::getVar('elements', '', 'request', 'string');
        $elements = json_decode($elements);
        if ($elements->done == 0) {
            $_SESSION['abortAJAX'] = 0;
            unset($_SESSION['addedUsers']);
        }

        $failed = $elements->errors;
        $errorMsg = $elements->errorMsg;
        $step = $elements->step;

        if ($_SESSION['abortAJAX'] != 1){

            if (isset($_SESSION['addedUsers'])){
                $exclude = $_SESSION['addedUsers'];
            } else {
                $exclude = array();
            }

            $addedUsers = $exclude;
            if (isset($exclude[0])){
                $exclude = implode('","', $exclude);
                $exclude = '"'.$exclude.'"';
                $excludeCond = 'AND id NOT IN ('.$exclude.') ';
            } else {
                $excludeCond = '';
            }

            if ($elements->range == 'all'){
                $query = 'SELECT * FROM #__users '
                .'WHERE block = 0 '
                .$excludeCond
                .'ORDER BY id '
                .'LIMIT '.$step;

            } else {

                $idList = implode(" OR id = ", $elements->cid);

                $query = 'SELECT * FROM #__users '
                .'WHERE block = 0 '
                .$excludeCond
                .'AND (id = '.$idList.') '
                .'ORDER BY id ';
            }
            $db->setQuery($query);
            $users = $db->loadObjectList();

            $queryJS = false;
            $queryCB = false;
            $JSand = array();
            foreach($config as $k => $v){
                if ($k != 'firstname' && $k != 'lastname'){
                    $vEx = explode(';', $v);
                    if ($vEx[0] == 'js') {
                        $queryJS = true;
                        $JSand[] = $vEx[1];
                    } else if ($vEx[0] == 'CB'){
                        $queryCB = true;
                    }
                }
            }
            $JSand = implode("','", array_unique($JSand));

            $data = array();
            $emails = array();
            $x = 0;
            $new = $elements->new;
            $updated = $elements->updated;
            $userIDs = array();
            foreach($users as $user){
                if ($validator->check_email_address($user->email)){

                    $userCB = false;

                    if ($config->first_name == 'core'){
                        $names = explode(' ', $user->name);
                        $first_name = $names[0];
                        $last_name = '';
                        if (isset($names[1])){
                            for($i=1;$i<count($names);$i++){
                                $last_name .= $names[$i].' ';
                            }
                        }
                        $last_name = trim($last_name);
                    } else {
                        $query = "SELECT * FROM #__comprofiler WHERE user_id = '$user->id'";
                        $db->setQuery($query);
                        $userCB = $db->loadObjectList();

                        $first_name = $userCB[0]->firstname;
                        $last_name  = $userCB[0]->lastname;
                        if ($userCB[0]->middlename != ''){
                            $last_name = $userCB[0]->middlename.' '.$last_name;
                        }
                    }
                    //	var_dump($first_name, $last_name);
                    if ($queryJS){
                        $query = "SELECT field_id, value FROM #__community_fields_values ".
                        "WHERE user_id = '$user->id' ".
                        "AND field_id IN ('$JSand')";
                        $db->setQuery($query);
                        $JSfields = $db->loadObjectList();
                        $JSfieldsArray = array();
                        foreach($JSfields as $jsf){
                            $JSfieldsArray[$jsf->field_id] = $jsf->value;
                        }
                    }

                    if ($queryCB){
                        if (!$userCB){
                            $query = "SELECT * FROM #__comprofiler WHERE user_id = '$user->id'";
                            $db->setQuery($query);
                            $userCB = $db->loadObjectList();
                        }
                    }


                    $data[$x] = array('first_name'	=> $first_name,
                        'last_name'	=> $last_name,
                        'email1'	=> $user->email
                    );


                    foreach($config as $k => $v){
                        if ($k != 'first_name' && $k != 'last_name'){
                            if ($v){
                                $vEx = explode(';', $v);
                                if ($vEx[0] == 'js') {
                                    $data[$x][$k] = (isset($JSfieldsArray[$vEx[1]])) ? $JSfieldsArray[$vEx[1]] : '';
                                } else {
                                    $data[$x][$k] = (isset($userCB[0]->{$vEx[1]})) ? str_replace('|*|',', ',$userCB[0]->{$vEx[1]}) : '';
                                }
                            }

                        }
                    }

                    $emails[$x] = $user->email;
                    $userIDs[] = $user->id;
                    $x++;
                } else {
                    $errorMsg .= '"Invalid email => '.$user->email.'", ';
                    $failed++;
                }
                $addedUsers[] = $user->id;
            }

            if (isset($emails[0])){
                $existing_users = $sugar->findUserByEmail($emails);
            } else {
                $existing_users = array();
            }

            $sendData = array();
            $x = 0;
            foreach($data as $d){
                $sendData[$x] = $d;
                if (isset($existing_users[ $d['email1'] ])){
                    $sendData[$x]['id'] = $existing_users[ $d['email1'] ];
                    $updated++;
                } else {
                    $new++;
                }
                $x++;
            }

            $sugarResult = $sugar->setContactMulti($sendData);

            if ($sugarResult !== false && isset($userIDs[0])){
                $userIDsInserts = array();
                foreach($userIDs as $uid){
                    $userIDsInserts[] = "('sugar', '$uid')";
                }
                $userIDsInsert = implode(', ', $userIDsInserts);
                $query = "INSERT INTO #__joomailermailchimpintegration_crm_users "
                ."(crm, user_id) VALUES "
                .$userIDsInsert;
                $db->setQuery($query);
                $db->execute();
            }

        } else {
            unset($_SESSION['addedUsers']);
            $response['finished'] = 1;
            $response['addedUsers'] = '';
            $response['abortAJAX'] = $_SESSION['abortAJAX'];
            echo json_encode($response);
        }

        if (!count($users)) {
            $done = $elements->total;
            unset($_SESSION['addedUsers']);
            $percent = 100;
        } else {
            $done = count($addedUsers);
            $_SESSION['addedUsers'] = $addedUsers;
            $percent = ($done / $elements->total) * 100;
        }

        $response['msg'] = '<div id="bg"></div>' .
                '<div id="progressBarContainer">' .
                    '<div id="progressBarTitle">' . JText::_('JM_ADDING_USERS') . ' (' . $done . '/' . $total . ' ' . JText::_('JM_DONE') . ')</div>' .
                    '<div id="progressBarBg">' .
                        '<div id="progressBarCompleted" style="width: ' . round($percent) . '%;"></div>' .
                        '<div id="progressBarNumber">' . round($percent) . ' %</div>' .
                    '</div>' .
                    '<a id="sbox-btn-close" href="javascript:joomlamailerJS.sync.abortAJAX();">abort</a>' .
                '</div>';

        $response['done']	    = $elements->run++;
        $response['done']	    = $done;
        $response['newUser']    = $new;
        $response['updated']    = $updated;
        $response['errors']	    = $failed;
        $response['errorMsg']   = $errorMsg;


        if (($done + $failed) >= $elements->total){
            unset($_SESSION['addedUsers']);
            $response['finished'] = 1;

            if ($errorMsg) {
                $errorMsg  = substr($errorMsg,0,-2);
                $msgErrors = ' ; '.$failed.' '.JText::_('JM_ERRORS').': '.$errorMsg.' ';
            }
            $msg = $done.' '.JText::_('JM_USERS_PROCESSED');

            $msg .= ' ('.$new.' '.JText::_('JM_NEW').' ; '.$updated.' '.JText::_('JM_UPDATED').' ';
            if (isset($msgErrors) && $msgErrors) { $msg .= $msgErrors; }
            $msg .= ')';
            $response['finalMessage'] = $msg;

        } else {
            $response['finished'] = 0;
            $response['finalMessage'] = '';
        }
        $response['abortAJAX'] = $_SESSION['abortAJAX'];

        echo json_encode($response);
    }

    public function cancel() {
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=sync');
    }
}
