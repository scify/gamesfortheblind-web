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

class joomailermailchimpintegrationControllerSend extends joomailermailchimpintegrationController {

    public function __construct() {
        parent::__construct();
    }

    public function cancel() {
        $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=archive');
    }

    public function send() {
        $db = JFactory::getDBO();
        $params = JComponentHelper::getParams('com_joomailermailchimpintegration');
        $archiveDir = $params->get('params.archiveDir', '/administrator/components/com_joomailermailchimpintegration/archive');
        $clientDetails = $this->getModel('send')->getClientDetails();

        $listId = JRequest::getVar('listId', '', 'post', 'string');
        $time = JRequest::getVar('time', 0, '', 'string');
        $test = JRequest::getVar('test', 0, 'post', 'int');
        $trackOpens = (bool)JRequest::getBool('trackOpens', false, 'post');
        $trackHTML = (bool)JRequest::getBool('trackHTML',  false, 'post');
        $trackText = (bool)JRequest::getBool('trackText',  false, 'post');
        $ecomm360 = (bool)JRequest::getBool('ecomm360',   false, 'post');
        $campaignType = JRequest::getVar('campaignType', 0, 'post');
        $offsetTime = JRequest::getVar('offset-time', 0, 'post');
        $offsetUnits = JRequest::getVar('offset-units', 0, 'post');
        $offsetDir = JRequest::getVar('offset-dir', 0, 'post');
        $event = JRequest::getVar('event', 0, 'post');
        $mergefield = JRequest::getVar('mergefield', 0, 'post');

        $emails = JRequest::getVar('email', array(), '', 'array');
        $emails = array_unique(array_values(array_filter($emails)));

        $timewarp = JRequest::getVar('timewarp', 0, '', 'int');
        $schedule = JRequest::getVar('schedule', 0, '', 'int');
        if ($schedule) {
            $deliveryDate = JRequest::getVar('deliveryDate', 'Immediately', '', 'string');
            $deliveryTime = JRequest::getVar('deliveryTime', '', '', 'string');

            if ($deliveryDate != 'Immediately') {
                $delivery = $deliveryDate . ' ' . $deliveryTime . ':00';
                // convert time to GMT
                setlocale(LC_TIME, 'en_GB');
                $delivery = gmstrftime("%Y-%m-%d %H:%M:%S", strtotime($delivery));
            } else {
                $delivery = 'Immediately';
            }
        }

        $useSegments = JRequest::getVar('useSegments', 0, '', 'int');
        if ($useSegments) {
            $type = $condition = $conditionDetailValue = array();
            for ($i = 1; $i < 11; $i++) {
                $type[] = JRequest::getVar('segmenttype' . $i, '');
                $condition[] = JRequest::getVar('segmentTypeCondition_' . $i, '');
                $conditionDetailValue[] = JRequest::getVar('segmentTypeConditionDetailValue_' . $i, '');
            }
            // remove empty values
            $type = array_values(array_filter($type));
            $condition = array_values(array_filter($condition));
            $conditionDetailValue = array_values(array_filter($conditionDetailValue));

            $conditions = array();
            for ($i = 0; $i < count($type); $i++) {
                if (is_numeric($type[$i])) {
                    $type[$i] = 'interests-' . $type[$i];
                }
                $conditions[] = array(
                    'field' => $type[$i],
                    'op' => $condition[$i],
                    'value' => $conditionDetailValue[$i]
                );
            }

            $segment_opts = array(
                'match' => JRequest::getVar('match', 'any', 'post', 'string'),
                'conditions' => $conditions
            );
        } else {
            $segment_opts = '';
        }

        $query = $db->getQuery(true)
            ->select('*')
            ->from('#__joomailermailchimpintegration_campaigns')
            ->where($db->qn('creation_date') . ' = ' . $db->q($time));
        $db->setQuery($query);
        $cDetails = $db->loadObject();
        $cData = json_decode($cDetails->cdata);

        $campaign_name_ent = JApplication::stringURLSafe($cDetails->name);
        if (isset($cData->text_only) && $cData->text_only) {
            $filename = JPATH_SITE . $archiveDir . '/' . $campaign_name_ent . '.txt';
            $content = JFile::read($filename);
            $content = array('text' => $content);
        } else {
            if (isset($_SERVER['LOCALSYSTEM'])) {
                $html_file = 'https://www.freakedout.de/tmp/' . $campaign_name_ent . '.html';
            } else {
                $html_file = JURI::root() . (substr($archiveDir, 1)) . '/' . $campaign_name_ent . '.html';
            }
            //$html_file = JURI::root() . (substr($archiveDir, 1)) . '/' . $campaign_name_ent . '.html';
            $content = array('url' => $html_file);

            // remove cache-preventing meta tags from campaign to avoid rendering issues in email clients
            $metaData = array("<meta http-Equiv=\"Cache-Control\" Content=\"no-cache\">\n",
                "<meta http-Equiv=\"Pragma\" Content=\"no-cache\">\n",
                "<meta http-Equiv=\"Expires\" Content=\"0\">\n");

            $filename = JPATH_SITE . $archiveDir . '/' . $campaign_name_ent . '.html';
            $template = JFile::read($filename);
            $template = str_replace($metaData, '', $template);
            $handle = JFile::write($filename, $template);
        }

        $lists = $this->getModel('send')->getMcObject()->lists();

        $memberCount = false;
        foreach($lists as $list){
            if ($list['id'] == $listId){
                $memberCount = $list['member_count'];
                break;
            }
        }

        // break if listId is invalid
        if ($memberCount === false) {
            $this->app->enqueueMessage(JText::_('JM_PLEASE_SELECT_A_LIST'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=send&campaign=' . $time);
        }

        if (!$test && $clientDetails['plan_type'] == 'free' && $memberCount > 2000) {
            $this->app->enqueueMessage(JText::_('JM_TO_MANY_RECIPIENTS'), 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=send&campaign=' . $time);
        }

        // submit to MC
        $type = ($cData->text_only) ? 'plaintext' : 'regular';
        $opts['list_id'] = $listId;
        $opts['title'] = $cDetails->name;
        $opts['subject'] = $cDetails->subject;
        if (!$test && $schedule && $timewarp) {
            $opts['timewarp'] = true;
        }
        $opts['from_email']	= $cDetails->from_email;
        $opts['from_name'] = $cDetails->from_name;
        $opts['tracking']= array(
            'opens' => $trackOpens,
            'html_clicks' => $trackHTML,
            'text_clicks' => $trackText
        );
        $opts['ecomm360'] = $ecomm360;
        $opts['authenticate'] = true;
        //		$opts['analytics'] = array('google'=>'my_google_analytics_key');
        $opts['inline_css'] = true;
        $opts['generate_text'] = true;
        $opts['auto_footer'] = false;
        $opts['folder_id'] = $cDetails->folder_id;

        //Check for auto_tweet
        if (JRequest::getVar('useTwitter', false)) {
            $opts['auto_tweet'] = true;
        }

        //Check for autoresponder
        $type_opts = array();
        if ($campaignType == 1) {
            $type = 'auto';
            $type_opts['offset-units'] = $offsetUnits;
            $type_opts['offset-time'] = $offsetTime;
            $type_opts['offset-dir'] = $offsetDir;
            $type_opts['event'] = $event;
            $type_opts['event-datemerge'] = $mergefield;
            // TODO: implement autoresponder folders
            unset($opts['folder_id']);
        }

        $camapignId = $this->getModel('send')->getMcObject()->campaignCreate($type, $opts, $content, $segment_opts, $type_opts);

        if (!$this->getModel('send')->getMcObject()->errorCode) {
            if ($test) {
                $this->getModel('send')->getMcObject()->campaignSendTest($camapignId, $emails);
                if ($this->getModel('send')->getMcObject()->errorCode) {
                    $msg = MCerrorHandler::getErrorMsg($this->getModel('send')->getMcObject());
                    $this->app->enqueueMessage($msg , 'error');
                } else {
                    $this->app->enqueueMessage(JText::_('JM_TEST_CAMPAIGN_SENT'));
                }
                // wait 5 seconds for the TEST campaign to be sent before we delete it from MC
                sleep(5);
                $this->getModel('send')->getMcObject()->campaignDelete($camapignId);

                $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=send&campaign=' . $time);
            } else {
                if ($schedule) {
                    $this->getModel('send')->getMcObject()->campaignSchedule($camapignId, $delivery);
                } else {
                    $this->getModel('send')->getMcObject()->campaignSendNow($camapignId);
                }
            }
        }

        if ($this->getModel('send')->getMcObject()->errorCode) {
            $msg = MCerrorHandler::getErrorMsg($this->getModel('send')->getMcObject());
            $this->app->enqueueMessage($msg, 'error');
            $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=send&campaign=' . $time);
        } else {
            // clear reports cache
            $this->getModel('main')->cache('joomlamailerReports')->clean('joomlamailerReports');

            if ($schedule) {
                $query = $db->getQuery(true)
                    ->update('#__joomailermailchimpintegration_campaigns')
                    ->set($db->qn('sent') . ' = ' . $db->q(1))
                    ->set($db->qn('cid') . ' = ' . $db->q($camapignId))
                    ->where($db->qn('creation_date') . ' = ' . $db->q($time));
                $db->setQuery($query);
                $db->execute();

                $this->app->enqueueMessage(JText::_('JM_CAMPAIGN_SCHEDULED'));
                $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=main');
            } else {
                $query = $db->getQuery(true)
                    ->update('#__joomailermailchimpintegration_campaigns')
                    ->set($db->qn('sent') . ' = ' . $db->q(2))
                    ->set($db->qn('cid') . ' = ' . $db->q($camapignId))
                    ->where($db->qn('creation_date') . ' = ' . $db->q($time));
                $db->setQuery($query);
                $db->execute();

                if ($campaignType == 1) {
                    $msg = JText::_('JM_AUTORESPONDER_CREATED');
                } else {
                    $msg = JText::_('JM_CAMPAIGN_SENT');
                }

                $this->app->enqueueMessage($msg);
                $this->app->redirect('index.php?option=com_joomailermailchimpintegration&view=campaigns');
            }
        }
    }

    public function getSegmentFields() {
        JHTML::_('behavior.calendar');
        $dc = $this->getmodel('main')->getMailChimpDataCenter();

        $listId = JRequest::getVar('listId', '', 'post', 'string');
        $type = JRequest::getVar('type', '', 'post', 'string');
        $condition = JRequest::getVar('condition', '', 'post', 'string');
        $condition = JRequest::getVar('condition', '', 'post', 'string');
        $conditionDetail = JRequest::getVar('conditionDetail', '', 'post', 'string');
        $num = JRequest::getVar('num', '', 'post', 'string');

        $interests = $this->getModel('send')->getInterestGroupings($listId);
        if ($interests){
            foreach($interests as $int){
                $ints[]   = $int['name'];
                $intIds[] = $int['id'];
                foreach($int['groups'] as $group){
                    $intVals[$int['id']][] = $group['name'];
                }
            }
        }
        $mergevars = $this->getModel('send')->getMergeVars($listId);
        $mvTags = array();
        if ($mergevars){
            foreach($mergevars as $mv){
                if (!in_array($mv['tag'], array('EMAIL', 'FNAME', 'LNAME'))) {
                    $mvs[] = $mv['name'];
                    $mvTags[] = $mv['tag'];
                    $mvTypes[$mv['tag']] = $mv['field_type'];
                    if (isset($mv['choices'])) {
                        foreach ($mv['choices'] as $group) {
                            $mvVals[$mv['tag']][] = $group;
                        }
                    }
                }
            }
        }

        if ($type == 'date') {
            $campaigns = $this->getModel('send')->getSentCampaigns();
            if (isset($campaigns[0])) {
                $disabled = '';
                $campaignDate = $campaigns[0]['send_time'];
                $noCampain = '';
            } else {
                $disabled = 'disabled="disabled"';
                $campaignDate = '(' . JText::_('JM_NO_CAMPAIGN_SENT') . ')';
                $noCampain = ' - (' . JText::_('JM_NO_CAMPAIGN_SENT') . ')';
                $conditionDetail = 'date';
            }
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="gt" '.(($condition=='gt')?'selected="selected"':'').'>'.JText::_('JM_IS_AFTER').'</option>
            <option value="lt" '.(($condition=='lt')?'selected="selected"':'').'>'.JText::_('JM_IS_BEFORE').'</option>
            <option value="eq" '.(($condition=='eq')?'selected="selected"':'').'>'.JText::_('JM_IS').'</option>
            </select>
            <select name="segmentTypeConditionDetail_'.$num.'" id="segmentTypeConditionDetail_'.$num.'" onchange="getSegmentFields(\'#segmentTypeConditionDiv_'.$num.'\', '.$num.');">
            <option value="last" '.$disabled.'>'.JText::_('JM_THE_LAST_CAMPAIGN_WAS_SENT').' - '.substr($campaignDate,0, -9).'</option>
            <option value="campaign" '.$disabled;
            if ($conditionDetail=='campaign') {
                $response['html'] .= ' selected="selected"';
            }
            $response['html'] .= '>'.JText::_('JM_A_SPECIFIC_CAMPAIGN_WAS_SENT').$noCampain.'</option>'
                . '<option value="date"';
            if ($conditionDetail=='date') $response['html'] .= ' selected="selected"';
            $response['html'] .= '>'.JText::_('JM_A_SPECIFIC_DATE').'</option>
            </select>';

            if ($conditionDetail == 'campaign') {
                $response['html'] .= '<div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv" style="top:0;">'
                .'<select name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'">';
                foreach($campaigns as $campaign){
                    if (strlen($campaign['title'])>16){ $campaign['title'] = substr($campaign['title'],0,13).'...'; }
                    $response['html'] .= '<option value="'.$campaign['send_time'].'">'.$campaign['title'].' ('.substr($campaign['send_time'],0, -9).')</option>';
                }
                $response['html'] .= '</select>';
            } else if ($conditionDetail=='date'){
                $response['html'] .= '<div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv">';
                $response['html'] .= JHTML::calendar(date('Y-m-d'), 'segmentTypeConditionDetailValue_'.$num.'', 'segmentTypeConditionDetailValue_'.$num.'', '%Y-%m-%d',
                    array('size'=>'12',
                        'maxlength'=>'10'
                ));
                $response['html'] .= '</div>';
                $response['js'] = 'Calendar.setup({inputField : "segmentTypeConditionDetailValue_'.$num.'", ifFormat : "%Y-%m-%d", button : "segmentTypeConditionDetailValue_'.$num.'_img", align : "Tl", singleClick : true });';
            } else {
                $response['html'] .= '<input type="hidden" value="'.$campaigns[0]['send_time'].'" name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'" /></div>';
            }

        } else if ($type=='email' || $type=='fname' || $type=='lname') {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="eq">'.JText::_('JM_IS').'</option>
            <option value="ne">'.JText::_('JM_IS_NOT').'</option>
            <option value="like">'.JText::_('JM_CONTAINS').'</option>
            <option value="nlike">'.JText::_('JM_DOES_NOT_CONTAIN').'</option>
            <option value="starts">'.JText::_('JM_STARTS_WITH').'</option>
            <option value="ends">'.JText::_('JM_ENDS_WITH').'</option>
            <option value="gt">'.JText::_('JM_IS_GREATER_THAN').'</option>
            <option value="lt">'.JText::_('JM_IS_LESS_THAN').'</option>
            </select>
            <div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv">
            <input type="text" value="" id="segmentTypeConditionDetailValue_'.$num.'" name="segmentTypeConditionDetailValue_'.$num.'"/>
            </div>';

        } else if ($interests && in_array($type, $intIds)) {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="one">'.JText::_('JM_ONE_OF').'</option>
            <option value="all">'.JText::_('JM_ALL_OF').'</option>
            <option value="none">'.JText::_('JM_NONE_OF').'</option>
            </select>
            <div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv">
            <select multiple="multiple" size="3" id="segmentTypeConditionDetailValue_'.$num.'" name="segmentTypeConditionDetailValue_'.$num.'">';
            foreach($intVals[$type] as $val) {
                $response['html'] .= '<option value="'.$val.'">'.$val.'</option>';
            }

            $response['html'] .= '</select></div>';

        } else if ($mergevars && in_array($type, $mvTags)) {
            if ($mvTypes[$type] == 'radio' || $mvTypes[$type] == 'dropdown') {
                $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
                <option value="eq">'.JText::_('JM_IS').'</option>
                <option value="ne">'.JText::_('JM_IS_NOT').'</option>
                </select>
                <div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv">
                <select multiple="multiple" size="3" id="segmentTypeConditionDetailValue_'.$num.'" name="segmentTypeConditionDetailValue_'.$num.'">';
                foreach($mvVals[$type] as $val){
                    $response['html'] .= '<option value="'.$val.'">'.$val.'</option>';
                }
                $response['html'] .= '</select></div>';
            } else if ($mvTypes[$type] == 'date') {
                $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
                <option value="gt">'.JText::_('JM_IS_AFTER').'</option>
                <option value="lt">'.JText::_('JM_IS_BEFORE').'</option>
                <option value="eq">'.JText::_('JM_IS').'</option>
                <option value="ne">'.JText::_('JM_IS_NOT').'</option>
                </select>';
                $response['html'] .= '<div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv">';
                $response['html'] .= JHTML::calendar(date('Y-m-d'), 'segmentTypeConditionDetailValue_'.$num.'', 'segmentTypeConditionDetailValue_'.$num.'', '%Y-%m-%d',
                    array('size'=>'12',
                        'maxlength'=>'10'
                ));
                $response['html'] .= '</div>';
                $response['js'] = 'Calendar.setup({inputField : "segmentTypeConditionDetailValue_'.$num.'", ifFormat : "%Y-%m-%d", button : "segmentTypeConditionDetailValue_'.$num.'_img", align : "Tl", singleClick : true });';

            } else {
                $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
                <option value="eq">'.JText::_('JM_IS').'</option>
                <option value="ne">'.JText::_('JM_IS_NOT').'</option>
                <option value="like">'.JText::_('JM_CONTAINS').'</option>
                <option value="nlike">'.JText::_('JM_DOES_NOT_CONTAIN').'</option>
                <option value="starts">'.JText::_('JM_STARTS_WITH').'</option>
                <option value="ends">'.JText::_('JM_ENDS_WITH').'</option>
                <option value="gt">'.JText::_('JM_IS_GREATER_THAN').'</option>
                <option value="lt">'.JText::_('JM_IS_LESS_THAN').'</option>
                </select>
                <div id="segmentTypeConditionDiv_'.$num.'" class="segmentTypeConditionDetailDiv">
                <input type="text" value="" id="segmentTypeConditionDetailValue_'.$num.'" name="segmentTypeConditionDetailValue_'.$num.'"/>
                </div>';
            }

        } else if ($type == 'rating') {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="eq">'.JText::_('JM_IS').'</option>
            <option value="ne">'.JText::_('JM_IS_NOT').'</option>
            <option value="gt">'.JText::_('JM_IS_GREATER_THAN').'</option>
            <option value="lt">'.JText::_('JM_IS_LESS_THAN').'</option>
            </select>
            <ul class="memberRating" data-num="' . $num . '">';
                for ($i = 1; $i < 6; $i++) {
                    $response['html'] .= '<li class="rating_' . $i . '" value="' . $i . '"></li>';
                }
            $response['html'] .= '</ul>
            <input type="hidden" value="0" name="segmentTypeConditionDetailValue_' . $num . '" id="segmentTypeConditionDetailValue_' . $num . '" />';


        } else if ($type == 'aim') {
            $campaigns = $this->getModel('send')->getSentCampaigns();
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="open">'.JText::_('JM_OPENED_').'</option>
            <option value="noopen">'.JText::_('JM_NOT_OPENED_').'</option>
            <option value="click">'.JText::_('JM_CLICKED').'</option>
            <option value="noclick">'.JText::_('JM_NOT_CLICKED').'</option>
            </select>
            <select name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'">
            <option value="any">'.JText::_('JM_ANY_CAMPAIGN').'</option>';
            foreach($campaigns as $campaign){
                $response['html'] .= '<option value="'.$campaign['id'].'">'.$campaign['title'].' ('.$campaign['send_time'].')</option>';
            }
            $response['html'] .= '</select>';

        } else if ($type == 'social_network') {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="member">'.JText::_('JM_IS_A_MEMBER_OF').'</option>
            <option value="notmember">'.JText::_('JM_IS_NOT_A_MEMBER_OF').'</option>
            </select>
            <select name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'">
            <option value="twitter">Twitter</option>
            <option value="facebook">Facebook</option>
            <option value="myspace">MySpace</option>
            <option value="linkedin">LinkedIn</option>
            <option value="flickr">Flickr</option>';
            $response['html'] .= '</select>';
        } else if ($type == 'social_influence') {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="eq">'.JText::_('JM_IS').'</option>
            <option value="ne">'.JText::_('JM_IS_NOT').'</option>
            <option value="gt">'.JText::_('JM_IS_GREATER_THAN').'</option>
            <option value="lt">'.JText::_('JM_IS_LESS_THAN').'</option>
            </select>
            <div style="margin-bottom:11px;">
            <ul class="memberRating" onmouseout="restoreRating('.$num.');">
            <li class="rating_1" value="1" onclick="rating('.$num.',this.value,1);" onmouseover="rating('.$num.',this.value,0);"></li>
            <li class="rating_2" value="2" onclick="rating('.$num.',this.value,1);" onmouseover="rating('.$num.',this.value,0);"></li>
            <li class="rating_3" value="3" onclick="rating('.$num.',this.value,1);" onmouseover="rating('.$num.',this.value,0);"></li>
            <li class="rating_4" value="4" onclick="rating('.$num.',this.value,1);" onmouseover="rating('.$num.',this.value,0);"></li>
            <li class="rating_5" value="5" onclick="rating('.$num.',this.value,1);" onmouseover="rating('.$num.',this.value,0);"></li>
            </ul>
            <input type="hidden" value="0" name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'" />
            </div>';
        } else if ($type == 'social_gender') {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
            <option value="eq">'.JText::_('JM_IS').'</option>
            <option value="ne">'.JText::_('JM_IS_NOT').'</option>
            </select>
            <select name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'">
            <option value="female">'.JText::_('JM_FEMALE').'</option>
            <option value="male">'.JText::_('JM_MALE').'</option>
            </select>';
        } else if ($type == 'social_age') {
            $response['html'] = '<select name="segmentTypeCondition_'.$num.'" id="segmentTypeCondition_'.$num.'">
                <option value="gt">'.JText::_('JM_IS_GREATER_THAN').'</option>
                <option value="lt">'.JText::_('JM_IS_LESS_THAN').'</option>
                </select>
                <input type="range" name="segmentTypeConditionDetailValue_'.$num.'" id="segmentTypeConditionDetailValue_'.$num.'" value="1" step="1" min="1" max="99" style="margin:0 5px;" />
                <span id="segmentTypeConditionDetailValueOutput_'.$num.'">0</span>';
            $response['js'] = '$("#segmentTypeConditionDetailValue_'.$num.'").change(function(){
                $("#segmentTypeConditionDetailValueOutput_'.$num.'").html(this.value);
            });';
        } else {
            $response['html'] = '';
        }

        echo json_encode($response);
    }

    public function testSegments() {
        $listId = JRequest::getVar('listId', '', 'post', 'string');
        $condCount = JRequest::getVar('condCount', '', 'post', 'string');
        $type = array_filter(explode('|*|', JRequest::getVar('type', '', 'post', 'string')));
        $condition = array_filter(explode('|*|', JRequest::getVar('condition', '', 'post', 'string')));
        $conditionDetailValue = array_filter(explode('|*|', JRequest::getVar('conditionDetailValue', '', 'post', 'string')));
        $match = JRequest::getVar('match', '', 'post', 'string');

        $conditions = array();
        for ($i = 0; $i < count($type); $i++) {
            if (is_numeric($type[$i])) {
                $type[$i] = 'interests-' . $type[$i];
            }
            $conditionDetailValue[$i] = array_filter(array_unique(explode('|*|', $conditionDetailValue[$i])));
            $conditionDetailValue[$i] = implode(',', $conditionDetailValue[$i]);

            $conditions[] = array(
                'field' => $type[$i],
                'op' => $condition[$i],
                'value' => $conditionDetailValue[$i]
            );
        }

        $opts = array(
            'match' => $match,
            'conditions' => $conditions
        );

        $result = $this->getModel('send')->getMcObject()->campaignSegmentTest($listId, $opts);

        $response = array();
        if ($this->getModel('send')->getMcObject()->errorCode) {
            $MCerrorHandler = new MCerrorHandler();
            $response['error'] = 1;
            $response['msg'] = $MCerrorHandler->getErrorMsg($this->getModel('send')->getMcObject());
        } else if ($result) {
            $response['msg'] = JText::sprintf('JM_X_RECIPIENTS_IN_THIS_SEGMENT', $result);
            $response['creditCount'] = $result;
        } else {
            $response['msg'] = JText::sprintf('JM_X_RECIPIENTS_IN_THIS_SEGMENT', 0);
            $response['creditCount'] = 0;
        }

        echo json_encode($response);
    }

    public function addCondition() {
        $listId = JRequest::getVar('listId', '', 'post', 'string');
        $conditionCount = JRequest::getVar('conditionCount', '', 'post', 'string');

        $interests = $this->getModel('send')->getInterestGroupings($listId);
        $mergevars = $this->getModel('send')->getMergeVars($listId);
        $campaigns = $this->getModel('send')->getSentCampaigns();

        $x = $conditionCount + 1;
        $response['js'] = false;

        $content = '<select name="segmenttype' . $x . '" id="segmenttype' . $x . '" class="segmentType">
            <option value="date">' . JText::_('JM_DATE_ADDED') . '</option>
            <option value="email">' . JText::_('JM_EMAIL_ADDRESS') . '</option>
            <option value="fname">' . JText::_('JM_FIRSTNAME') . '</option>
            <option value="lname">' . JText::_('JM_LASTNAME') . '</option>
            <option value="rating">' . JText::_('JM_MEMBER_RATING') . '</option>
            <option value="aim">' . JText::_('JM_SUBSCRIBER_ACTIVITY') . '</option>
            <option value="social_network">' . JText::_('JM_SOCIAL_NETWORK') . '</option>
            <option value="social_influence">' . JText::_('JM_SOCIAL_INFLUENCE') . '</option>
            <option value="social_gender">' . JText::_('JM_SOCIAL_GENDER') . '</option>
            <option value="social_age">' . JText::_('JM_SOCIAL_AGE') . '</option>';

        if ($interests) {
            foreach ($interests as $interest){
                $content .= '<option value="' . $interest['id'] . '">'
                    . ((strlen($interest['name']) > 25) ? substr($interest['name'], 0, 22) . '...' : $interest['name'])
                    . '</option>';
            }
        }
        if ($mergevars){
            foreach ($mergevars as $mv) {
                if (!in_array($mv['tag'], array('EMAIL', 'FNAME', 'LNAME'))) {
                    $content .= '<option value="' . $mv['tag'] . '">'
                        . ((strlen($mv['name']) > 25) ? substr($mv['name'], 0, 22) . '...' : $mv['name'])
                        . '</option>';
                }
            }
        }

        $content .= '</select>
            <div id="segmentTypeConditionDiv_' . $x . '" class="segmentConditionDiv">
                <select name="segmentTypeCondition_' . $x . '" id="segmentTypeCondition_' . $x . '">
                    <option value="gt">' . JText::_('JM_IS_AFTER') . '</option>
                    <option value="lt">' . JText::_('JM_IS_BEFORE') . '</option>
                    <option value="eq">' . JText::_('JM_IS') . '</option>
                </select>
                <select name="segmentTypeConditionDetail_' . $x . '" id="segmentTypeConditionDetail_' . $x . '">';
        if (!isset($campaigns[0])) {
            $disabled = 'disabled="disabled"';
            $campaignDate = '(' . JText::_('JM_NO_CAMPAIGN_SENT') . ')';
            $noCampain = ' - (' . JText::_('JM_NO_CAMPAIGN_SENT') . ')';
        } else {
            $disabled = '';
            $campaignDate = $campaigns[0]['send_time'];
            $noCampain = '';
        }
        $content .= '<option value="last" ' . $disabled . '>' . JText::_('JM_THE_LAST_CAMPAIGN_WAS_SENT') . ' - ' . $campaignDate . '</option>
                <option value="campaign" ' . $disabled . '>' . JText::_('JM_A_SPECIFIC_CAMPAIGN_WAS_SENT') . '' . $noCampain . '</option>
                <option value="date">' . JText::_('JM_A_SPECIFIC_DATE') . '</option>
            </select>
            <div id="segmentTypeConditionDetailDiv_' . $x . '" class="segmentTypeConditionDetailDiv">';
        if (isset($campaigns[0])) {
            $content .= '<input type="hidden" value="' . $campaigns[0]['send_time'] . '" name="segmentTypeConditionDetailValue_' . $x . '" id="segmentTypeConditionDetailValue_' . $x . '" />';
        } else {
            $content .= JHTML::calendar(date('Y-m-d'), 'segmentTypeConditionDetailValue_' . $x . '', 'segmentTypeConditionDetailValue_' . $x . '', '%Y-%m-%d',
                array(
                    'size'=>'12',
                    'maxlength'=>'10'
            ));
            $response['js'] .= 'Calendar.setup({inputField: "segmentTypeConditionDetailValue_' . $x . '", ifFormat : "%Y-%m-%d", button : "segmentTypeConditionDetailValue_' . $x . '_img", align : "Tl", singleClick : true });';
        }
        $content .= '</div></div>';

        $response['html'] = $content . '</div>'
            . '<div class="removeCondition"><a href="javascript:void(0);joomlamailerJS.send.removeCondition(' . $x . ');" title="' . JText::_('JM_REMOVE') . '">'
            . '<img src="' . JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/deselect.png" alt="' . JText::_('JM_REMOVE') . '" style="padding:3px 5px;"/></a>'
            . '</div><div style="clear: both;"></div>';

        $response['js'] .= '$(\'#segmenttype' . $x . '\').change(function() {
            joomlamailerJS.send.getSegmentFields(\'#segmentTypeConditionDiv_' . $x . '\', ' . $x . ');
        });
        $(\'#segmentTypeConditionDetail_' . $x . '\').change(function() {
            joomlamailerJS.send.getSegmentFields(\'#segmentTypeConditionDiv_' . $x . '\', ' . $x . ');
        });';

        echo  json_encode($response);
    }


