<?php
/**
* @copyright   (C) 2010 iJoomla, Inc. - All rights reserved.
* @license  GNU General Public License, version 2 (http://www.gnu.org/licenses/gpl-2.0.html) 
* @author  iJoomla.com webmaster@ijoomla.com
* @url   http://www.ijoomla.com/licensing/
* the PHP code portions are distributed under the GPL license. If not otherwise stated, all images, manuals, cascading style sheets, and included JavaScript  
* are NOT GPL, and are released under the IJOOMLA Proprietary Use License v1.0 
* More info at http://www.ijoomla.com/licensing/
*/

defined('_JEXEC') or die('Restricted Access');
//JHtml::_('bootstrap.tooltip');
JHtml::_('behavior.modal');

include_once(JPATH_ROOT.DS."administrator".DS."components".DS."com_ijoomla_seo".DS."helpers".DS."meta.php");

$item=$this->items;

$ijseo_type_key = $this->params->ijseo_type_key;
$ijseo_allow_no = $this->params->ijseo_allow_no;

$ijseo_type_title = $this->params->ijseo_type_title;
$ijseo_allow_no2 = $this->params->ijseo_allow_no2;

$ijseo_type_desc = $this->params->ijseo_type_desc;
$ijseo_allow_desc = $this->params->ijseo_allow_no_desc;

$meta = new Meta();

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$app = JFactory::getApplication('administrator');
$limistart = $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
$limit = $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');

?>

<script type="text/javascript">
/*window.addEvent('domready', function () {
	if (document.getElementById('mtree').value == 0) {
	  document.getElementById('mtree').value = 1;
	  document.adminForm.submit();
	}
});*/
Joomla.submitbutton = function (task){
	if(task == "apply" || task == "save"){
		var form=document.adminForm;
		form.toggle.checked=1;
		checkAll('<?php echo $this->state->get('list.limit'); ?>','cb');
		form.task.value=task;		
	}	
	submitform(task);
}
</script>

