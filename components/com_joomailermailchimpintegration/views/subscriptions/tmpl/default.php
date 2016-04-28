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

<h2 class="componentheading">
    <?php echo ($this->menuParams->get('show_page_heading') && $this->menuParams->get('page_heading'))
        ? $this->menuParams->get('page_heading') : JText::_('JM_CAMPAIGN_ARCHIVE'); ?>
</h2>
<table>
    <tr>
        <th align="left">
            <?php echo JText::_('JM_LIST_NAME'); ?>
        </th>
        <th>
            <?php echo JText::_('JM_SUBSCRIBED'); ?>
        </th>
    </tr><?php
    foreach ($this->lists as $list) {
        $isSub = $this->getModel()->getIsSubscribed($list['id'], $this->user->email); ?>
        <tr>
            <td><?php echo $list['name']; ?></td>
            <td align="center">
                <?php echo ($isSub) ? JText::_('JM_YES') : JText::_('JM_NO'); ?>
            </td>
        </tr><?php
    } ?>
</table>
<br />
<a href="<?php echo $this->editlink; ?>">
    <?php echo JText::_('JM_EDIT_SUBSCRIPTIONS'); ?>
</a>