    public function addInterests() {
        $listId = JRequest::getVar('listId', '', 'post', 'string');

        $interests = $this->getModel('send')->getInterestGroupings($listId);
        $mergevars = $this->getModel('send')->getMergeVars($listId);

        $res = array();

        if ($interests) {
            foreach ($interests as $int) {
                $res[] = array(
                    'id' => $int['id'],
                    'name' => (strlen($int['name']) > 25) ? substr($int['name'], 0, 22).'...' : $int['name']
                );
            }
        }

        if ($mergevars) {
            foreach ($mergevars as $mv) {
                if (!in_array($mv['tag'], array('EMAIL', 'FNAME', 'LNAME'))) {
                    $res[] =  array(
                        'id' => $mv['tag'],
                        'name' => (strlen($mv['name']) > 25) ? substr($mv['name'], 0, 22).'...' : $mv['name']
                    );
                }
            }
        }

        echo json_encode($res);
    }

    public function getMerges() {
        $response = array();
        $listId = JRequest::getVar('listId', '', 'post', 'string');

        $mergeVars = $this->getModel('send')->getMcObject()->listMergeVars($listId);

        if ($mergeVars) {
            foreach($mergeVars as $index => $var) {
                if ($var['field_type'] != 'date') {
                    unset($mergeVars[$index]);
                }
            }
        }

        if (is_array($mergeVars) && count($mergeVars)) {
            $first= new stdClass();
            $first->tag = -1;
            $first->name = '-- ' . JText::_('JM_SELECT_A_MERGE_FIELD') . ' --';
            $merges = array_merge(array($first), $mergeVars);

            $response['html'] = JHTML::_('select.genericlist', $merges, 'mergefield', 'style="width:auto;"', 'tag', 'name' , '');
        } else {
            $response['html'] = '<a href="index.php?option=com_joomailermailchimpintegration&view=fields&listid=' . $listId . '" class="inputInfo">'
                . JText::_('JM_CREATE_MERGE_FIELDS') . '</a>';
        }

        echo json_encode($response);
    }

