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

require_once(JPATH_COMPONENT . '/views/views.php');

class EasyBlogViewComments extends EasyBlogView
{
	/**
	 * Processes comment saving
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function save()
	{
		// Check for request forgeries
		EB::checkToken();

		// Test if user is really allowed to post comments
		if (!$this->acl->get('allow_comment')) {
			return $ajax->reject(JText::_('COM_EASYBLOG_NO_PERMISSION_TO_POST_COMMENT'));
		}

		// Default values
		$moderated = false;
		$parentId  = $this->input->get('parentId', 0, 'int');
		$depth 	   = $this->input->get('depth', 0, 'int');
		$subscribe = $this->input->get('subscribe', false, 'bool');
		$email     = $this->input->get('email', '', 'email');
		$message   = $this->input->get('comment', '', 'default');
		$name 	   = $this->input->get('name', '', 'default');
		$username  = $this->input->get('username', '', 'default');
		$title 	   = $this->input->get('title', '', 'default');
		$terms 	   = $this->input->get('terms', false, 'bool');
		$blogId    = $this->input->get('blogId', 0, 'int');
		$isCB      = $this->input->get('iscb', 0, 'int');


		// Validate the email
		$data = array('post_id' => $blogId, 'comment' => $message, 'title' => $title, 'email' => $email, 'name' => $name, 'username' => $username, 'terms' => $terms);

		// Load up comment table
		$comment = EB::table('Comment');
		$state = $comment->validatePost($data);

		if (!$state) {
			return $this->ajax->reject($comment->getError());
		}

		// Bind the data on the comment table now
		$comment->bindPost($data);

		// Check for spams
		if ($comment->isSpam()) {
			return $this->ajax->reject(JText::_('COM_EASYBLOG_SPAM_DETECTED_IN_COMMENT'));
		}

		$captchaResponse = EB::captcha()->verify();

		// Perform captcha verification
		if ($captchaResponse == false) {
			return $this->ajax->reject(JText::_('COM_EASYBLOG_CAPTCHA_INVALID_RESPONSE'));
		}

		// Get current date
		$date = EB::date();

		// Set other attributes for the comment
		$comment->created = $date->toSql();
		$comment->modified = $date->toSql();
		$comment->published = true;
		$comment->parent_id = $parentId;
		$comment->created_by = $this->my->id;

		// Process user registrations via comment
		$register = $this->input->get('register', '', 'bool');


		if ($register && $this->my->guest) {

			$userModel = EB::model('Users');
			$id = $userModel->createUser($username, $email, $name);

			if (!is_numeric($id)) {
				return $this->ajax->reject($id);
			}

			$comment->created_by = $id;
		}

		$totalComments = $this->input->get('totalComment', 0, 'int');


		// Determines if comment moderation is enabled
		if ($this->config->get('comment_moderatecomment') == 1 || ($this->my->guest && $this->config->get('comment_moderateguestcomment'))) {
			$comment->published = EBLOG_COMMENT_STATUS_MODERATED;
		}

		// Load up the blog table
		$blog = EB::table('Blog');
		$blog->load($comment->post_id);

		// If moderation for author is disabled, ensure that the comment is also published automatically.
		if ((!$this->config->get('comment_moderateauthorcomment') && $blog->created_by == $this->my->id) || EB::isSiteAdmin()) {
			$comment->published = true;
		}

		// Update the ordering of the comment before storing
		$comment->updateOrdering();

		// Save the comment
		$state = $comment->store();

		if (!$state) {
			return $this->ajax->reject($comment->getError());
		}

		$resultMessage = JText::_('COM_EASYBLOG_COMMENTS_POSTED_SUCCESS');
		$resultState = 'success';

		// If user registered as well, display a proper message
		if ($register) {
			$resultMessage = JText::_('COM_EASYBLOG_COMMENTS_SUCCESS_AND_REGISTERED');
		}

		if ($comment->isModerated()) {
			$resultMessage = JText::_('COM_EASYBLOG_COMMENT_POSTED_UNDER_MODERATION');
			$resultState = 'info';
		}

		// Process comment subscription
		if ($subscribe && $this->config->get('main_subscription') && $blog->subscription) {
			$subscribeModel = EB::model('Subscription');
			$subscribeModel->subscribe('blog', $blog->id, $email, $name, $this->my->id);
		}

		// Process comment notifications
		$comment->processEmails($comment->isModerated(), $blog);

		// Set the comment depth
		$comment->depth = $this->input->get('depth', 0, 'int');

		// Update the sent flag
		$comment->updateSent();

		// Format the comments
		$result = EB::comment()->format(array($comment));
		$comment = $result[0];

		$language = JFactory::getLanguage();
		$rtl = $language->isRTL();

		$theme = EB::template();
		$theme->set('comment', $comment);
		$theme->set('rtl', $rtl);

		$output = '';

		if ($isCB) {
			// if the is saving from CB plugin, then we need to display the output using different template.
			$output = $theme->output('site/comments/cb.item');
		} else {
			$output = $theme->output('site/comments/default.item');
		}

		return $this->ajax->resolve($output, $resultMessage, $resultState);
	}

	/**
	 * Allows caller to reload recaptcha provided that the previous recaptcha reference
	 * is given. This is to avoid any spams on the system.
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function reloadCaptcha()
	{
		$ajax = EB::ajax();

		// Get the previous captcha id.
		$id   = $this->input->get('previousId', 0, 'int');

		$captcha = EB::table('Captcha');
		$state = $captcha->load($id);

		if ($state) {
			$captcha->delete();
		}

		// Generate a new captcha
		$captcha = EB::table('Captcha');
		$captcha->created = EB::date()->toSql();
		$captcha->store();

		$image = EB::_('index.php?option=com_easyblog&task=captcha.generate&tmpl=component&no_html=1&id=' . $captcha->id, false);

		return $ajax->resolve($image, $captcha->id);
	}

	/**
	 * Allows caller to update comments via ajax
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function update()
	{
		// Check for request forgeries
		EB::checkToken();

		$ajax = EB::ajax();

		// Get the comment object
		$id   = $this->input->get('id', 0, 'int');
		$comment = EB::table('Comment');
		$comment->load($id);

		if (!$id || !$comment->id) {
			return $ajax->reject(JText::_('COM_EASYBLOG_NO_PERMISSION_TO_EDIT_COMMENT'));
		}

		if (!EB::isSiteAdmin() && ($this->acl->get('edit_comment') || $this->my->id != $comment->created_by) && !$this->acl->get('manage_comment') ) {
			return $ajax->reject(JText::_('COM_EASYBLOG_NO_PERMISSION_TO_EDIT_COMMENT'));
		}

		// Get the updated comment
		$message = $this->input->get('message', '', 'default');

		if (!$message) {
			return $ajax->reject(JText::_('COM_EASYBLOG_COMMENTS_EMPTY_COMMENT_NOT_ALLOWED'));
		}

		$comment->comment = $message;

		// Update the comment
		$comment->store();

		// Format the output back
		$output = nl2br($message);
		$output = EB::comment()->parseBBCode($output);

		return $ajax->resolve($output, $message);
	}


	/*
	 * @since 2.0.3300
	 * AJAX method to edit a comment
	 *
	 * @param	int		$id		The comment subject.
	 * @return	string	JSON encoded stiring.
	 */
	public function editComment( $id )
	{
		$config     = EasyBlogHelper::getConfig();
		$my			= JFactory::getUser();
		$ajax		= new Ejax();
		$acl		= EB::acl();

		JTable::addIncludePath( EBLOG_TABLES );
		$comment	= EB::table('Comment');
		$comment->load( $id );


		$tpl = EB::template();
		$tpl->set('comment' , $comment );

		$options = new stdClass();
		$options->title = JText::_('COM_EASYBLOG_DASHBOARD_EDIT_COMMENT');
		$options->content = $tpl->output('site/comments/dialog.edit');

		$ajax->dialog( $options );
		$ajax->send();
	}


