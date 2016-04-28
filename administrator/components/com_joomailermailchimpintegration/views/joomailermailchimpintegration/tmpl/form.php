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

// populate list title
$list_title = (isset($this->joomailermailchimpintegration['anyType']['Title']))
? $this->joomailermailchimpintegration['anyType']['Title'] : '';
$list_id = (isset($this->joomailermailchimpintegration['anyType']['ListID']))
? $this->joomailermailchimpintegration['anyType']['ListID'] : '';
// preselect list type
if ($this->joomailermailchimpintegration['anyType']) {
    if (isset($this->joomailermailchimpintegration['anyType']['ConfirmOptIn'])
        && $this->joomailermailchimpintegration['anyType']['ConfirmOptIn'] == 'true') {
        $selected_0 = 'selected="selected"';
        $selected_1 = '';
    } else {
        $selected_0 = '';
        $selected_1 = 'selected="selected"';
    }
} ?>
<form action="index.php" method="post" name="adminForm" id="adminForm">
    <div class="col100">
        <fieldset class="adminform">
            <legend><?php echo JText::_('Details'); ?></legend>

            <table class="admintable">
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="name">
                            <?php echo JText::_('Name'); ?>:
                        </label>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td>
                                    <input class="text_area" type="text" name="name" id="name" size="48" maxlength="250" value="<?php echo $list_title;?>" />
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <tr>
                    <td width="100" align="right" class="key">
                        <label for="name">
                            <?php echo JText::_('List Type'); ?> :
                        </label>
                    </td>
                    <td>
                        <table>
                            <tr>
                                <td valign="top">
                                    <select name="type">
                                        <option <?php echo $selected_1; ?> value="1">Single opt-in (no confirmation required)</option>
                                        <option <?php echo $selected_0; ?> value="0">Confirmed opt-in (confirmation required)</option>
                                    </select>
                                </td>
                                <td><?php echo JText::_('Confirmed opt-in info');?></td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </fieldset>
    </div>
    <div class="clr"></div>

    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="id" value="<?php echo $list_id; ?>" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="controller" value="joomailermailchimpintegration" />
</form>
