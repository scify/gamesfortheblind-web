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
<div class="eb-comments" data-eb-comments>
	<h4 class="eb-section-heading reset-heading">
		<?php echo JText::_('COM_EASYBLOG_COMMENTS');?> 
		<?php if ( $blog->totalComments ) { ?>
			<span data-comment-counter><?php echo $blog->totalComments;?></span>
		<?php } ?>
	</h4>
	
	<a class="eb-anchor-link" name="comments" id="comments">&nbsp;</a>

	<?php if (!$this->config->get('main_allowguestviewcomment') && !$this->acl->get('allow_comment') && $this->my->guest) { ?>
		<div class="eblog-message info">
			<?php echo JText::sprintf('COM_EASYBLOG_COMMENT_DISABLED_FOR_GUESTS', $loginURL); ?>
		</div>
	<?php } else { ?>
		<?php if ($this->config->get('main_allowguestviewcomment') && $this->my->guest || (!$this->my->guest)) { ?>
			<div data-comment-list>
				<?php if ($comments) { ?>
					<?php foreach ($comments as $comment) { ?>
						<?php echo $this->output('site/comments/default.item', array('comment' => $comment)); ?>
					<?php } ?>
				<?php } else { ?>
					<div class="eb-comments-empty" data-comment-empty>
						<i class="fa fa-info-circle"></i>
						<?php echo JText::_('COM_EASYBLOG_COMMENTS_NO_COMMENT_YET'); ?>
					</div>
				<?php } ?>
			</div>
		<?php } ?>
	<?php } ?>

	<?php if($pagination) {?>
		<?php echo $pagination;?>
	<?php } ?>

<?php if (($this->acl->get('allow_comment') && !$this->my->guest) || ($this->acl->get('allow_comment') && $this->my->guest)) { ?>
	<div data-comment-form-wrapper>
		<?php echo $this->output('site/comments/form'); ?>
	</div>
<?php } ?>
</div>
