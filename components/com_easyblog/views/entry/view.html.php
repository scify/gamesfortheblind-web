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

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewEntry extends EasyBlogView
{
	/**
	 * Main display for the blog entry view
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function display($tpl = null)
	{
		// Get the blog post id from the request
		$id = $this->input->get('id', 0, 'int');

		// Load the blog post now
		$post = EB::post($id);

		// If blog id is not provided correctly, throw a 404 error page
		if (!$id || !$post->id) {
			return JError::raiseError(404, JText::_('COM_EASYBLOG_ENTRY_BLOG_NOT_FOUND'));
		}

		// If the settings requires the user to be logged in, do not allow guests here.
		$post->requiresLoginToRead();

		// After the post is loaded, set it into the cache
		EB::cache()->insert(array($post));

		// Render necessary data on the headers
		$post->renderHeaders();

		// Check if blog is password protected.
		$protected = $this->isProtected($post);

		if ($protected !== false) {
			return;
		}

		// Perform validation checks to see if post is valid
		$exception = $post->checkView();

		if ($exception instanceof EasyBlogException) {
			return JError::raiseError(400, $exception->getMessage());
		}

		// Increment the hit counter for the blog post.
		$post->hit();

		// Format the post
		$post = EB::formatter('entry', $post);

		// Add bloggers breadcrumbs
		if (!EBR::isCurrentActiveMenu('blogger', $post->author->id) && $this->config->get('layout_blogger_breadcrumb')) {
			$this->setPathway($post->author->getName(), $post->author->getPermalink());
		}

		// Add entry breadcrumb
		if (!EBR::isCurrentActiveMenu('entry', $post->id)) {
			$this->setPathway($post->title, '');
		}

		// Load up the blog model
		$model = EB::model('Blog');

		// Get author's recent posts.
		$recent = $this->getRecentPosts($post);

		// Add canonical URLs for the blog post
		if ($this->config->get('main_canonical_entry')) {
			$this->canonical('index.php?option=com_easyblog&view=entry&id=' . $post->id);
		}

		// Prepare navigation object
		$navigation = $this->prepareNavigation($post);

		// Retrieve Google Adsense codes
		$adsense = EB::adsense()->html($post);

		// If a custom theme is setup for entries in the category, set a different theme
		if (!empty($post->category->theme)) {
			$this->setTheme($post->category->theme);
		}

		// Check if the user subscribed to this post.
		$subscription = EB::table('Subscriptions');
		$subscription->load(array('uid' => $post->id, 'utype' => 'entry', 'user_id' => $this->my->id));

		$theme = EB::template();

		// Prepare related post
		$relatedPosts = array();

		// Get the menu params associated with this post
		$params = $post->getMenuParams();

		// Related posts seems to be missing from the theme file.
		if ($params->get('post_related', true)) {
			$relatedPosts = $model->getRelatedPosts($post->id, $post->category->getParam('post_related_limit', 5));

			// Format the related posts image
			if ($relatedPosts) {
				foreach ($relatedPosts as $relatedPost) {

					$relatedPost->postimage = $relatedPost->getImage('thumbnail');

					// Try to get the first image in the post
					if (!$relatedPost->hasImage()) {
						$content = $relatedPost->getContent(EASYBLOG_VIEW_ENTRY);

						$image = EB::string()->getImage($content);

						if ($image) {
							$relatedPost->postimage = $image;
						}
					}
				}
			}
		}

		if (!$post->posttype) {
			$post->posttype = 'standard';
		}

		$this->set('post', $post);
		$this->set('navigation', $navigation);
		$this->set('relatedPosts', $relatedPosts);
		$this->set('recent', $recent);
		$this->set('preview', false);
		$this->set('adsense' , $adsense);
		$this->set('subscription', $subscription);

		$this->theme->entryParams = $params;

		parent::display('blogs/entry/default');
	}

	/**
	 * Login layout for entry view
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function login()
	{
		$return = $this->input->get('return', '', 'string');

		if (!$return) {
			$return = base64_encode(EBR::_('index.php?option=com_easyblog', false));
		}

		$this->set('return', $return);

		parent::display('blogs/entry/login');
	}


	/**
	 * Determines if the current post is protected
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isProtected(EasyBlogPost &$post)
	{
		// Password protection disabled
		if (!$this->config->get('main_password_protect')) {
			return false;
		}

		// Site admin should not be restricted
		if (EB::isSiteAdmin()) {
			return false;
		}

		// Blog does not contain any password protection
		if (!$post->isPasswordProtected()) {
			return false;
		}

		// User already entered password
		if ($post->verifyPassword()) {
			return false;
		}

		$post = EB::formatter('entry', $post);

		// Set the return url to the current url
		$return = base64_encode($post->getPermalink(false));

		// Get the menu params associated with this post
		$this->theme->params = $post->getMenuParams();

		$this->set('post', $post);

		parent::display('blogs/entry/default.protected');

		return;
	}

	/**
	 * Displays the latest entry on the site using the entry view
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function latest()
	{
		// Fetch the latest blog entry
		$model 	= EB::model('Blog');

		// Get the current active menu's properties.
		$app = JFactory::getApplication();
		$menu = $app->getMenu()->getActive();
		$inclusion = '';

		if (is_object($menu)) {
			$params = EB::registry($menu->params);
			$inclusion = EB::getCategoryInclusion($params->get('inclusion'));
		}

		$items 	= $model->getBlogsBy( 'latest' , 0 , '' , 1 , EBLOG_FILTER_PUBLISHED , null , true , array() , false , false , true , array() , $inclusion );

		if (is_array($items) && !empty($items)) {
			JRequest::setVar( 'id' , $items[ 0 ]->id );
			return $this->display();
		}

		echo JText::_( 'COM_EASYBLOG_NO_BLOG_ENTRY' );
	}

	/**
	 * Main display for the blog entry view
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function preview($tpl = null)
	{
		// Get the blog post id from the request
		$id = $this->input->get('uid', '', 'default');

		// Load the blog post now
		$post = EB::post($id);

		// After the post is loaded, set it into the cache
		EB::cache()->insert(array($post));

		// If blog id is not provided correctly, throw a 404 error page
		if (!$id || !$post->id) {
			return JError::raiseError(404, JText::_('COM_EASYBLOG_ENTRY_BLOG_NOT_FOUND'));
		}

		// If the settings requires the user to be logged in, do not allow guests here.
		$post->requiresLoginToRead();

		// Render necessary data on the headers
		$post->renderHeaders();

		// Check if blog is password protected.
		$protected = $this->isProtected($post);

		if ($protected !== false) {
			return;
		}

		// Perform validation checks to see if post is valid
		$exception = $post->checkView();

		if ($exception instanceof EasyBlogException) {
			return JError::raiseError(400, $exception->getMessage());
		}

		// If the viewer is the owner of the blog post, display a proper message
		if ($this->my->id == $post->created_by && !$post->isPublished()) {
			$notice = JText::_('COM_EASYBLOG_ENTRY_BLOG_UNPUBLISHED_VISIBLE_TO_OWNER');
		}

		if (EB::isSiteAdmin() && !$post->isPublished()) {
			$notice = JText::_('COM_EASYBLOG_ENTRY_BLOG_UNPUBLISHED_VISIBLE_TO_ADMIN');
		}

		// Format the post
		$post = EB::formatter('entry', $post);

		// Add bloggers breadcrumbs
		if (!EBR::isCurrentActiveMenu('blogger', $post->author->id) && $this->config->get('layout_blogger_breadcrumb')) {
			$this->setPathway($post->author->getName(), $post->author->getPermalink());
		}

		// Add entry breadcrumb
		if (!EBR::isCurrentActiveMenu('entry', $post->id)) {
			$this->setPathway($post->title, '');
		}

		// Load up the blog model
		$model = EB::model('Blog');

		// Get author's recent posts.
		$recent = $this->getRecentPosts($post);

		// Add canonical URLs for the blog post
		if ($this->config->get('main_canonical_entry')) {
			$this->canonical('index.php?option=com_easyblog&view=entry&id=' . $post->id);
		}

		// Prepare navigation object
		$navigation = $this->prepareNavigation($post);

		// Retrieve Google Adsense codes
		$adsense = EB::adsense()->html($post);

		// If a custom theme is setup for entries in the category, set a different theme
		if (!empty($post->category->theme)) {
			$this->setTheme($post->category->theme);
		}

		// Check if the user subscribed to this post.
		$isBlogSubscribed = $model->isBlogSubscribedEmail($post->id, $this->my->email);

		$theme = EB::template();

		// Prepare related post
		$relatedPosts = array();

		// @TODO: Related posts seems to be missing from the theme file.
		if ($theme->params->get('post_related', true)) {
			$relatedPosts = $model->getRelatedPosts($post->id, $theme->params->get('post_related_limit', 5));
		}

		if (!$post->posttype) {
			$post->posttype = 'standard';
		}

		$this->set('post', $post);
		$this->set('navigation', $navigation);
		$this->set('relatedPosts', $relatedPosts);
		$this->set('recent', $recent);
		$this->set('preview', true);
		$this->set('adsense' , $adsense);
		$this->set('isBlogSubscribed', $isBlogSubscribed);

		// Get the menu params associated with this post
		$params = $post->getMenuParams();

		$this->theme->entryParams = $params;

		parent::display('blogs/entry/default');
	}

	/**
	 * Retrieves a list of recent posts
	 *
	 * @since	4.0
	 * @access	private
	 * @param	string
	 * @return
	 */
	public function getRecentPosts(EasyBlogPost &$post)
	{
		$recent = array();
		if (!$post->category->getParam('show_author_box', true) || !$post->category->getParam('post_author_recent', true)) {
			return $recent;
		}

		$limit = $post->category->getParam('post_author_recent_limit', 5);

		$model = EB::model('Blog');
		$result = $model->getBlogsBy('blogger', $post->created_by, 'latest', $limit);

		if (!$result) {
			return $recent;
		}

		$posts = array();

		foreach ($result as $row) {
			$item = EB::post();
			$item->bind($row, array('force' => true));

			$posts[] = $item;
		}

		return $posts;
	}

	/**
	 * Prepares the blog navigation
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function prepareNavigation(EasyBlogPost &$post)
	{
		// If it's not enabled, skip this
		if (!$this->config->get('layout_navigation')) {
			return;
		}

		// Determines if the blog is associated with any teams
		$navigationType = 'team';

		// This should change to the menu params
		if ($this->theme->params->get('layout_navigation_restrict_author')) {
			$navigationType = 'author';
		}

		$model = EB::model('Blog');
		$navigation = $model->getPostNavigation($post, $navigationType);

		if ($navigation->prev) {

			$navigation->prev->link = EBR::_('index.php?option=com_easyblog&view=entry&id=' . $navigation->prev->id);
			$navigation->prev->title = JString::strlen($navigation->prev->title) > 50 ? JString::substr($navigation->prev->title, 0, 50) . JText::_('COM_EASYBLOG_ELLIPSES') : $navigation->prev->title;
		}

		if ($navigation->next) {
			$nextPost = EB::post($navigation->next->id);

			$navigation->next->link = $nextPost->getPermalink();
			$navigation->next->title = JString::strlen($navigation->next->title) > 50 ? JString::substr($navigation->next->title, 0, 50) . JText::_('COM_EASYBLOG_ELLIPSES') : $navigation->next->title;
		}

		return $navigation;
	}
}
