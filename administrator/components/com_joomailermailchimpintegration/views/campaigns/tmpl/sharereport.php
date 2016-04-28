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

$document = JFactory::getDocument();
$script = 'var uploadButtonText = "' . JText::_('JM_UPLOAD_HEADER_IMAGE') . '";';
$document->addScriptDeclaration($script); ?>

<script type="text/javascript">
    Joomla.submitbutton = function(pressbutton) {
        if (pressbutton == 'goToCampaigns') {
            Joomla.submitform(pressbutton);
        } else if (!checkEmail($j('email').val())) {
            alert('<?php echo JText::_('JM_INVALID_EMAIL'); ?>');
            $j('#email').val('');
            $j('#email').focus();
        } else {
            joomlamailerJS.functions.preloader();
            Joomla.submitform(pressbutton);
        }
    }

    function checkEmail(email) {
        if (email == '') {
            return false;
        } else {
            var pattern = /^([a-zA-Z0-9_\.\-\+])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
            return pattern.test(email);
        }
    }
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <h3 style="margin:0 0 1em 0;"><?php echo $this->name;?></h3>
    <div id="selectCampaign" style="margin:0 0 1em 0;">
        <h3 style="margin:0;float:left;"><?php echo JText::_('JM_DIRECT_LINK');?>:</h3>
        <span id="directLink"><a href="<?php echo $this->data['secure_url'];?>" target="_blank"><?php echo $this->data['url'];?></a></span>
    </div>

    <div id="shareReport">
        <div id="shareReportTitle">
            <h3><?php echo JText::_('JM_SEND_REPORT');?></h3>
        </div>
        <div id="shareReportTable">
            <table>
                <tr>
                    <td align="right" nowrap="nowrap"><label for="title"><?php echo JText::_('JM_PAGE_TITLE');?>:</label></td>
                    <td><input type="text" name="title" id="title" value="<?php echo JText::_('JM_CAMPAIGN_REPORT').': '.$this->name;?>" size="30" onfocus="if (this.value=='<?php echo 'Campaign Report: '.$this->name;?>'){this.value='';}" onblur="if (this.value==''){this.value='<?php echo 'Campaign Report: '.$this->name;?>';}" /></td>
                </tr>
                <tr>
                    <td align="right" nowrap="nowrap"><label for="email"><?php echo JText::_('JM_SEND_TO');?>:</label></td>
                    <td><input type="text" name="email" id="email" value="" size="30" onchange="validateEmail(this.value)" /></td>
                </tr><?php /*
                <tr>
                    <td align="right" nowrap="nowrap"><label for="password"><?php echo JText::_('JM_PASSWORD');?>:</label></td>
                    <td><input type="text" name="password" id="password" value="<?php echo $this->data['password']; ?>" size="50" /></td>
                </tr>*/ ?>
                <tr>
                    <td align="right" nowrap="nowrap"><label for="css"><?php echo JText::_('JM_ADDITIONAL_CSS');?></label></td>
                    <td>
                        <input type="text" name="css" id="css" value="" />
                        <br />
                        <span class="small"<?php echo JText::_('JM_CSS_URL_INFO');?></span>
                    </td>
                </tr>
                <tr>
                    <td></td>
                    <td>
                        <div class="sendOptionsButton" style="float: none;">
                            <a id="sendNowButton" href="javascript: Joomla.submitbutton('sendShareReport');" class="JMbuttonOrange"><?php echo JText::_('JM_SEND'); ?></a>
                        </div>
                    </td>
                </tr>
            </table>
        </div>
        <div style="clear: both;"></div>
    </div>

    <input type="hidden" name="cid" id="cid" value="<?php echo JRequest::getVar('cid', '', 'get', 'string');?>" />
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="1" />
    <input type="hidden" name="controller" value="campaigns" />
</form>
<div id="reportPreview">
    <iframe src="<?php echo $this->data['secure_url'];?>" width="100%" height="800"></iframe>
</div>
