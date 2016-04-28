<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
	<width>500</width>
	<height>200</height>
	<selectors type="json">
	{
		"{closeButton}" : "[data-close-button]",
		"{form}" : "[data-form-response]",
		"{submitButton}" : "[data-submit-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{closeButton} click": function()
		{
			this.parent.close();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYBLOG_DASHBOARD_BLOG_DIALOG_CONFIRM_REJECT_TITLE'); ?></title>
	<content>
		<p class="mt-5">
			<?php echo JText::_('COM_EASYBLOG_DASHBOARD_BLOG_DIALOG_CONFIRM_REJECT_CONTENT');?>
		</p>

		<form data-form-response method="post" action="<?php echo JRoute::_('index.php');?>">
			<div class="mt-20">
				<textarea class="form-control" name="message" data-reason placeholder="<?php echo JText::_('COM_EASYBLOG_REJECT_BLOG_POST_PLACEHOLDER');?>"></textarea>
			</div>
			
			<?php foreach ($ids as $id) {?>
				<input type="hidden" name="ids[]" value="<?php echo $id;?>" />
			<?php } ?>
			<?php echo $this->html('form.action', 'moderate.reject'); ?>
		</form>
	</content>
	<buttons>
		<button type="button" class="btn btn-default btn-sm" data-close-button><?php echo JText::_('COM_EASYBLOG_CANCEL_BUTTON');?></button>
		<button type="button" class="btn btn-primary btn-sm" data-submit-button><?php echo JText::_('COM_EASYBLOG_REJECT_POSTS_BUTTON');?></button>
	</buttons>
</dialog>
