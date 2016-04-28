<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<div id="fd" class="eb eb-mod mod-easyblogwelcome<?php echo $params->get('moduleclass_sfx') ?>">
	
	<?php if (!$my->guest) { ?>
		<div class="mod-welcome-profile mod-table align-middle">
			<?php if ($params->get('display_avatar')) { ?>
			<div class="mod-cell cell-tight">
				<a href="<?php echo $author->getPermalink();?>" class="mod-avatar">
					<img src="<?php echo $author->getAvatar();?>" class="avatar" width="50" height="50" />
				</a>
			</div>
			<?php } ?>

			<div class="mod-cell">
				<a href="<?php echo $author->getProfileLink();?>">
					<b><?php echo $author->getName();?></b>
				</a>

				<?php if ($acl->get('add_entry')) { ?>
				<div>
					<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=dashboard&layout=profile');?>" class="small">
						<?php echo JText::_( 'MOD_EASYBLOGWELCOME_SETTINGS');?>
					</a>
				</div>
				<?php } ?>
			</div>
		</div>

		<div class="mod-welcome-action">
			<?php if ($acl->get('add_entry')) { ?>
				<?php if ($config->get('main_microblog')) { ?>
				<div class="eb-mod-item">
					<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=dashboard&layout=quickpost');?>">
						<span class="mod-cell">
							<i class="mod-muted fa fa-bolt"></i>
						</span>
						<span class="mod-cell">
							<?php echo JText::_('MOD_EASYBLOGWELCOME_QUICK_SHARE');?>
						</span>
					</a>
				</div>
				<?php } ?>

				<div class="eb-mod-item">
					<a href="<?php echo EB::_('index.php?option=com_easyblog&view=composer&tmpl=component'); ?>" target="_blank" data-eb-composer>
						<span class="mod-cell">
							<i class="mod-muted fa fa-pencil"></i>
						</span>
						<span class="mod-cell">
							<?php echo JText::_('MOD_EASYBLOGWELCOME_WRITE_NEW');?>
						</span>
					</a>
				</div>

				<div class="eb-mod-item">
					<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=dashboard&layout=entries');?>">
						<span class="mod-cell">
							<i class="mod-muted fa fa-file-text"></i>
						</span>
						<span class="mod-cell">
							<?php echo JText::_('MOD_EASYBLOGWELCOME_MYBLOGS');?>
						</span>
					</a>
				</div>

				<?php if ((($config->get('comment_easyblog')) && $config->get('main_comment_multiple')) && $config->get('main_comment')) { ?>
				<div class="eb-mod-item">
					<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=dashboard&layout=comments');?>">
						<span class="mod-cell">
							<i class="mod-muted fa fa-comments"></i>
						</span>
						<span class="mod-cell">
							<?php echo JText::_( 'MOD_EASYBLOGWELCOME_MYCOMMENTS');?>
						</span>
					</a>
				</div>
				<?php } ?>
			<?php } ?>

			<div class="eb-mod-item">
				<a href="<?php echo EBR::_('index.php?option=com_easyblog&view=subscription');?>">
					<span class="mod-cell">
						<i class="mod-muted fa fa-envelope"></i>
					</span>
					<span class="mod-cell">
						<?php echo JText::_('MOD_EASYBLOGWELCOME_MYSUBSCRIPTION');?>
					</span>
				</a>
			</div>

			<?php if ($params->get('enable_login')) { ?>
			<div class="eb-mod-item">
				<a href="<?php echo JRoute::_('index.php?option=com_users&task=user.logout&' . JSession::getFormToken() . '=1&return='.$return);?>">
					<span class="mod-cell">
						<i class="mod-muted fa fa-sign-out"></i>
					</span>
					<span class="mod-cell">
						<?php echo JText::_('MOD_EASYBLOGWELCOME_LOGOUT');?>
					</span>
				</a>
			</div>
			<?php } ?>
		</div>
	<?php } ?>

	<?php if ($my->guest && $params->get('enable_login')) { ?>
	<form action="<?php echo JRoute::_('index.php', true, $params->get('usesecure')); ?>" method="post" name="login" id="form-login">
		<?php echo $params->get('pretext'); ?>

		<div>
			<label for="eb-username"><?php echo JText::_('MOD_EASYBLOGWELCOME_USERNAME') ?></label>
			<input id="eb-username" type="text" name="username" class="mod-input" />
		</div>

		<div>
			<label for="eb-password"><?php echo JText::_('MOD_EASYBLOGWELCOME_PASSWORD') ?></label>
			<input id="eb-password" type="password" name="password" class="mod-input" />
		</div>

		<?php if (JPluginHelper::isEnabled('system', 'remember')) { ?>
		<div class="mod-checkbox">
			<input id="eb-remember" type="checkbox" name="remember" class="inputbox" value="yes" />
			<label for="eb-remember"><?php echo JText::_('MOD_EASYBLOGWELCOME_REMEMBER_ME'); ?></label>
		</div>
		<?php } ?>

		<div>
			<button class="mod-btn mod-btn-block mod-btn-primary"><?php echo JText::_('MOD_EASYBLOGWELCOME_LOGIN') ?></button>
		</div>

		<br>

		<div>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=reset'); ?>">
			<?php echo JText::_('MOD_EASYBLOGWELCOME_FORGOT_YOUR_PASSWORD'); ?></a>
		</div>
		<div>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=remind'); ?>">
			<?php echo JText::_('MOD_EASYBLOGWELCOME_FORGOT_YOUR_USERNAME'); ?></a>
		</div>
		
		<?php if ($allowRegistration) { ?>
		<div>
			<a href="<?php echo JRoute::_('index.php?option=com_users&view=registration'); ?>">
				<?php echo JText::_('MOD_EASYBLOGWELCOME_REGISTER'); ?>
			</a>
		</div>
		<?php } ?>

		<?php echo $params->get('posttext'); ?>

		<input type="hidden" name="option" value="com_users" />
		<input type="hidden" name="task" value="user.login" />
		<input type="hidden" name="return" value="<?php echo $return; ?>" />
		<?php echo JHTML::_('form.token'); ?>
	</form>
	<?php } ?>
</div>
