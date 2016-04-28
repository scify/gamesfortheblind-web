<?php
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/

// no direct access
defined('_JEXEC') or die;

JHtml::addIncludePath(JPATH_COMPONENT.'/helpers/html');
JHtml::_('behavior.tooltip');
JHtml::_('behavior.formvalidation');

$mystring = $this->item->link;
$findme   = 'http';
$pos = strpos($mystring, $findme);

if ($pos === false) {
    $url = JURI::root().$this->item->link;
} else {
    $url = $this->item->link;
}
?>
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'item.cancel' || document.formvalidator.isValid(document.id('item-form'))) {
			Joomla.submitform(task, document.getElementById('item-form'));
		}
	}
</script>
<script type="text/javascript"> var site_url='<?php echo JUri::base(); ?>';</script>
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/jquery-1.9.1.min.js'; ?>"></script>  
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/onepage.js'; ?>"></script> 
<form action="<?php echo JRoute::_('index.php?option=com_onepage&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="item-form" class="form-validate" enctype="multipart/form-data">
	<div class="form-horizontal">
        <?php if(JVERSION >= 3) echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php if(JVERSION >= 3) echo JHtml::_('bootstrap.addTab', 'myTab', 'general', empty($this->item->id) ? JText::_('COM_ONEPAGE_NEW_ITEM') : JText::sprintf('COM_ONEPAGE_ITEM_DETAILS', $this->item->id)); ?>
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('title'); ?> 
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('title'); ?>
            </div> 
        </div>         
        <div class="control-group">
            <div class="control-label">
                <?php echo $this->form->getLabel('onepage_id'); ?> 
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('onepage_id'); ?>
            </div> 
        </div> 
        <div class="control-group">             
            <div class="control-label">
                <?php echo $this->form->getLabel('state'); ?> 
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('state'); ?>
            </div>                        
        </div>            
        <div class="control-group">                               
            <div class="control-label">
                <?php echo $this->form->getLabel('menu_type'); ?>
            </div>
            <div class="controls">
                <?php echo $this->form->getInput('menu_type'); ?>
            </div>        
        </div>
        <img src="<?php echo JUri::base().'components/com_onepage/assets/images/ajax-loader.gif'; ?>" id="imgwaitting" style="display:none" /> 
        <div id="menuitem" <?php echo $this->item->id ? '':'style="display:none"' ?>>   
            <div class="control-group"> 
                <div class="control-label">
                    <?php echo $this->form->getLabel('menu_id'); ?> 
                </div>
                <div class="controls" id="select-menu-item">   
                    <?php //echo $this->form->getInput('menu_id'); ?>
                    <?php if(isset($this->item->menu_id)) { ?>
                    <select aria-required="true" required="" name="jform[menu_id]" id="jform_menu_id" class="" aria-invalid="false" onclick="loadContentmenu();">
                        <option value=""><?php echo JText::_('COM_ONEPAGE_SELECT'); ?></option>
                        <?php foreach ($this->menuitem as $row): ?>
                            <option value="<?php echo $row->id ?>" <?php echo $row->id == $this->item->menu_id ? 'selected="selected"' : '' ?> ><?php echo $row->title ?></option>
                        <?php endforeach; ?> 
                    </select>
                    <?php } ?>
                </div> 
            </div>
            <img src="<?php echo JUri::base().'components/com_onepage/assets/images/ajax-loader.gif'; ?>" id="imgwaitting1" style="display:none" /> 
            <div id="menu-content" <?php echo $this->item->id ? '':'style="display:none"' ?>>
                <div class="control-group"> 
                    <div class="control-label">
                        <?php echo JText::_('COM_ONEPAGE_CONTENT'); ?>
                        <div>  <br/> 
                            <a class="btn pull-right" onclick="preview('<?php echo $url; ?>','<?php echo $this->item->value; ?>')"><?php echo JText::_('COM_ONEPAGE_BUTTON_PREVIEW') ?></a>
                            <a class="btn pull-right" onclick="original()"><?php echo JText::_('COM_ONEPAGE_BUTTON_ORIGINAL') ?></a>    <br/> <br/>
                            <a class="btn" id="custom" onclick="original()"><?php echo JText::_('COM_ONEPAGE_BUTTON_CUSTOMDOM') ?></a>  
                        </div>                    
                    </div>
                    <div class="controls" id="content-area">
                        <iframe id="myIframe" onload="inspectItem();" src="<?php echo $url ?>" width="100%" height="400" scrolling="yes"></iframe> 
                        <div id="preview"></div><br/> 
                        <div id="customdom"><textarea name="jform[value]" id="contentmenu" cols="20" rows="5"><?php echo $this->item->value ? $this->item->value : '' ?></textarea></div> 
                    </div>
                </div>
            </div>
        </div>         		
		<?php if(JVERSION >= 3) echo JHtml::_('bootstrap.endTab'); ?>
	</div>
              <div class="hidden"> 
                <input id="menulink" type="hidden" name="jform[link]" value="<?php echo $this->item->link ?>" />           
             </div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

<div class="clr"></div>
</form>
