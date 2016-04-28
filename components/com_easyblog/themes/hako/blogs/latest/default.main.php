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
<div itemprop="blogPosts" itemscope itemtype="http://schema.org/BlogPosting" class="eb-post" data-blog-posts-item data-id="<?php echo $post->id;?>">

	<div class="eb-post-content">
		<?php if ($this->config->get('layout_avatar') && $this->params->get('post_author_avatar', true)) { ?>
		<div class="eb-post-avatar">
			<?php if ($post->isTeamBlog() && $this->config->get('layout_teamavatar')) { ?>
			<a href="<?php echo $post->getBlogContribution()->getPermalink(); ?>" class="eb-avatar-team eb-avatar">
				<img src="<?php echo $post->getBlogContribution()->getAvatar();?>" width="50" height="50" alt="<?php echo $post->getBlogContribution()->getTitle();?>" />
			</a>
			<?php } ?>

			<a href="<?php echo $post->author->getProfileLink(); ?>" class="eb-avatar-single eb-avatar">
				<img src="<?php echo $post->author->getAvatar();?>" width="50" height="50" alt="<?php echo $post->author->getName();?>" />
			</a>
		</div>
		<?php } ?>

		<div class="eb-post-head">
			<?php echo $this->output('site/blogs/admin.tools', array('post' => $post, 'return' => $return)); ?>

			<!-- Quote type -->
			<?php if ($post->posttype == 'quote') { ?>
			<div class="eb-post-headline">
				<h2 itemprop="name headline" class="eb-post-title reset-heading">
					<a href="<?php echo $post->getPermalink();?>" class="text-inherit"><?php echo nl2br($post->title);?></a>
				</h2>

				<div class="eb-post-headline-source">
					<?php echo $post->getContent(); ?>
				</div>
			</div>
			<?php } ?>

			<!-- Link type -->
			<?php if ($post->posttype == 'link') { ?>
			<?php $link = $post->getAsset('link')->getValue(); ?>
			<div class="eb-post-headline">
				<h2 itemprop="name headline" class="eb-placeholder-link-title eb-post-title reset-heading">
					<a href="<?php echo $post->getPermalink();?>"><?php echo nl2br($post->title);?></a>
				</h2>

				<div class="eb-post-headline-source">
					<a href="<?php echo $post->getAsset('link'); ?>" target="_blank">
						<?php echo EB::string()->htmlAnchorLink($link, $link); ?>
					</a>
				</div>
			</div>
			<?php } ?>

			<!-- Twitter type -->
			<?php if ($post->posttype == 'twitter') { ?>
			<?php $screen_name = $post->getAsset('screen_name')->getValue();
				  $created_at = EB::date($post->getAsset('created_at')->getValue(), true)->format(JText::_('DATE_FORMAT_LC'));
			?>
			<div class="eb-post-headline">
				<h2 itemprop="name headline" class="eb-post-title-tweet reset-heading">
					<?php echo $post->content;?>
				</h2>

				<?php if (!empty($screen_name) && !empty($created_at)) { ?>
				<div class="eb-post-headline-source">
						<?php echo '@'.$screen_name.' - '.$created_at; ?>
						&middot;
						<a href="<?php echo $post->getPermalink();?>">
							<?php echo JText::_('COM_EASYBLOG_LINK_TO_POST'); ?>
						</a>
				</div>
				<?php } ?>
			</div>
			<?php } ?>

			<!-- Photo/Video/Standard type -->
			<?php if ((in_array($post->posttype, array('photo', 'standard', 'video', 'email'))) && $this->params->get('post_title', true)) { ?>
			<h2 itemprop="name headline" class="eb-post-title reset-heading">
				<a href="<?php echo $post->getPermalink();?>" class="text-inherit"><?php echo $post->title;?></a>
			</h2>
			<?php } ?>

			<?php if ($this->params->get('post_date', true) || $this->params->get('post_author', true) || $this->params->get('post_category', true)) { ?>
			<div class="eb-post-meta text-muted">
				<?php if ($post->isFeatured) { ?>
				<div class="eb-post-featured">
					<!-- <i class="fa fa-star" data-original-title="" data-placement="bottom" data-eb-provide="tooltip"></i> -->
					<b><?php echo JText::_('COM_EASYBLOG_FEATURED');?></b>
				</div>
				<?php } ?>

				<?php if ($this->params->get('post_author', true)) { ?>
				<div class="eb-post-author" itemprop="author" itemscope="" itemtype="http://schema.org/Person">
					<i class="fa fa-user"></i>
					<span itemprop="name">
						<a href="<?php echo $post->author->getProfileLink();?>" itemprop="url" rel="author"><?php echo $post->author->getName();?></a>
					</span>
				</div>
				<?php } ?>

				<?php if ($this->params->get('post_date', true)) { ?>
				<div class="eb-post-date">
					<i class="fa fa-clock-o"></i>
					<time class="eb-meta-date" itemprop="datePublished" content="<?php echo $post->getDisplayDate($this->params->get('post_date_source', 'created'))->format(JText::_('DATE_FORMAT_LC4'));?>">
						<?php echo $post->getDisplayDate($this->params->get('post_date_source', 'created'))->format(JText::_('DATE_FORMAT_LC1')); ?>
					</time>
				</div>
				<?php } ?>

				<?php if ($this->params->get('post_category', true) && $post->categories) { ?>
				<div class="eb-post-category comma-seperator">
					<i class="fa fa-folder-open"></i>
					<?php foreach ($post->categories as $category) { ?>
					<span>
						<a href="<?php echo $category->getPermalink();?>"><?php echo $category->getTitle();?></a>
					</span>
					<?php } ?>
				</div>
				<?php } ?>
			</div>
			<?php } ?>
		</div>

		<?php if (in_array($post->getType(), array('photo', 'standard', 'twitter', 'email', 'link'))) { ?>
			<div class="eb-post-body type-<?php echo $post->posttype; ?>">
				<?php echo $this->output('site/blogs/latest/post.cover', array('post' => $post)); ?>

				<?php echo $post->getIntro();?>
			</div>
		<?php } ?>

		<?php if ($post->posttype == 'video') { ?>
		<div class="eb-post-video">
			<?php foreach ($post->videos as $video) { ?>
			<div class="eb-responsive-video">
				<?php echo $video->html;?>
			</div>
			<?php } ?>
		</div>
		<?php } ?>

		<?php if ($post->fields && $post->category->getParam('show_fields', true)) { ?>
			<?php echo $this->output('site/blogs/entry/fields', array('fields' => $post->fields, 'post'=>$post)); ?>
		<?php } ?>

		<?php if ($this->params->get('post_tags', true)) { ?>
			<?php echo $this->output('site/blogs/tags/item', array('post' => $post)); ?>
		<?php } ?>

		<?php if ($this->config->get('main_ratings_frontpage') && $this->params->get('post_ratings', true)) { ?>
			<div class="eb-post-rating">
				<?php echo $this->output('site/ratings/frontpage', array('post' => $post)); ?>
			</div>
		<?php } ?>

		<?php if ($post->copyrights && $this->params->get('post_copyrights', true)) { ?>
			<div class="eb-entry-copyright">
				<h4 class="eb-section-title"><?php echo JText::_('COM_EASYBLOG_COPYRIGHT_HEADING');?></h4>
				<p>&copy; <?php echo $post->copyrights;?></p>
			</div>
		<?php } ?>

		<?php if ($this->params->get('post_social_buttons', true)) { ?>
			<?php echo $this->output('site/blogs/part.socialbuttons', array('post' => $post)); ?>
		<?php } ?>

		<?php echo $this->output('site/blogs/latest/part.comments', array('post' => $post)); ?>

		<div class="eb-post-foot">
			<div class="row-table">
			<?php if ($post->hasReadmore() && $this->params->get('post_readmore', true)) { ?>
				<div class="col-cell cell-tight eb-post-more">
					<a class="btn btn-default" href="<?php echo $post->getPermalink();?>"><?php echo JText::_('COM_EASYBLOG_CONTINUE_READING');?></a>
				</div>
			<?php } ?>

			<?php if ($this->params->get('post_hits', true)) { ?>
				<div class="col-cell cell-tight eb-post-hits">
					<i class="fa fa-eye"></i> <?php echo JText::sprintf('COM_EASYBLOG_POST_HITS', $post->hits);?>
				</div>
			<?php } ?>

			<?php if ($this->config->get('main_comment') && $this->params->get('post_comment_counter', true) && $post->allowcomment) { ?>
				<div class="col-cell cell-tight eb-post-comments">
					<i class="fa fa-comments"></i>
					<a href="<?php echo $post->getPermalink();?>"><?php echo $this->getNouns('COM_EASYBLOG_COMMENT_COUNT', $post->getTotalComments(), true); ?></a>
				</div>
			<?php } ?>

			<?php if ($this->params->get('post_type', true)) { ?>
			<div class="col-cell text-right eb-post-type">
				<?php echo $post->getIcon(); ?>
			</div>
			<?php } ?>
			</div>
		</div>
	</div>
</div>
