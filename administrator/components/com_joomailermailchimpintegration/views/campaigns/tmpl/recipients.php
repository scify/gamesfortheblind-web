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

$cid = JRequest::getVar('cid', '', 'get', 'string');
$AIM = false;
$clientDetails = $this->getModel('main')->getClientDetails();
foreach ($clientDetails['modules'] as $mod) {
    if ($mod['name'] == 'AIM Reports') {
        $AIM = true;
        break;
    }
} ?>
<form action="index.php?option=com_joomailermailchimpintegration&view=campaigns" method="post" name="adminForm" id="adminForm">
    <table class="adminlist">
        <thead>
            <tr>
                <th width="10">#</th>
                <th nowrap="nowrap">
                    <?php echo JText::_('JM_NAME'); ?>
                </th>
                <th nowrap="nowrap">
                    <?php echo JText::_('JM_EMAIL_ADDRESS'); ?>
                </th>
                <th width="100" nowrap="nowrap">
                    <?php echo JText::_('JM_TOTAL_TIMES_OPENED'); ?>
                </th>
                <th width="100" nowrap="nowrap">
                    <?php echo JText::_('JM_TOTAL_CLICKS'); ?>
                </th>
                <th width="20" nowrap="nowrap">
                    <?php echo JText::_('JM_ID'); ?>
                </th>
            </tr>
        </thead>

        <tfoot>
            <tr>
                <td colspan="15">
                    <?php echo $this->pagination->getListFooter(); ?>
                </td>
            </tr>
        </tfoot>
        <?php
        $k = $i = $x = 0;
        if ($this->clicked) {
            foreach($this->clicked as $key => $value) {
                $user = $this->getModel()->getUserDetails($key);
                $openCount  = 0;
                $clickCount = 0;
                foreach ($value as $v) {
                    if ($v['action'] == 'open') {
                        $openCount++;
                    } else if ($v['url']) {
                        $clickCount++;
                    }
                }
                $link = 'index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=clicked&cid=' . $cid . '&url=' . urlencode(htmlentities(urlencode($key))); ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center">
                        <?php echo $i + 1 + $this->limitstart; ?>
                    </td>
                    <td>
                        <?php if ($user) { ?>
                        <a href="index.php?option=com_joomailermailchimpintegration&view=subscriber&uid=<?php echo $user->id; ?>&email=<?php echo $key; ?>">
                            <?php echo $user->name; ?>
                        </a>
                        <?php } else {
                            echo JText::_('JM_UNREGISTERED_USER');
                        } ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $key; ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $openCount; ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $clickCount; ?>
                    </td>
                    <td align="center">
                        <?php echo ($user) ? $user->id : '-'; ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
                $i++;
            }
        } ?>
    </table>
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="campaigns" />
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />
    <input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid');?>" />
</form>