	/**
	 * Confirm comment deletion
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function confirmDelete()
	{
		$ajax = EB::ajax();

		// Get the comment object
		$id = $this->input->get('id', 0, 'int');

		$comment = EB::table('Comment');
		$comment->load($id);

		// Check if the user has access to delete comments
		if (($this->my->id == 0 || $this->my->id != $comment->created_by || !$this->acl->get('delete_comment') ) && !EB::isSiteAdmin()) {
			return $ajax->reject(JText::_('COM_EASYBLOG_NO_PERMISSION_TO_EDIT_COMMENT'));
		}

		// Get the return url
		$blog = EB::table('Blog');
		$blog->load($comment->post_id);

		$return = base64_encode($blog->getExternalPermalink(false));

		$theme = EB::template();
		$theme->set('return', $return);
		$theme->set('comment', $comment);

		$output = $theme->output('site/comments/dialog.delete');

		return $ajax->resolve($output);
	}

	/**
	 * Allows caller to like a comment
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function like()
	{
		$ajax = EB::ajax();

		if ($this->my->guest) {
			return $ajax->reject(JText::_('COM_EASYBLOG_NOT_ALLOWED'));
		}

		// Get the comment id
		$id = $this->input->get('id', 0, 'int');

		// Add likes
		$model = EB::model('Comment');
		$likes = $model->like($id, $this->my->id);

		// Get the tooltip string
		$data  = EB::getLikesAuthors($id, 'comment', $this->my->id);


		return $ajax->resolve($data->string, $data->count);
	}

	/**
	 * Allows caller to unlike a comment
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function unlike()
	{
		$ajax = EB::ajax();

		if ($this->my->guest) {
			return $ajax->reject(JText::_('COM_EASYBLOG_NOT_ALLOWED'));
		}

		// Get the comment id
		$id = $this->input->get('id', 0, 'int');

		// Add likes
		$model = EB::model('Comment');
		$likes = $model->unlike($id, $this->my->id);

		// Get the tooltip string
		$data  = EB::getLikesAuthors($id, 'comment', $this->my->id);

		return $ajax->resolve($data->string, $data->count);
	}

	/**
	 * Displays the terms and condition popup
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function terms()
	{
		$ajax = EB::ajax();

		$text = $this->config->get('comment_tnctext');
		$text = nl2br($text);

		$theme = EB::template();
		$theme->set('text', $text);
		$output = $theme->output('site/comments/dialog.terms');

		return $ajax->resolve($output);
	}
}
