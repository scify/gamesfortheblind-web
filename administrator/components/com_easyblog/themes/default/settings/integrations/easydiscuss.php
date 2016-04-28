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
<div class="row">
	<div class="col-lg-6">
		<div class="panel">
			<div class="panel-head">
				<b><?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_GENERAL_TITLE');?></b>
				<div class="panel-info">
					<img style="margin: 0 15px 15px 15px;width: 64px;" src="<?php echo $this->getPathUri('images/vendors');?>/easydiscuss.png" align="right" />
					<?php echo JText::_( 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_DESC' ); ?><br><br>
					<a class="btn btn-primary btn-sm" target="_blank" href="http://stackideas.com/easydiscuss?from=easyblog"><?php echo JText::_('COM_EASYDISCUSS_TRY_BUTTON');?></a>
				</div>
			</div>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'integrations_easydiscuss_points', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_POINTS'); ?>
				<?php echo $this->html('settings.toggle', 'integrations_easydiscuss_badges', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_BADGES'); ?>
			</div>
		</div>
	</div>

	<div class="col-lg-6">
		<div class="panel">
			<div class="panel-head">
				<b><?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_NOTIFICATIONS_TITLE');?></b>
				<div class="panel-info"><?php echo JText::_('COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_NOTIFICATIONS_DESC'); ?></div>
			</div>

			<div class="panel-body">
				<?php echo $this->html('settings.toggle', 'integrations_easydiscuss_notification_blog', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_NOTIFICATIONS_NEW_BLOG'); ?>
				<?php echo $this->html('settings.toggle', 'integrations_easydiscuss_notification_comment', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_NOTIFICATIONS_NEW_COMMENT'); ?>
				<?php echo $this->html('settings.toggle', 'integrations_easydiscuss_notification_rating', 'COM_EASYBLOG_SETTINGS_INTEGRATIONS_EASYDISCUSS_NOTIFICATIONS_RATING'); ?>
			</div>
		</div>
	</div>
</div>
