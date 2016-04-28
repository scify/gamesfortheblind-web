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
                <th width="5">#</th>
                <th width="150" nowrap="nowrap">
                    <?php echo JText::_('JM_DATE'); ?>
                </th>
                <th style="text-align:left" nowrap="nowrap">
                    <?php echo JText::_('JM_EMAIL_ADDRESS'); ?>
                </th>
                <th width="150" nowrap="nowrap">
                    <?php echo JText::_('JM_TYPE'); ?>
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
        $db = JFactory::getDBO();
        $k = $this->limitstart;
        for ($i = 0; $i < count($this->items); $i++) {
            if ($i >= $this->limit || !isset($this->items[$i + $this->limitstart])) {
                break;
            }
            $row = $this->items[$i + $this->limitstart];
            $query = $db->getQuery(true)
                ->select($db->qn('id'))
                ->from('#__users')
                ->where($db->qn('email') . ' = ' . $db->q($row['email']));
            $db->setQuery($query);
            $id = $db->loadResult(); ?>
            <tr class="<?php echo "row$k"; ?>">
                <td align="center">
                    <?php echo ($i + 1 + $this->limitstart);?>
                </td>
                <td align="center" nowrap="nowrap">
                    <?php echo $row['date']; ?>
                </td>
                <td>
                    <a href="index.php?option=com_joomailermailchimpintegration&view=subscriber&uid=<?php echo $id; ?>&email=<?php echo $row['email']; ?>">
                        <?php echo $row['email']; ?>
                    </a>
                </td>
                <td align="center" nowrap="nowrap">
                    <?php echo $row['type']; ?>
                </td>
            </tr>
            <?php
            $k = 1 - $k;
        } ?>
    </table>
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="campaigns" />
    <input type="hidden" name="layout" value="<?php echo JRequest::getVar('layout');?>" />
    <input type="hidden" name="cid" value="<?php echo JRequest::getVar('cid');?>" />
</form>
