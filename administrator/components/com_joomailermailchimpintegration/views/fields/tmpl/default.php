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
    <?php
    if (!$this->fields) {
        echo '<div style="margin: 1em;">'.JText::_('JM_NO_CUSTOM_MERGE_FIELDS').'</div>';
    } else { ?>
        <div id="editcell">
            <table class="adminlist">
                <thead>
                    <tr>
                        <th width="5" align="center">#</th>
                        <th width="20" align="center">
                            <input type="checkbox" name="toggle" value="" onclick="Joomla.checkAll(this)" />
                        </th>
                        <th>
                            <?php echo JText::_('JM_MERGE_FIELD_NAME'); ?>
                        </th>
                        <th width="230">
                            <?php echo JText::_('JM_TYPE'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('JM_REQUIRED'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('JM_TAG'); ?>
                        </th>
                        <th>
                            <?php echo JText::_('JM_ORDER'); ?>
                        </th>
                    </tr>
                </thead>
                <?php
                $k = 0;
                for ($i = 0, $n = count($this->fields); $i < $n; $i++) {
                    $row = $this->fields[$i];
                    $listid = JRequest::getVar('listid');
                    $choices = '';
                    if (isset($row['choices'])) {
                        foreach($row['choices'] as $c) {
                            $choices .= $c.'||';
                        }
                        $choices = substr($choices,0,-2);
                    }
                    $checkbox = JHTML::_('grid.id', $i, $row['name'] . ';' . $row['tag'] . ';' . $row['field_type'] .
                        ';' . $row['req'] . ';' . $choices); ?>
                    <tr class="<?php echo "row$k"; ?>">
                        <td align="center">
                            <?php echo $i+1; ?>
                        </td>
                        <td align="center">
                            <?php echo $checkbox;?>
                        </td>
                        <td>
                            <?php echo $row['name']; ?>
                        </td>
                        <td align="center">
                            <?php echo $row['field_type']; ?>
                        </td>
                        <td align="center">
                            <?php echo JText::_(($row['req'] ? 'JYES' : 'JNO')); ?>
                        </td>
                        <td align="center">
                            <?php echo $row['tag']; ?>
                        </td>
                        <td align="center">
                            <?php echo $row['order']; ?>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                }
                ?>
            </table>
        </div>

        <?php } // end if no lists created ?>

    <input type="hidden" name="listid" value="<?php echo JRequest::getVar('listid');?>" />
    <input type="hidden" name="listName" value="<?php echo $this->name;?>" />
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="fields" />
</form>
