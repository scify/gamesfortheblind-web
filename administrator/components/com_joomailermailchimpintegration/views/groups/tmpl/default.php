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
  if (pressbutton == 'remove'){
        if (confirm('<?php echo JText::_('JM_ARE_YOU_SURE_TO_DELETE_THIS_FIELD');?>')) {
			Joomla.submitform(pressbutton);
		}
  } else {
      Joomla.submitform(pressbutton);
  }
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
<?php
if ($this->fields) { ?>
<div id="editcell">
	<table class="adminlist">
	<thead>
		<tr>
			<th width="5" align="center">
				#
			</th>
			<th width="20" align="center">

				<input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->fields); ?>);" />

                <?php /*<input type="radio" name="key" value="" />*/ ?>
			</th>
            <th>
				<?php echo JText::_('JM_FIELD_NAME'); ?>
			</th>
            <th width="230">
				<?php echo JText::_('JM_DATA_TYPE'); ?>
			</th>
			<th>
				<?php echo JText::_('JM_OPTIONS'); ?>
			</th>
		</tr>
	</thead>
	<?php
	$k = 0;
	for ($i = 0, $n = count($this->fields); $i < $n; $i++) {
		$row = $this->fields[$i];
		$checked = JHTML::_('grid.id',   $i, $row['id']);
//		$link 		= JRoute::_('index.php?option=com_joomailermailchimpintegration&controller=joomailermailchimpintegration&task=edit&listid='. $row['Key']);
		$options = array();
		foreach($row['groups'] as $group){
			$options[] = $group['name'];
		}
		$options = implode(', ', $options);
//		$options = isset($row['groups']['name']) ? implode(', ', $row['groups']['name']) : '';
        (strlen($options) > 50) ? $options = substr($options, 0, 50) . ' ...' : '';

        switch($row['form_field']) {
			case 'checkboxes':
				$dataType = JText::_('JM_CHECKBOXES');
				break;
			case 'hidden':
				$dataType = JText::_('JM_HIDDEN_INPUT');
				break;
			case 'dropdown':
				$dataType = JText::_('JM_DROPDOWN_LIST');
				break;
			case 'radio':
				$dataType = JText::_('JM_RADIO_BUTTONS');
				break;
		} ?>
		<tr class="<?php echo "row$k"; ?>">
			<td align="center">
                <?php echo $i+1; ?>
			</td>
			<td align="center">
				<?php /*
                <input type="radio" name="id" id="cb<?php echo $i;?>" value="<?php echo $row['id']; ?>" onclick="isChecked(this.checked);"/>
                */ ?>
				<?php echo $checked;?>
			</td>
            <td>
<?php /*		<a href="<?php echo $link; ?>"><?php echo $row['FieldName']; ?></a> */ ?>
				<?php echo $row['name']; ?>
			</td>
            <td align="center">
                <?php echo $dataType; ?>
			</td>
			<td align="center">
                <?php echo $options; ?>
			</td>
		</tr>
		<?php
		$k = 1 - $k;
	} ?>
	</table>
</div>

<?php } // end if no lists created ?>

    <input type="hidden" name="listid" value="<?php echo JRequest::getVar('listid');?>" />
    <input type="hidden" name="listName" value="<?php echo $this->listName;?>" />
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="groups" />
</form>