<form action="index.php" method="post" name="adminForm" id="adminForm">
	<table width="100%">
		<tr>
			<td width="40%">
				<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SEO_METATAGS"); ?></span>
			</td>
			<td width="60%" align="right">
                <a class="modal seo_video_meta"  rel="{handler: 'iframe', size: {x: 740, y: 425}}" style="font-size: 16px;" target="_blank"
                        href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=28774680">
                    <img src="<?php echo JURI::base(); ?>components/com_ijoomla_seo/images/icon_video.gif" class="video_img" />
                    <?php echo JText::_("COM_IJOOMLA_SEO_WHY_NOT_SHOW"); ?>
                </a>
				&nbsp;&nbsp;
				<span style="color:#FF0000; font-size:16px;"><?php echo JText::_('COM_IJOOMLA_SEO_MUST_WATCH'); ?></span>
			</td>
		</tr>
		<tr>
			<td width="40%" class="description_zone">
				<?php echo JText::_("COM_IJOOMLA_SEO_METATAGS_DESCRIPTION"); ?>
			</td>
			<td width="60%" class="filter_zone">
				<table width="100%">
					<tr>
						<td align="right">
							<table>
								<tr>
									<td align="right">
										<span class="title_page"><?php echo JText::_("COM_IJOOMLA_SEO_SELECTITEMS_TO_EDIT"); ?></span>
										<img alt="arrow" src="components/com_ijoomla_seo/images/redarrow.png" style="vertical-align:middle;">
										<?php echo $meta->getList(); ?>
										<?php echo $meta->createOptions(); ?>
									</td>
								</tr>
							</table>		
						</td>
					</tr>
					<tr>
						<td align="right">							
							<?php
							echo $this->createCriterias();
							?>
						</td>
					</tr>
					<tr>
						<td align="right" colspan="2">
							<?php
								 $search = JRequest::getVar("search", "");
							?>
							<?php echo JText::_("COM_IJOOMLA_SEO_FILTER"); ?>: <input type="text" name="search" value="<?php echo $search;?>" class="text_area" onChange="document.adminForm.submit();" />&nbsp;<input type="button" onclick="this.form.submit();" value="<?php echo JText::_("COM_IJOOMLA_SEO_GO"); ?>" />
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
    <br />
	<p>
    <a href="index.php?option=com_ijoomla_seo&controller=about&task=vimeo&id=13155551"
        class="modal seo_video" rel="{handler: 'iframe', size: {x: 740, y: 425}}">
        <img src="components/com_ijoomla_seo/images/icon_video.gif">
        <?php echo JText::_('COM_IJOOMLA_SEO_HOWTO_METATAGS_VIDEO'); ?>
    </a>
    </p>
			
	<table width="100%" cellspacing="0" cellpadding="0" border="0" class="adminlist">
		<tr>
			<th align="center"><?php echo JText::_('#'); ?></th>
			<th align="center"><input type="checkbox" name="toggle" value="" onclick="checkAll(this)"/></th>			
			<th align="center"><?php echo JText::_("COM_IJOOMLA_SEO_ITEM_TITLE") ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_VIEW'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_TITLE_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_KEYWORDS_METATAG'); ?></th>
			<th align="center"><?php echo JText::_('COM_IJOOMLA_SEO_DESCRIPTIONS_METATAG'); ?></th>			
		</tr>
		<?php
		$app		= JFactory::getApplication('administrator');
		$limistart	= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.start', 'limitstart');
		$limit		= $app->getUserStateFromRequest('com_ijoomla_seo.articles'.'.list.limit', 'limit');
		$k = $limistart+1;
		$mtree_type = JRequest::getVar("mtree", "0");
				
		for($i=0;$i<count($this->items);$i++){			
			$item=$this->items[$i];
			$element_name = "";
			$element_id = "";
			if($mtree_type == "1"){
				$element_name = $item->link_name;
				$element_id = $item->link_id;
			}
			elseif($mtree_type == "2"){
				$element_name = $item->cat_name;
				$element_id = $item->cat_id;
			}
			$page_title = $element_name;
			if(isset($item->titletag)){
				$page_title = trim($item->titletag);
			}
		?>
			<tr class="row<?php echo $i%2; ?>">
				<td align="center">
					<?php echo $k;?>
				</td>				
				<td align="center">
					<?php echo JHtml::_('grid.id', $i, $element_id); ?>
				</td>				
				<td>
					<?php echo $element_name; ?>
				</td>
				<td>
					<a href="index.php?option=com_ijoomla_seo&controller=preview&id=<?php echo $element_id; ?>&tmpl=component&task=article_preview" class="modal" rel="{handler: 'iframe', size: {x: 640, y: 480}}"><?php echo "view"; ?></a>
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_title=="Words"){
						$var = explode(' ', trim($page_title));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_no2 - $num;
					}
					else{
						$var = strlen($page_title);
						if(isset($ijseo_allow_no2)){
							$do = $ijseo_allow_no2 - $var;
						}	
				 	}
					?>					
					<textarea id="<?php echo "metatitle".$element_id; ?>" name="page_title[<?php echo $element_id; ?>]" onkeyup="javascript: countTitle(this, this.value, '<?php echo $element_id; ?>', '<?php echo $ijseo_type_title; ?>', '<?php echo $ijseo_allow_no2; ?>');" rows="4"><?php echo $page_title; ?></textarea>
					<span id="go_<?php echo $element_id; ?>" name="go_<?php echo $element_id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$element_id.', "go", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$element_id.', "go", "#666666") </script>';
					}	
					if(!empty($page_title)){
						echo '<script type="text/javascript">
								unColor("metatitle['.$element_id.']");
							</script>';
					}				
					?>				
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_key=="Words"){
						$var = explode(' ', trim($item->metakey));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$no = $ijseo_allow_no - $num;
					}
					else{
						$var = strlen(trim($item->metakey));						
						if(isset($ijseo_allow_no)){
							$no = $ijseo_allow_no - $var;
						}	
				 	}					
					?>
					<textarea id="<?php echo "metakey".$element_id; ?>" name="metakey[<?php echo $element_id; ?>]" onkeyup="javascript: countKey(this, this.value, '<?php echo $element_id; ?>', '<?php echo $ijseo_type_key; ?>', '<?php echo $ijseo_allow_no; ?>');" rows="4"><?php echo $item->metakey; ?></textarea>
					<span id="no_<?php echo $element_id; ?>" name="no_<?php echo $element_id; ?>"><?php if(isset($no)) echo $no; ?></span>
					<?php
					if(isset($no) &&($no < 0)){
						echo '<script type="text/javascript"> changeColor('.$element_id.', "no", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$element_id.', "no", "#666666") </script>';
					}	
					if(!empty($item->metakey)){
						echo '<script type="text/javascript">
								unColor("metakey['.$element_id.']");
							</script>';
					}				
					?>
				</td>
				<td valign="top" align="center">
					<?php
					if ($ijseo_type_desc=="Words"){
						$var = explode(' ', trim($item->metadesc));
						$num = count($var);
						if($var[$num-1] == ""){
							unset($var[$num-1]);
						}	
						$num = count($var);
						$do = $ijseo_allow_desc - $num;
					}
					else{
						$var = strlen($item->metadesc);
						if(isset($ijseo_allow_desc)){
							$do = $ijseo_allow_desc - $var;
						}	
				 	}
					?>
					<textarea id="<?php echo "metadesc".$element_id; ?>" name="metadesc[<?php echo $element_id; ?>]" onkeyup="javascript: countDesc(this, this.value, '<?php echo $element_id; ?>', '<?php echo $ijseo_type_desc; ?>', '<?php echo $ijseo_allow_desc; ?>');" rows="4"><?php echo $item->metadesc; ?></textarea>
					<span id="do_<?php echo $element_id; ?>" name="do_<?php echo $element_id; ?>"><?php if(isset($do)) echo $do; ?></span>
					<?php
					if(isset($do) &&($do < 0)){
						echo '<script type="text/javascript"> changeColor('.$element_id.', "do", "red") </script>';
					}	
					else{ 
						echo '<script type="text/javascript"> changeColor('.$element_id.', "do", "#666666") </script>';
					}	
					if(!empty($item->metadesc)){
						echo '<script type="text/javascript">
								unColor("metadesc['.$element_id.']");
							</script>';
					}				
					?>					
				</td>
			</tr>			
		<?php
			$k++;
		}
		?>
		<tfoot>
		<tr>
			<td colspan="7">
				<?php echo $this->pagination->getListFooter(); ?>
			</td>
		</tr>
		</tfoot>
	</table>
	
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="mtree" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<?php echo JHtml::_('form.token'); ?>
</form>