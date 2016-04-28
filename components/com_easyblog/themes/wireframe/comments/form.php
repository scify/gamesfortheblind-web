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
<form class="eb-comment-form reset-form" method="post" action="<?php echo JRoute::_('index.php');?>" data-comment-form>

	<div class="eb-composer-author row-table">
		<?php if ($this->config->get('layout_avatar')) { ?>
		<span class="col-cell cell-tight">
			<b class="eb-avatar">
				<img src="<?php echo $user->getAvatar(); ?>" width="50" height="50" />
			</b>
		</span>
		<?php } ?>

		<div class="col-cell">

			<?php if ($this->my->guest) { ?>
			<div class="pull-right">
				<?php echo JText::_('COM_EASYBLOG_COMMENTS_ALREADY_REGISTERED');?>
				<a href="<?php echo $loginURL;?>"><?php echo JText::_('COM_EASYBLOG_COMMENTS_ALREADY_REGISTERED_LOGIN_HERE');?></a>
			</div>
			<?php } ?>

			<strong><?php echo $user->getName();?></strong>
			<div class="text-small text-muted"><?php echo $date->format(JText::_('DATE_FORMAT_LC1'));?></div>
		</div>
	</div>

	<div class="eb-comment-notice" data-comment-notice></div>

	<?php if ($this->acl->get('allow_comment')) { ?>
	<div class="eb-comment-editor">

		<?php if ($this->config->get('comment_requiretitle') || $this->config->get('comment_show_title')) { ?>
		<div class="form-group">
			<input type="text" class="form-control" name="title" id="title" placeholder="<?php echo JText::_('COM_EASYBLOG_COMMENTS_TITLE_PLACEHOLDER', true); ?>" data-comment-title/>
		</div>
		<?php } else { ?>
			<input type="hidden" id="title" name="title" value="" data-comment-title/>
		<?php } ?>

		<div class="form-group">
			<textarea id="comment" name="comment" class="form-control textarea" rows="5" data-comment-editor data-comment-bbcode="<?php echo $this->config->get('comment_bbcode'); ?>"></textarea>
		</div>

		<?php if ($this->my->guest) { ?>
			<?php if($registration) { ?>
			<div class="eb-comment-register text-muted mt-10 mb-15">
				<?php echo JText::_('COM_EASYBLOG_COMMENTS_REGISTER_NOTE');?>
			</div>

			<div class="form-group">
				<input type="text" class="form-control" id="esusername" name="esusername" placeholder="<?php echo JText::_('COM_EASYBLOG_USERNAME', true);?>" data-comment-username/>
			</div>
			<?php } ?>

			<div class="form-group">
				<input type="text" class="form-control" id="esname" name="esname" placeholder="<?php echo JText::_('COM_EASYBLOG_NAME', true);?>" data-comment-name/>
			</div>

			<?php if ($email || $website) { ?>
			<div class="form-group">
				<?php if ($email) { ?>
				<div class="col-cell<?php if ($email & $website) { echo " cell-half"; } ?>">
					<input type="text" class="form-control" name="esemail" id="esemail" placeholder="<?php echo JText::_('COM_EASYBLOG_EMAIL', true); ?>" data-comment-email/>
				</div>
				<?php } ?>

				<?php if ($website) { ?>
				<div class="col-cell<?php if ($email & $website) { echo " cell-half"; } ?>">
					<input type="text" class="form-control" name="url" id="url" placeholder="<?php echo JText::_('COM_EASYBLOG_WEBSITE', true); ?>" data-comment-website/>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		<?php } ?>

		<?php if ($this->my->guest && $registration) { ?>
		<div class="eb-checkbox">
			<input type="checkbox" id="esregister" name="esregister" value="1" data-comment-register/>
			<label for="esregister">
				<?php echo JText::_('COM_EASYBLOG_REGISTER_AS_SITE_MEMBER'); ?>
			</label>
		</div>
		<?php } ?>

		<?php if ($subscribed) { ?>
		<div>
			<div id="unsubscription-message" class="unsubscription-message">
				<?php echo JText::_('COM_EASYBLOG_ENTRY_AUTO_SUBSCRIBE_SUBSCRIBED_NOTE'); ?> 
				<a href="javascript:void(0);" title="" data-blog-unsubscribe data-type="entry" data-subscription-id="<?php echo $subscribed;?>"><?php echo JText::_('COM_EASYBLOG_UNSUBSCRIBE_BLOG'); ?></a>
			</div>
		</div>
		<?php } ?>

		<?php if (!$subscribed && $this->config->get('main_subscription') && $blog->subscription) { ?>
		<div class="eb-checkbox">
			<input type="checkbox" name="subscribe-to-blog" id="subscribe-to-blog" value="1"<?php echo $this->config->get('comment_autosubscribe') ? ' checked="checked"' : '';?> data-comment-subscribe />
			<label for="subscribe-to-blog">
				<?php echo JText::_('COM_EASYBLOG_SUBSCRIBE_BLOG');?>

				<?php if (!$this->my->guest) { ?>
					(<?php echo $this->my->email;?>)
				<?php } else { ?>
					(<?php echo JText::_('COM_EASYBLOG_ENTRY_AUTO_SUBSCRIBE_NOTE');?>)
				<?php } ?>
			</label>
		</div>
		<?php } ?>

		<?php if ($this->config->get('comment_tnc') && (($this->config->get('comment_tnc_users') == 0 && $this->my->guest) || ($this->config->get('comment_tnc_users') == 1 && !$this->my->guest) || ($this->config->get('comment_tnc_users') == 2))) { ?>
		<div class="eb-checkbox">
			<input type="checkbox" name="tnc" id="tnc" value="1" data-comment-terms/>
			<label for="tnc">
				<?php echo JText::sprintf('COM_EASYBLOG_COMMENTS_TNC_AGREE', '<a href="javascript:void(0);" data-comment-tnc>' . JText::_('COM_EASYBLOG_COMMENTS_TNC_TEXT') . '</a>'); ?>
			</label>
		</div>
		<?php } ?>

		<?php echo EB::captcha()->getHTML();?>
		
		<div class="form-action">
			<button class="btn btn-primary" data-comment-submit><?php echo JText::_('COM_EASYBLOG_SUBMIT_COMMENT');?></button>
		</div>
	</div>
	<?php } ?>

	<input type="hidden" name="id" value="<?php echo $blog->id; ?>" data-comment-blog-id />
	<input type="hidden" name="parent_id" id="parent_id" value="0" data-comment-parent-id />
	<input type="hidden" name="comment_depth" id="comment_depth" value="0" data-comment-depth />
	<input type="hidden" name="email" id="email" value="<?php echo $this->my->email; ?>" data-comment-email />
</form>
