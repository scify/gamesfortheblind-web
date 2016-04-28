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

class EasyBlogCommentHyperComments extends EasyBlog
{
	public function html(EasyBlogPost &$blog)
	{
		$widgetId = $this->config->get('comment_hypercomments_widgetid');

		if (!$widgetId) {
			return;
		}

		$template = EB::template();
		$template->set('widgetId', $widgetId);
		$template->set('blog', $blog);

		$output = $template->output('site/comments/hypercomments');

		return $output;
	}
}
