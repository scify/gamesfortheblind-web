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
defined('_JEXEC') or die('Restricted Access'); ?>

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
                    <?php echo JText::_('JM_OPENED'); ?>
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
        $k = $this->limitstart;
        $x = $y = 0;
        for ($i = 0; $i < count($this->items); $i++) {
            if ($x < ($this->limitstart + $this->limit) && $x >= $this->limitstart) {
                $row = $this->items[$i];
                $user = $this->getModel()->getUserDetails($row['email']); ?>
                <tr class="<?php echo "row$k"; ?>">
                    <td align="center">
                        <?php echo $y + 1 + $this->limitstart; ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php if ($user) { ?>
                            <a href="index.php?option=com_joomailermailchimpintegration&view=subscriber&uid=<?php echo $user->id; ?>&email=<?php echo $row['email']; ?>">
                                <?php echo $user->name; ?>
                            </a>
                        <?php } else {
                            echo JText::_('JM_UNREGISTERED_USER');
                        } ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $row['email']; ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo $row['open_count']; ?>
                    </td>
                    <td align="center" nowrap="nowrap">
                        <?php echo ($user) ? $user->id : '-'; ?>
                    </td>
                </tr>
                <?php
                $k = 1 - $k;
                $y++;
            }
            $x++;
        } ?>
    </table>
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="campaigns" />
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />
    <input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid');?>" />
</form>
