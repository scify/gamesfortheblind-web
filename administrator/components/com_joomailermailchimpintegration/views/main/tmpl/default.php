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

JHTML::_('behavior.modal');
$MCapi = $this->params->get('params.MCapi');
$sugar_name = $this->params->get('params.sugar_name', 0);
$sugar_pwd = $this->params->get('params.sugar_pwd', 0);
$sugar_url = $this->params->get('params.sugar_url', 0);
$highrise_url = $this->params->get('params.highrise_url', 0);
$highrise_api_token = $this->params->get('params.highrise_api_token', 0);

//$model = $this->getModel();
$JoomlamailerMC = new JoomlamailerMC();

if (!$MCapi) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing();
    return;
} else if (!$JoomlamailerMC->pingMC()) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing(1);
    return;
}

echo checkPermissions::check();

if ($MCapi && $JoomlamailerMC->pingMC()){
    echo $this->getModel()->setupInfo();
}

if ($sugar_name && $sugar_pwd && $sugar_url){
    $CRMauth = new CRMauth();
    echo $CRMauth->checkSugarLogin();
}
if ($highrise_url && $highrise_api_token){
    $CRMauth = new CRMauth();
    echo $CRMauth->checkHighriseLogin();
}
$archiveDir = $this->params->get('params.archiveDir', '/administrator/components/com_joomailermailchimpintegration/archive');
$dc = $this->getModel()->getMailChimpDataCenter(); ?>
<table width="100%">
    <tr>
        <td valign="top">
            <form name="adminForm" id="adminForm" action="index.php" method="post" style="margin:0">
                <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
                <input type="hidden" name="task" value="" />
                <input type="hidden" name="boxchecked" value="1" />
                <input type="hidden" name="controller" value="main" />
            </form>
            <h2 style="margin: 0;"><?php echo JText::_('JM_CAMPAIGNS'); ?></h2>
            <h3 style="margin-bottom: 1em; width: 300px; float:left;"><?php echo JText::_('JM_PENDING_CAMPAIGNS'); ?></h3>
            <script type="text/javascript">
                function submitForm(pressbutton){
                    if (document.adminForm1.boxchecked.value == 0) {
                        alert('<?php echo JText::_('JM_PLEASE_SELECT_A_DRAFT'); ?>');
                    } else {
                        document.adminForm1.task.value = pressbutton;
                        document.adminForm1.submit();
                    }
                }
            </script>
            <form name="adminForm1" id="adminForm1" action="index.php" method="post">
                <div id="savedButtons">
                    <?php if (count($this->drafts)!=0){ ?>
                        <?php if (JOOMLAMAILER_CREATE_DRAFTS){ ?>
                            <a href="javascript:submitForm('edit');" id="editDraft"><?php echo JText::_('JM_EDIT'); ?></a>
                            <?php } ?>
                        <?php if (JOOMLAMAILER_CREATE_DRAFTS && JOOMLAMAILER_MANAGE_CAMPAIGNS){ ?>
                            <span> | </span>
                            <?php } ?>
                        <?php if (JOOMLAMAILER_MANAGE_CAMPAIGNS){ ?>
                            <a href="javascript:submitForm('send');" id="sendDraft"><?php echo JText::_('JM_SEND_CAMPAIGN'); ?></a>
                            <?php } ?>
                        <?php } ?>
                    <?php if (JOOMLAMAILER_CREATE_DRAFTS){ ?>
                        <a id="createCampaign" class="JMbuttonOrange" href="index.php?option=com_joomailermailchimpintegration&view=create"><?php echo JText::_('JM_CREATE_CAMPAIGN'); ?></a>
                        <?php } ?>
                </div>
                <div style="clear:both;"></div>

                <?php
                    if (count($this->drafts)==0){
                        echo JText::_('JM_NO_PENDING_CAMPAIGNS');
                        echo '</form>';
                    } else {
                    ?>

                    <table class="adminlist">
                        <thead>
                            <tr>
                                <th width="20"><input type="radio" name="campaign" value="" onclick="document.adminForm1.boxchecked.value = 0;"/></th>
                                <th><?php echo JText::_('JM_NAME'); ?></th>
                                <th><?php echo JText::_('JM_SUBJECT'); ?></th>
                                <th nowrap="nowrap" width="5"><?php echo JText::_('JM_CREATION_DATE'); ?></th>
                                <th nowrap="nowrap" width="5"><?php echo JText::_('JM_PREVIEW'); ?></th>
                            </tr>
                        </thead>
                        <?php
                            $k = 0;
                            for ($i=0, $n=count($this->drafts); $i < $n; $i++){
                                $draft = &$this->drafts[$i];
                                // preview link
                                $campaignNameSafe = JApplication::stringURLSafe($draft->name);
                                if (JFile::exists(JPATH_SITE . '/' . (substr($archiveDir,1)) . '/' . $campaignNameSafe . '.html')){
                                    $link = JURI::root() . (substr($archiveDir,1)) . '/' . $campaignNameSafe . '.html';
                                } else {
                                    $link = JURI::root() . (substr($archiveDir,1)) . '/' . $campaignNameSafe . '.txt';
                                }
                            ?>
                            <tr class="<?php echo "row$k"; ?>">
                                <td><input type="radio" name="campaign" value="<?php echo $draft->creation_date;?>" onclick="document.adminForm1.boxchecked.value = 1;"/></td>
                                <td align="center">
                                    <?php echo (strlen($draft->name)>30) ? substr($draft->name, 0, 27).'...' : $draft->name; ?>
                                </td>
                                <td align="center">
                                    <?php echo (strlen($draft->subject)>30) ? substr($draft->subject, 0, 27).'...' : $draft->subject; ?>
                                </td>
                                <td align="center" nowrap="nowrap"><?php echo strftime('%Y-%m-%d %H:%M:%S', $draft->creation_date); ?></td>
                                <td align="center"><a class="modal" rel="{handler: 'iframe', size: {x: 980, y: 550} }" href="<?php echo $link;?>">
                                    <img src="../media/com_joomailermailchimpintegration/backend/images/preview_32.png" alt="Preview" title="Preview" height="17"/></a></td>
                            </tr>
                            <?php
                                $k = 1 - $k;
                        } ?>
                    </table>

                    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
                    <input type="hidden" name="task" value="" />
                    <input type="hidden" name="boxchecked" value="" />
                    <input type="hidden" name="controller" value="main" />
                </form>
                <?php if (JOOMLAMAILER_CREATE_DRAFTS){ ?>
                    <div class="moreCampaigns">
                        <a href="index.php?option=com_joomailermailchimpintegration&view=campaignlist&filter_status=save">
                            <?php echo JText::_('JM_MORE_CAMPAIGNS');?>
                        </a>
                    </div>
                    <?php } ?>
                <?php } ?>

            <script type="text/javascript">
                function submitForm2(task){
                    if (document.adminForm2.boxchecked.value==0){
                        alert('<?php echo JText::_('JM_PLEASE_SELECT_A_CAMPAIGN'); ?>');
                    } else {
                        if (task=='archive'){
                            if (confirm('<?php echo JText::_('Are you sure to archive this campaign');?>?')){
                                document.adminForm2.task.value = task;
                                document.adminForm2.submit();
                            }
                        } else {
                            document.adminForm2.task.value = task;
                            document.adminForm2.submit();
                        }
                    }
                }
            </script>
            <h3 style="margin-bottom: 1em; width: 300px; float:left;"><?php echo JText::_('JM_SENT_CAMPAIGNS'); ?></h3>
            <form name="adminForm2" id="adminForm2" action="index.php?option=com_joomailermailchimpintegration&view=main" method="post"><?php
                if (!isset($this->campaigns) || count($this->campaigns)==0){
                    echo JText::_('JM_NO_SENT_CAMPAIGNS');
                } else {
                    if (JOOMLAMAILER_CREATE_DRAFTS) { ?>
                        <div id="campaignButtons">
                            <a href="javascript:submitForm2('copyCampaign');" id="copyCampaign"><?php echo JText::_('JM_COPY'); ?></a>
                        </div><?php
                    } ?>
                    <div style="clear:both;"></div>
                    <table class="adminlist">
                        <thead>
                            <tr>
                                <th width="20"><input type="radio" name="cid" value="" onclick="document.adminForm2.boxchecked.value = 0;"/></th>
                                <th><?php echo JText::_('JM_NAME'); ?></th>
                                <th><?php echo JText::_('JM_SUBJECT'); ?></th>
                                <th><?php echo JText::_('JM_STATUS'); ?></th>
                                <th><?php echo JText::_('JM_DELIVERY_DATE'); ?></th>
                                <th><?php echo JText::_('JM_TOTAL_RECIPIENTS'); ?></th>
                                <th><?php echo JText::_('JM_UNIQUE_OPENS'); ?></th>
                                <th><?php echo JText::_('JM_CLICKS'); ?></th>
                                <th><?php echo JText::_('JM_ARCHIVE'); ?></th>
                                <th><?php echo JText::_('JM_SHARE'); ?></th>
                            </tr>
                        </thead>
                        <?php
                            $k = 0;
                            $i = 0;
                            foreach($this->campaigns as $campaign) {
                                if ($i==5) break; // display only 5 campaigns
                                if ($campaign['status'] != 'save') {
                                    if ($campaign['status']=='schedule') {
                                        $campaign['emails_sent'] = '-';
                                        $summary['unique_opens'] = '-';
                                        $summary['clicks']	 = '-';
                                        $onClick = '';
                                    } else if ($campaign['type'] == 'auto'){
                                        $campaign['status'] = 'Autoresponder';
                                        $campaign['send_time'] = JText::_('JM_VARIABLE');
                                        $summary = $this->getModel()->getCampaignStats($campaign['id']);
                                        $onClick = '';
                                    } else {
                                        $summary = $this->getModel()->getCampaignStats($campaign['id']);
                                        $onClick = 'onclick="window.location=\'index.php?option=com_joomailermailchimpintegration&view=campaigns&active='.$i.'\'"  style="cursor: pointer"';
                                    }
                                    // convert time to locale timezone (set in Joomla config)
                                    if ($campaign['type'] != 'auto'){
                                        $config = JFactory::getConfig();
                                        $campaign['send_time'] = JHTML::date($campaign['send_time'], "Y-m-d H:i:s", $config->get('offset'));

                                    } ?>
                                <tr class="<?php echo "row$k"; ?>" <?php /*echo $onClick*/ ; ?>>
                                    <td><input type="radio" name="cid" value="<?php echo $campaign['id'];?>" onclick="document.adminForm2.boxchecked.value = 1;"/></td>
                                    <td align="center"><?php echo (strlen($campaign['title'])>30) ? substr($campaign['title'], 0, 27).'...' : $campaign['title']; ?></td>
                                    <td align="center"><?php echo (strlen($campaign['subject'])>30) ? substr($campaign['subject'], 0, 27).'...' : $campaign['subject']; ?></td>
                                    <td align="center"><?php echo ($campaign['status']=='save')?JText::_('JM_SAVED'):JText::_($campaign['status']); ?></td>
                                    <td align="center"><?php echo $campaign['send_time']; ?></td>
                                    <td align="center"><?php echo $campaign['emails_sent']; ?></td>
                                    <td align="center"><?php echo $summary['unique_opens']; ?></td>
                                    <td align="center"><?php echo $summary['clicks']; ?></td>
                                    <td align="center"><a class="modal" rel="{handler: 'iframe', size: {x: 980, y: 550} }" href="<?php echo $campaign['archive_url'];?>">
                                        <img src="../media/com_joomailermailchimpintegration/backend/images/preview_32.png" alt="Preview" title="Preview" height="17"/></a>
                                    </td>
                                    <td align="center"><a class="modal" rel="{handler: 'iframe', size: {x: 200, y: 200} }" href="<?php echo 'index.php?option=com_joomailermailchimpintegration&view=share&format=raw&url='.$campaign['archive_url'].'&title='.$campaign['title'];?>">
                                        <img src="../media/com_joomailermailchimpintegration/backend/images/share.png" alt="Share" title="Share" height="17"/></a>
                                    </td>
                                </tr>
                                <?php
                                    $k = 1 - $k;
                                    $i++;
                                }
                            } ?>
                    </table>

                    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
                    <input type="hidden" name="task" value="send" />
                    <input type="hidden" name="boxchecked" value="" />
                    <input type="hidden" name="controller" value="main" />
                </form><?php
                if (JOOMLAMAILER_MANAGE_CAMPAIGNS) { ?>
                    <div class="moreCampaigns">
                        <a href="index.php?option=com_joomailermailchimpintegration&view=campaignlist&filter_status=sent"><?php echo JText::_('JM_MORE_CAMPAIGNS');?></a>
                    </div><?php
                }
            } ?>
        </td>
        <td width="300" valign="top" id="info">
            <div class="buyCredits">
                <a href="https://<?php echo $dc;?>.admin.mailchimp.com/account/" class="JMbuttonOrange" target="_blank"><?php echo JText::_('JM_BUY_CREDITS');?></a>
            </div>
            <div id="accountDetailTab"><?php
                echo JHtml::_('bootstrap.startTabSet', 'accountDetails', array('active' => 'details'));
                echo JHtml::_('bootstrap.addTab', 'accountDetails', 'details', JText::_('JM_ACCOUNT_DETAILS', true)); ?>
                <div class="tabContent">
                    <table cellspacing="0">
                        <tr>
                            <td><?php echo JText::_('JGLOBAL_USERNAME'); ?>:</td>
                            <td><?php echo $this->details['username']; ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('JM_PLAN'); ?>:</td>
                            <td><?php echo JText::_('JM_' . $this->details['plan_type']);
                                if ($this->details['plan_type'] == 'free') {
                                    echo ' (<a href="http://mailchimp.com/pricing/" class="modal" rel="{handler: \'iframe\', size: {x: 980, y: 550} }" >'.JText::_('JM_UPGRADE').'</a>)';
                                }
                            ?></td>
                        </tr>
                        <tr>
                            <td><?php echo JText::_('JM_EMAILS_LEFT'); ?>:</td>
                            <td><?php echo $this->details['emails_left']; ?></td>
                        </tr>

                        <tr>
                            <td style="border-bottom: 0px solid #C6C6C6;"><a href="http://www.joomlamailer.com/support.html" target="_blank">joomlamailer <?php echo JText::_('JM_SUPPORT');?></a></td>
                            <td style="border-bottom: 0px solid #C6C6C6;"><a href="http://kb.mailchimp.com" target="_blank">MailChimp <?php echo JText::_('JM_SUPPORT');?></a></td>
                        </tr>
                    </table>
                </div> <?php
                echo JHtml::_('bootstrap.endTab');
                echo JHtml::_('bootstrap.endTabSet'); ?>
            </div>
            <div style="clear:both;"></div>
            <div id="chimpChatter">
                <h3 class="left"><?php echo JText::_('JM_CHIMP_CHATTER'); ?></h3>
                <div class="rss right"><a href="http://www.mailchimp.com/blog/feed" target="_blank"><?php echo JText::_('JM_RSS_FEED');?></a></div>
                <div style="clear:both;"></div>
                <ul>
                    <?php
                        if (isset($this->chimpChatter[0])){
                            foreach($this->chimpChatter as $index => $chatter){
                                echo '<li class="left">' . $chatter['message'] . '<span class="small right">'
                                    . date('Y-m-d H:i', strtotime($chatter['update_time'])) . '<span></li>';
                                if ($index >= 4) { break; }
                            }
                        }
                    ?>
                </ul>
            </div>
        </td>
    </tr>
</table>
