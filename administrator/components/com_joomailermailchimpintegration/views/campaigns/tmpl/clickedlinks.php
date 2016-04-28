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
foreach($clientDetails['modules'] as $mod){
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
                    <?php echo JText::_('JM_LINK_URL'); ?>
                </th>
                <th width="100" nowrap="nowrap">
                    <?php echo JText::_('JM_TOTAL_CLICKS'); ?>
                </th>
                <th width="100" nowrap="nowrap">
                    <?php echo JText::_('JM_UNIQUE_CLICKS'); ?>
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
        $k = 0;
        $i = 0;
        $x = 0;
        foreach ($this->clicked as $key => $value) {
            if ($x < ($this->limitstart + $this->limit) && $x >= $this->limitstart){
                $link = 'index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=clickedlinkdetails&cid=' . $cid . '&url=' . urlencode(htmlentities(urlencode($key))); ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center">
                        <?php echo $i+1+$this->limitstart; ?>
                    </td>
                    <td>
                        <?php if ($AIM){ echo '<a href="' . $link . '">';}?>
                        <?php echo $key; ?>
                        <?php if ($AIM){ echo '</a>';}?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $value['clicks']; ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $value['unique']; ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
                $i++;
            }
            $x++;
        }
        ?>
    </table>
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="campaigns" />
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />
    <input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid');?>" />
</form>
