<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<dialog>
	<width>400</width>
	<height>100</height>
	<selectors type="json">
	{
		"{submitButton}"	: "[data-submit-button]",
		"{cancelButton}"	: "[data-cancel-button]"
	}
	</selectors>
	<bindings type="javascript">
	{
		"{cancelButton} click": function() {
			this.parent.close();
		},
		"{submitButton} click" : function()
		{
			$('[data-notify-form]').submit();
		}
	}
	</bindings>
	<title><?php echo JText::_('COM_EASYBLOG_BLOGS_DIALOG_RENOTIFY_TITLE');?></title>
	<content>
		<p><?php echo JText::_('COM_EASYBLOG_BLOGS_DIALOG_RENOTIFY_CONTENT'); ?></p>

		<form data-notify-form method="post">
			<?php echo $this->html('form.action', 'blogs.notify'); ?>

			<input type="hidden" name="id" value="<?php echo $id;?>" />
		</form>
	</content>
	<buttons>
		<button data-cancel-button type="button" class="btn btn-default btn-sm"><?php echo JText::_('COM_EASYBLOG_CANCEL_BUTTON'); ?></button>
		<button data-submit-button type="button" class="btn btn-primary btn-sm"><?php echo JText::_('COM_EASYBLOG_PROCEED_BUTTON'); ?></button>
	</buttons>
</dialog>
