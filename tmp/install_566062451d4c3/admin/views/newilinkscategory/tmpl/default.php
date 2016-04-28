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
JHtml::_('behavior.tooltip');

$document = JFactory::getDocument();
$document->addStyleSheet("components/com_ijoomla_seo/css/seostyle.css");
$document->addScript("components/com_ijoomla_seo/javascript/scripts.js");

$id = JRequest::getVar("id", "0", "get");
$name = "";
$published = "1";

if($id != "0"){
	$values = $this->getValues();
	$name = $values["0"]->name;
	$name = str_replace('"', "&quot;", $name);
	$published = $values["0"]->published;
}

?>

<script type="text/javascript">
	Joomla.submitbutton = function(pressbutton){
		var form = document.adminForm;		
		if (pressbutton == 'cancel') {
			submitform(pressbutton);
		}		
		else if(pressbutton == 'save' || pressbutton == 'apply') {
			if (form.name.value == ""){
				alert("<?php echo JText::_("COM_IJOOMLA_SEO_CAT_NAME"); ?>: <?php echo " ".JText::_("COM_IJOOMLA_SEO_IS_REQUIRED")."."; ?>");
			}			
			else{
				submitform( pressbutton );		
			}	
		}
		else{
			submitform( pressbutton );
		}
		
	}
</script>

<form class="form-horizontal" action="index.php" method="post" name="adminForm" id="adminForm">
	
    <?php
		if(intval($id) != "0"){
			echo '<h2 class="pub-page-title">'.JText::_("COM_IJOOMLA_SEO_KEY_CATEG_MANAGER")."&nbsp;:Edit&nbsp;[&nbsp;<small>".$name."</small>&nbsp;]"."</h2>";
		}
		else{
			echo '<h2 class="pub-page-title">'.JText::_("COM_IJOOMLA_SEO_KEY_CATEG_MANAGER").":&nbsp;New"."</h2>";
		}
	?>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_CAT_NAME"); ?>:<span style="color:#FF0000">*</span></label>
        <div class="controls">
        	<input type="text" name="name" value="<?php echo $name; ?>">
        	<span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_SEO_TOOLTIP_NAME_KEY_CATEG"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
			
        </div>
    </div>
    
    <div class="control-group">
        <label class="control-label"><?php echo JText::_("COM_IJOOMLA_SEO_CAT_PUBLISHED"); ?></label>
        <div class="controls">
        	<fieldset class="radio btn-group" id="jform_published">
                <?php
                    $no_checked = "";
                    $yes_cheched = "";
                    $display = "block";
                    
                    if($published == 0){
                        $no_checked = 'checked="checked"';
                        $display = "none";
                    }
                    else{
                        $yes_cheched = 'checked="checked"';
                    }
                ?>
                 <input type="hidden" name="published" value="0">
				 <input type="checkbox" <?php echo $yes_cheched; ?> value="1" name="published" class="ace-switch ace-switch-5">
				<span class="lbl"></span>
               
            </fieldset>
            <span class="editlinktip hasTip" title="<?php echo JText::_("COM_IJOOMLA_SEO_SEO_TOOLTIP_STATUS_KEY_LINK_CATEG"); ?>" >					
			<img src="<?php echo JURI::root() . "administrator/components/com_ijoomla_seo/images/tooltip.png"; ?>" border="0"/></span>
          
        </div>
    </div>
    
	<input type="hidden" name="option" value="com_ijoomla_seo" />
	<input type="hidden" name="controller" value="newilinkscategory" />
	<input type="hidden" name="task" value="" />
	<input type="hidden" name="boxchecked" value="0" />
	<input type="hidden" name="id" value="<?php echo $id; ?>" />
	<?php echo JHtml::_('form.token'); ?>
</form>