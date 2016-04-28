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

$document =& JFactory::getDocument();
$model	  =& $this->getModel();
//print_r($this->items);
$document->addScript(JURI::base()."components/com_joomailermailchimpintegration/assets/js/sync.js");
?>
<script type="text/javascript">
<?php if (version_compare(JVERSION,'1.6.0','ge')){ ?>
Joomla.submitbutton = function(pressbutton) {
<?php } else { ?>
function submitbutton(pressbutton) {
<?php } ?>
  if (document.adminForm.listid.value == ""){
        alert('<?php echo JText::_('JM_SELECT_A_LIST_TO_ASSIGN_THE_USERS_TO');?>');
        document.adminForm.listid.style.border = "1px solid #ff0000";
  } else if (pressbutton=='sync_all'){
	  if (confirm('<?php echo JText::_('Are you sure to add all users');?>')){
//		  submitform(pressbutton);
			AjaxAddAll(0);
	  }
  } else {
//	submitform(pressbutton);
	AjaxAddLeads();
    //    run();
  }
}

var baseUrl = '<?php echo JURI::base();?>';

function AJAXinit(total) {
	var progressBar = '<div id="bg"></div>'
					+'<div style="background:#FFFFFF none repeat scroll 0 0;border:10px solid #000000;height:100px;left:37%;position:relative;text-align:center;top:37%;width:300px; ">'
					+'<div style="margin: 35px auto 3px; width: 300px; text-align: center;"><?php echo JText::_('adding users');?> (0/'+total+' <?php echo JText::_('done');?>)</div>'
					+'<div style="margin: auto; background: transparent url(<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/progress_bar_grey.gif);  width: 190px; height: 14px; display: block;">'
					+'<div style="width: 0%; overflow: hidden;">'
					+'<img src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/progress_bar.gif" style="margin: 0 5px 0 0;"/>'
					+'</div>'
					+'<div style="width: 190px; text-align: center; position: relative;top:-13px; font-weight:bold;">0 %</div>'
					+'</div>'
					+'</div>';
	
	$('ajax_response').style.display = 'block';
	$('ajax_response').setHTML(progressBar);
}

function AJAXsuccess(message) {
	var messageBlock =	'<dl id="system-message">'
						+'<dt class="message">Message</dt>'
						+'	<dd class="message message fade">'
						+'		<ul>'
						+'			<li style="text-indent:0; padding-left: 30px;">'+message+'</li>'
						+'		</ul>'
						+'	</dd>'
						+'</dl>';
	$('message').style.display = 'block';
	$('message').setHTML(messageBlock);
}

function noListSelected(){
	alert('<?php echo JText::_('JM_SELECT_A_LIST_TO_ASSIGN_THE_USERS_TO');?>');
	document.adminForm.listid.style.border = "1px solid #ff0000";
}
function noUsersSelected(){
	alert('<?php echo JText::_('No Users selected');?>');
}
</script>

<h1><?php echo JRequest::getVar('name').' ('.JRequest::getVar('subject').')';?></h1>

<div id="ajax_response" style="display: none"></div>
<div id="message" style="display: none"></div>

<form action="index.php?option=com_joomailermailchimpintegration&view=campaigns" method="post" name="adminForm" id="adminForm">
<div id="editcell">

			<select name="listid" id="listid" onchange="javascript:this.style.border=''; markAdded2(this.value); " style="float: left;">
				  <option value=""><?php echo JText::_('Select a list to assign the users to'); ?></option>
				  <?php
				   foreach ($this->lists['anyType']['List'] as $list){
				              ?>
				              <option value="<?php echo $list['ListID'];?>"><?php echo $list['Name'];?></option>
				              <?php
				   }
				  ?>
			</select>
			<div id="addUsersLoader" style="visibility:hidden;">
					<img src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/loader_16.gif" style="margin: 0 0 0 10px;"/>
			</div>
			<div style="clear:both;"></div>
	<table class="adminlist">
	<thead>
		<tr>
			<th width="20" align="center">#</th>
			<th width="20"><input type="checkbox" name="toggle" value="" onclick="checkAll(<?php echo count($this->items); ?>);" /></th>
            <th nowrap="nowrap">
				<?php echo JText::_('Name').' ('.JText::_('Username').')'; ?>
			</th>
            <th width="125" nowrap="nowrap">
				<?php echo JText::_('Email'); ?>
			</th>
            <th width="100" nowrap="nowrap">
				<?php echo JText::_('Clicked Links'); ?>
			</th>
            <th width="100" nowrap="nowrap">
				<?php echo JText::_('Total Clicks'); ?>
			</th>
			<th nowrap="nowrap">
				<?php echo JText::_('Clicked Links'); ?>
			</th>
		</tr>
	</thead>
	
<?php
	$k = 0;
	$togglerScript = '';
	for ($i=0, $n=count($this->items); $i < $n; $i++)
	{
		$row =& $this->items[$i];
		$userdata =& $model->getUserdata($row->email);
/*		
		if (!$userdata){
			$uData =& $model->getUserdataAPI($row->email);
			$userdata->username = false;
			$userdata->name = $uData['anyType']['Name'];
		}
		* */
		$checked  = JHTML::_('grid.id',   $i, $row->email);
		/*
		// make sure the array looks the same with one entry as with several entries
		if (!isset($row['ClickedLinks']['SubscriberClickedLink'][0]) && isset($row['ClickedLinks']['SubscriberClickedLink'])) {
			$data = $row['ClickedLinks']['SubscriberClickedLink'];
			$row['ClickedLinks']['SubscriberClickedLink'] = '';
			$row['ClickedLinks']['SubscriberClickedLink'][0] = $data;
		}
		
		$totalClicks = 0;
		foreach($row['ClickedLinks']['SubscriberClickedLink'] as $clicks){
			$totalClicks += $clicks['Clicks'];
		}
		*/
		$totalClicks = $row->clicks;
		/*
		$togglerScript .= "var toggler".$userdata->id." = new Fx.Slide('details_".$userdata->id."');\n".
							"$('row".$userdata->id."').addEvent('click', function(e){\n".
								"toggler".$userdata->id.".toggle();\n".
								"});";
		*/
?>
		<tr class="<?php echo "row$k"; ?>" id="row_<?php echo $userdata->email;?>">
			<td align="center"><?php echo (isset($userdata->id))?$userdata->id:'-';?></td>
			<td align="center">
				<?php echo $checked;
				/*
				<input type="checkbox" name="cid[]" value="<?php echo $userdata->id;?>" onclick="isChecked(this.checked);"/>
				*/ 
				?>
			</td>
			<td><?php if (isset($userdata->name) && $userdata->username){
							echo $userdata->name. ' ('.$userdata->username.')';
						} else if (isset($userdata->name) && !$userdata->username){
							echo $userdata->name;
						} else {
							echo '-';
						}
							
							
				?></td>
			<td align="center"><?php echo $row->email;?></td>
			<td align="center"><?php echo $row->clickedLinks;?></td>
			<td align="center"><?php echo $totalClicks;?></td>
			<td>
				<?php 
				foreach($row->links as $link){
					echo '<a href="'.$link.'" target="_blank">'.$link.'</a><br />';
				}
				?>
			</td>
		</tr>
		
<?php
	}
/*
	$document->addCustomTag("<script language=\"javascript\" type=\"text/javascript\">".
							"window.addEvent('domready', function() {\n".
							$togglerScript.
							"});</script>");
*/
?>
</table>
</div>

<input type="hidden" name="option" value="com_joomailermailchimpintegration" />
<input type="hidden" name="task" value="" />
<input type="hidden" name="boxchecked" id="boxchecked" value="0" />
<input type="hidden" name="controller" value="sync" />
<input type="hidden" name="type" value="sync" />
<input type="hidden" name="total" id="total" value="<?php echo count($this->items);?>" />
</form>