    public function ajax_sync_hotness() {
        $listId = JRequest::getVar('listId', '', 'post', 'string');
        $total = JRequest::getVar('total', '', 'post', 'string');
        $step = JRequest::getVar('step', '', 'post', 'string');
        $done = JRequest::getVar('done', '', 'post', 'string');
        $errors = JRequest::getVar('errors', '', 'post', 'string');
        $errorMsg = JRequest::getVar('errorMsg', '', 'post', 'string');
        $addedUsers = JRequest::getVar('addedUsers', '', 'post', 'string');
        $failed = JRequest::getVar('failed', array(), 'post', 'string');
        $offset = JRequest::getVar('offset', '', 'post', 'string');

        if ($done == 0) {
            $_SESSION['abortAJAX'] = 0;
            unset($_SESSION['addedUsers']);
            unset($_SESSION['HotnessExists']);
        }

        if ($_SESSION['abortAJAX'] == 1) {
            unset($_SESSION['addedUsers']);
            $response['addedUsers'] = '';
            $response['finished'] = 1;
            $response['abortAJAX'] = 1;
            echo json_encode($response);
            return;
        }

        $db = JFactory::getDBO();
        $MCerrorHandler = new MCerrorHandler();

        // retrieve hotness rating
        require_once(JPATH_COMPONENT_ADMINISTRATOR . DS . '/libraries/joomailer/hotActivityComposite.php');
        $composite = new hotActivityComposite();
        $hotnessRating = $composite->getAllUserHotnessValue($listId);

        $exclude = (isset($_SESSION['addedUsers'])) ? $_SESSION['addedUsers'] : array();
        if (count($exclude)) {
            $failed = array_merge($exclude, $failed);
            $exclude = 'AND j.userid NOT IN ("' . implode('","', $failed) . '") ';
        } else {
            $exclude = '';
        }

        $data = $this->getModel('send')->getMcObject()->listMembers($listId, '', '', $offset, $step);

        if (count($data) > 0) {
            // determine if the interest group Hotness already exists, if not: create it
            if (!isset($_SESSION['HotnessExists'])) {
                $query = $db->getQuery(true)
                    ->select($db->qn('value'))
                    ->from('#__joomailermailchimpintegration_misc')
                    ->where($db->qn('type') . ' = ' . $db->q('hotness'))
                    ->where($db->qn('listid') . ' = ' . $db->q($listId));
                $db->setQuery($query);
                $hotnessId = $db->loadResult();

                if ($hotnessId == NULL) {
                    $result = $this->getModel('send')->getMcObject()->listInterestGroupingAdd($listId, JText::_('JM_HOTNESS_RATING'), 'hidden', array(1,2,3,4,5));
                    if (is_int($result)) {
                        $query = $db->getQuery(true)
                            ->insert('#__joomailermailchimpintegration_misc')
                            ->set($db->qn('type') . ' = ' . $db->q('hotness'))
                            ->set($db->qn('listid') . ' = ' . $db->q($listId))
                            ->set($db->qn('value') . ' = ' . $db->q($result));
                        $db->setQuery($query);
                        $db->execute();
                        $_SESSION['HotnessExists'] = $result;
                    }
                } else {
                    $_SESSION['HotnessExists'] = $hotnessId;
                }
            }

            $successCount = 0;
            for ($x = 0; $x < count($data); $x += $step) {
                if ($_SESSION['abortAJAX'] == 1) {
                    unset($_SESSION['addedUsers']);
                    break;
                }

                $k = 0;
                $batch = array();

                for ($y = $x; $y < ($x + $step); $y++) {
                    if ($_SESSION['abortAJAX'] == 1) {
                        unset($_SESSION['addedUsers']);
                        break;
                    }

                    $dat = (isset($data[$y])) ? $data[$y] : false;
                    if ($dat) {
                        $addedUsers[] = $dat['email'];
                        $batch[$k]['EMAIL'] = $dat['email'];
                        if (!isset($hotnessRating[$dat['email']])){
                            $hotnessRating[$dat['email']] = 2;
                        }
                        $batch[$k]['GROUPINGS'][] = array(
                            'id' => $_SESSION['HotnessExists'],
                            'groups' => $hotnessRating[$dat['email']]
                        );

                        $k++;
                    } else {
                        break;
                    }
                }

                if ($batch) {
                    $optin = false; //yes, send optin emails
                    $up_exist = true; // yes, update currently subscribed users
                    $replace_int = true; // false = add interest, don't replace
                    $result = $this->getModel('send')->getMcObject()->listBatchSubscribe($listId, $batch, $optin, $up_exist, $replace_int);
                    $successCount += $result['success_count'];

                    if ($result['error_count']) {
                        foreach($result['errors'] as $e) {
                            $tmp = new stdClass();
                            $tmp->errorCode = $e['code'];
                            $tmp->errorMessage = $e['message'];
                            $errorMsg .= '"' . $MCerrorHandler->getErrorMsg($tmp) . ' => ' . $e['row']['EMAIL'] . '", ';
                        }
                    }
                }
            }

            $addedUsers = array_unique($addedUsers);

            if (!count($data)) {
                $done = $total;
                unset($_SESSION['addedUsers']);
                $percent = 100;
            } else {
                $done = count($addedUsers);
                $_SESSION['addedUsers'] = $addedUsers;
                $percent = ($done / $total) * 100;
            }

            $response['msg'] = '<div id="bg"></div>'
                .'<div style="background:#FFFFFF none repeat scroll 0 0;border:10px solid #000000;height:100px;left:37%;position:relative;text-align:center;top:37%;width:300px; ">'
                .'<div style="margin: 35px auto 3px; width: 300px; text-align: center;">'.JText::_('adding users').' ('.$done.'/'.$total.' '.JText::_('done').')</div>'
                .'<div style="margin: auto; background: transparent url('.JURI::root().'media/com_joomailermailchimpintegration/backend/images/progress_bar_grey.gif) repeat scroll 0% 0%; width: 190px; height: 14px; display: block;">'
                .'<div style="width: '.$percent.'%; overflow: hidden;">'
                .'<img src="'.JURI::root().'media/com_joomailermailchimpintegration/backend/images/progress_bar.gif" style="margin: 0 5px 0 0;"/>'
                .'</div>'
                .'<div style="width: 190px; text-align: center; position: relative;top:-13px; font-weight:bold;">'.round($percent,0).' %</div>'
                .'</div>'
                .'<a id="sbox-btn-close" style="text-indent:-5000px;right:-20px;top:-18px;outline:none;" href="javascript:void(0);joomlamailerJS.sync.abortAJAX(true);">abort</a>'
                .'</div>';

            $response['done'] = $done;
            $response['errors']	= count($failed);
            $response['errorMsg'] = $errorMsg;
            $response['addedUsers'] = array_values(array_unique($addedUsers));

            if (($done + count($failed) +  $errors) >= $total) {
                $response['finished'] = 1;

                $msg = $done . ' ' . JText::_('JM_USERS_SYNCHRONIZED') . '.';
                if ($errorMsg) {
                    $errorMsg = substr($errorMsg,0,-2);
                    $msg .= ' (' . count($failed) . ' ' . JText::_('Errors') . ': ' . $errorMsg . ')';
                }
                $response['finalMessage'] = $msg;
            } else {
                $response['finished'] = 0;
                $response['finalMessage'] = '';
            }
            $response['abortAJAX'] = $_SESSION['abortAJAX'];
        } else {
            unset($_SESSION['addedUsers']);
            $response['addedUsers']   = '';
            $response['finalMessage'] = JText::_('JM_NO_USERS_FOUND');
            $response['finished']     = 1;
            $response['abortAJAX']    = $_SESSION['abortAJAX'];
        }

        echo json_encode($response);
    }
}
