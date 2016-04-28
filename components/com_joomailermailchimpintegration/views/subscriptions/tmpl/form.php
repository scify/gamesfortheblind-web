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
<script language="javascript" type="text/javascript">
Joomla.submitbutton = function(pressbutton) {
	Joomla.submitform(pressbutton);
}
</script>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <h2 class="componentheading">
        <?php echo ($this->menuParams->get('show_page_heading') && $this->menuParams->get('page_heading'))
            ? $this->menuParams->get('page_heading') : JText::_('JM_CAMPAIGN_ARCHIVE'); ?>
    </h2>
	<table class="admintable">
        <colgroup>
            <col width="" />
            <col width="150" />
            <col width="150" />
        </colgroup><?php
        foreach ($this->lists as $list) {
            $isSub = $this->getModel()->getIsSubscribed($list['id'], $this->user->email); ?>
            <tr>
                <td align="left" class="key" style="padding: 0 15px 0 0;">
                    <label for="listid"><?php echo $list['name']; ?></label>
                </td>
                <td style="padding: 0 15px 0 0;">
                    <input type="hidden" name="isSub[<?php echo $list['id']; ?>]" value="<?php echo ($isSub) ? 1 : 0; ?>" />

                    <label for="lists_<?php echo $list['id']; ?>_yes">
                        <?php echo JText::_('JM_SUBSCRIBE'); ?>:
                        <input type="radio" name="lists[<?php echo $list['id']; ?>]"<?php echo ($isSub) ? 'checked="checked"' : ''; ?> value="1" id="lists_<?php echo $list['id']; ?>_yes" />
                    </label>
                </td>
                <td>
                    <label for="lists_<?php echo $list['id']; ?>_no">
                        <?php echo JText::_('JM_UNSUBSCRIBE'); ?>:
                        <input type="radio" name="lists[<?php echo $list['id']; ?>]"<?php echo ($isSub) ? '' : 'checked="checked"'; ?>  value="0" id="lists_<?php echo $list['id']; ?>_no" />
                    </label>
                </td>
            </tr><?php
        } ?>
	</table>
	<br />
    <button type="button" onclick="Joomla.submitbutton('save')"><?php echo JText::_('JM_SAVE') ?></button>
    <button type="button" onclick="Joomla.submitbutton('cancel')"><?php echo JText::_('JM_CANCEL') ?></button>

    <input type="hidden" name="itemid" value="<?php echo JRequest::getVar('Itemid', 0, 'get', 'int');?>" />
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="save" />
    <input type="hidden" name="controller" value="" />
    <?php echo JHTML::_('form.token'); ?>
</form>
