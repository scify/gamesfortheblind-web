 <?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php if ($this->params->get('post_comment_preview', false) && EB::comment()->isBuiltin() && $post->getComments($this->params->get('post_comment_preview_limit', 3))) { ?>
<div class="eb-post-comments-pre">
	<div class="eb-post-comments-head">
		<span class="show-totalcomment"><?php echo $this->getNouns('COM_EASYBLOG_RECENT_COMMENT', $post->getTotalComments()); ?></span>
	</div>

	<div class="eb-post-comments-list reset-list">
		<?php foreach ($post->getComments($this->params->get('post_comment_preview_limit', 3)) as $comment) { ?>
		<div class="eb-post-comment">
			<i class="fa fa-comment-o"></i>
			<div class="eb-post-comment-content">
				<div class="eb-post-comment-author">
					<a href="<?php echo $comment->getAuthor()->getPermalink();?>">
						<b>
							<?php echo $comment->getAuthor()->getName(); ?>
							<?php if (!$comment->created_by && isset($comment->name) && $comment->name) { ?>
							&mdash; <?php echo $comment->name;?>
							<?php } ?>
						</b>
					</a>
				</div>

				<?php if ($this->config->get('comment_requiretitle') && !empty($comment->title)) { ?>
				<p class="eb-post-comment-title">
					<b><?php echo (JString::strlen($comment->title) > 30) ? JString::substr(strip_tags($comment->title), 0, 30) . '...' : strip_tags($comment->title) ; ?></b>
				</p>
				<?php } ?>

				<div class="eb-post-comment-text">
					<?php if (JString::strlen($comment->comment) > 130) { ?>
						<?php echo JString::substr(strip_tags(EB::comment()->parseBBCode($comment->comment)), 0, 130); ?>... <a class="fd-small" href="<?php echo $post->getPermalink(); ?>"><?php echo JText::_('COM_EASYBLOG_READMORE_HERE'); ?></a>
					<?php } else { ?>
					<?php echo strip_tags(EB::comment()->parseBBCode($comment->comment)); ?>
					<?php } ?>
				</div>

				<a href="<?php echo $post->getPermalink();?>#comment-<?php echo $comment->id; ?>" class="eb-post-comment-date text-muted"><?php echo $comment->getCreated()->format(JText::_('COM_EASYBLOG_DATE_FORMAT_STATISTICS')); ?></a>
			</div>
		</div>
		<?php } ?>
	</div>
</div>
<?php } ?>
