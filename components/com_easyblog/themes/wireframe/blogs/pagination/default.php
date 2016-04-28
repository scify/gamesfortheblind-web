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
<div class="eb-pager eb-responsive">
	<?php if($data->previous->link) { ?>
		<a href="<?php echo EB::uniqueLinkSegments($data->previous->link); ?>" rel="prev">
			<i class="fa fa-chevron-left"></i> <?php echo JText::_('COM_EASYBLOG_PAGINATION_PREVIOUS');?>
		</a>
	<?php } else { ?>
		<a href="javascript:void(0);" class="disabled">
			<i class="fa fa-chevron-left"></i> <?php echo JText::_('COM_EASYBLOG_PAGINATION_PREVIOUS');?>
		</a>
	<?php } ?>

	<?php if($data->next->link) { ?>
		<a href="<?php echo EB::uniqueLinkSegments( $data->next->link ); ?>" rel="next">
			<?php echo JText::_('COM_EASYBLOG_PAGINATION_NEXT'); ?> <i class="fa fa-chevron-right"></i>
		</a>
	<?php } else { ?>
		<a href="javascript:void(0);" class="disabled">
			<?php echo JText::_('COM_EASYBLOG_PAGINATION_NEXT');?><i class="fa fa-chevron-right"></i>
		</a>
	<?php } ?>

	<div>
		<?php foreach ($data->pages as $page) { ?>
			<?php if ($page->link) { ?>
				<a href="<?php echo EB::uniqueLinkSegments($page->link); ?>"><?php echo $page->text;?></a>
			<?php } else { ?>
				<a class="disabled active"><?php echo $page->text;?></a>
			<?php 	} ?>
		<?php } ?>
	</div>
</div>
