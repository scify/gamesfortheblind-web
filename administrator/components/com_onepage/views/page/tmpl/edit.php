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
if(JVERSION >= 3) JHtml::_('formbehavior.chosen','select');   
?>
<link rel="stylesheet" href="<?php echo JUri::base().'components/com_onepage/assets/css/icon-admin.css'; ?>" type="text/css" media="screen,projection"  />   
<script type="text/javascript">
	Joomla.submitbutton = function(task)
	{
		if (task == 'page.cancel' || document.formvalidator.isValid(document.id('page-form'))) {
			Joomla.submitform(task, document.getElementById('page-form'));
		}
	}
</script>      
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/jquery.fancybox.pack.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/jquery.fancybox.pack.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/jquery-ui.min.js'; ?>"></script> 
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/jquery.geocomplete.min.js'; ?>"></script>  
<script type="text/javascript" src="<?php echo JUri::base().'components/com_onepage/assets/js/design.js'; ?>"></script>     
<form action="<?php echo JRoute::_('index.php?option=com_onepage&layout=edit&id='.(int) $this->item->id); ?>" method="post" name="adminForm" id="page-form" class="form-validate" enctype="multipart/form-data">
    <div class="form-horizontal">
        <?php if(JVERSION >= 3) echo JHtml::_('bootstrap.startTabSet', 'myTab', array('active' => 'general')); ?>
        <?php if(JVERSION >= 3) echo JHtml::_('bootstrap.addTab', 'myTab', 'general', empty($this->item->id) ? JText::_('COM_ONEPAGE_NEW_PAGE') : JText::sprintf('COM_ONEPAGE_PAGE_DETAILS', $this->item->id)); ?>	
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
                    <?php echo $this->form->getLabel('description'); ?>  
                </div>
                <div class="controls">
                    <?php echo $this->form->getInput('description'); ?>
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
	</div>
<?php if($this->id): ?>    
<div id="st-debug"></div>
<div class="st-design row-fluid">
    <div class="span2 st-items">
        <ul id="draggable" class="sortable-list">
            <?php foreach($this->pageitem as $item){ ?>
            <li  class="ui-state-highlight <?php echo $item->class; ?>" data-type="<?php echo $item->type; ?>" data="<?php echo rawurlencode($item->defaultcode); ?>">
                <div class="st-header-title">
                    <span class="st-name"><?php echo $item->name; ?></span>
                    <span href="#" class="st-close" title="Close"><i class="icon-cancel-circle "></i></span>
                    <span href="#st-navigation" title="Edit" data-type="<?php echo $item->type; ?>" class="st-edit stmodal"><i class="icon-cog-2"></i></span>
                    <?php if($item->type=='columns_desi' || $item->type=='columns_desi'){  ?>
                    <span class="st-hide"><i class="icon-new-window"></i></span>
                    <?php } ?>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div class="span10 st-desi">
        <ul id="st-page" class="sortable-list sortable">
            <?php foreach($this->design as $item){ ?>
            <li class="btn ui-state-highlight ui-draggable  <?php echo $item->class; ?>" data-type="<?php echo $item->type; ?>" data="<?php echo rawurlencode($item->json); ?>" style="display:block;">
                <div class="st-header-title">
                    <span class="st-name"><?php echo $item->name; ?></span>
                    <span href="#" class="st-close" title="Close"><i class="icon-cancel-circle "></i></span>
                    <span href="#st-navigation" title="Edit" data-type="<?php echo $item->type; ?>" class="st-edit stmodal"><i class="icon-cog-2"></i></span>
                    <?php
                        if($item->type=='columns_desi' || $item->type=='tabs_desi'){
                    ?>
                    <span class="st-hide"><i class="icon-new-window"></i></span>
                    <?php } ?>
                </div>
            </li>
            <?php } ?>
        </ul>
    </div>
    <div id="st-save-eff">
        <div class="over-message">
            <i class="icon-spinner icon-spinning"></i>
            <div class="message">
                <i class="icon-checkmark-4"></i> Save successful!
            </div>
        </div>
        <div class="over"></div>
    </div>
</div>
<div id="st-navigation" class="st-navigation"></div>
<?php 
$arrName ='{';
foreach($this->pageitem as $key=>$item){
    if($key!=0)
        $arrName.=',';
    $arrName.='"'.$item->type.'":{"name":"'.$item->name.'","class":"'.$item->class.'","type":"'.$item->type.'"}';
}
$arrName.='}';
?>
<div id="st-arrName" data='<?php echo $arrName; ?>'></div>
<div id="st-element" class="none">
    <div id="st-element-modules">
    <?php echo $this->form->getInput('modules'); ?>
    </div>
    <div id="st-element-pageitem">
    <?php //echo $this->form->getInput('pageitem'); ?>
        <select name="jform[pageitem]" id="jform_pageitem" class="">
            <option value="">Select page item</option> 
            <?php foreach($this->listItem as $item): ?>
                <option value="<?php echo $item->id ?>"><?php echo $item->title ?></option>
            <?php endforeach; ?>
        </select>    
    </div>    

</div>
<input type="hidden" id="stid" value="<?php echo $this->id; ?>">
<?php endif; ?>
<div class="btn-group" id="toolbar-save"></div>
	<input type="hidden" name="task" value="" />
	<?php echo JHtml::_('form.token'); ?>

</form>
