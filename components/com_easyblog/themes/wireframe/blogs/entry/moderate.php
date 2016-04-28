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
<div class="eb-entry-moderate">
	<h4 class="reset-heading"><?php echo JText::_('COM_EASYBLOG_POST_UNDER_MODERATION');?></h4>
	<div class="mt-10 mb-15"><?php echo JText::_('COM_EASYBLOG_POST_UNDER_MODERATION_INFO'); ?></div>

		<div class="clearfix" style="margin-top: 15px;">
			<a class="btn btn-primary pull-right" href="javascript:void(0);" data-blog-moderate-approve>
				<i class="fa fa-check-circle"></i>
				&nbsp; <?php echo JText::_('COM_EASYBLOG_POST_APPROVE_POST');?>
			</a>
			<a class="btn btn-default" href="<?php echo $post->getEditLink();?>" target="_blank">
				<i class="fa fa-pencil"></i>
				&nbsp; <?php echo JText::_('COM_EASYBLOG_POST_REVIEW_POST');?>
			</a>

			<a class="btn btn-danger" href="javascript:void(0);" data-blog-moderate-reject>
				<i class="fa fa-close"></i>
				&nbsp; <?php echo JText::_('COM_EASYBLOG_POST_REJECT_POST');?>
			</a>
		</div>
</div>
