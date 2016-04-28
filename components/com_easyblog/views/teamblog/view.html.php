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

class EasyBlogViewTeamBlog extends EasyBlogView
{
	/**
	 * Default method to display a list of team blogs on the site.
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function display($tmpl = null)
	{
		// Set the breadcrumbs for this view
		$this->setViewBreadcrumb('teamblog');

		// Set meta tags for team blog view
		EB::setMeta(META_ID_TEAMBLOGS, META_TYPE_VIEW);

		// Get the sorting options
		$sort = $this->input->get('sort', $this->config->get('layout_postorder'));

		// Get the team blogs model
		$model = EB::model('TeamBlogs');

		// get viewable teamblogs
		$teams = $model->getTeamBlogs();

		// Load up the pagination object
		$pagination	= $model->getPagination();

		// Format the teams
		$teams = EB::formatter('teamblogs', $teams);

		// Set the page title
		$title = EB::getPageTitle(JText::_('COM_EASYBLOG_TEAMBLOG_PAGE_TITLE'));
		$this->setPageTitle($title, $pagination, $this->config->get('main_pagetitle_autoappend'));

		$this->set('teams', $teams);
		$this->set('pagination', $pagination->getPagesLinks());

		parent::display('teamblogs/default');
	}

	/**
	 * Displays a list of blog posts from a specific team
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function listings()
	{
		// Get the team id that is being accessed now
		$id = $this->input->get('id', 0, 'int');
		$team = EB::table('TeamBlog');
		$team->load($id);

		if (!$id || !$team->id) {
			return JError::raiseError(404, JText::_('COM_EASYBLOG_TEAMBLOG_INVALID_ID'));
		}

		// set meta tags for teamblog view
		EB::setMeta($id, META_TYPE_TEAM);

		$gid = EB::getUserGids();
		$isMember = $team->isMember($this->my->id, $gid);

		$team->isMember = $isMember;
		$team->isActualMember = $team->isMember($this->my->id, $gid, false);

		// Add rss feed link
		if ($team->access == EBLOG_TEAMBLOG_ACCESS_EVERYONE || $team->isMember) {
			$this->doc->addHeadLink($team->getRSS(), 'alternate' , 'rel' , array('type' => 'application/rss+xml', 'title' => 'RSS 2.0') );
			$this->doc->addHeadLink($team->getAtom(), 'alternate' , 'rel' , array('type' => 'application/atom+xml', 'title' => 'Atom 1.0') );
		}

		// check if team description is emtpy or not. if yes, show default message.
		if (empty($team->description)) {
			$team->description = JText::_('COM_EASYBLOG_TEAMBLOG_NO_DESCRIPTION');
		}

		// Set the breadcrumbs for this view
		$this->setViewBreadcrumb('teamblog');
		$this->setPathway($team->getTitle());

		$limit = EB::call('Pagination', 'getLimit', array(EBLOG_PAGINATION_TEAMBLOGS));		

		// Retrieve the model
		$model = EB::model('TeamBlogs');
		$posts = $model->getPosts($team->id, $limit);
		$posts = EB::formatter('list', $posts);

		// Get the pagination
		$pagination	= $model->getPagination();

		// Retrieve team's information
		$members = $model->getTeamMembers($team->id);

		// Determines if the team blog is featured
		$team->isFeatured = EB::isFeatured('teamblog', $team->id);

		// Set the page title
		$title = EB::getPageTitle($team->title);
		$this->setPageTitle($title, $pagination, $this->config->get('main_pagetitle_autoappend'));

		// Check if subscribed
		$isTeamSubscribed = $model->isTeamSubscribedEmail($team->id, $this->my->email);

		// Get the current url
		$return = $team->getPermalink();

		$this->set('return', $return);
		$this->set('team', $team);
		$this->set('members', $members);
		$this->set('pagination', $pagination->getPagesLinks());
		$this->set('posts', $posts);
		$this->set('isTeamSubscribed', $isTeamSubscribed);

		parent::display('teamblogs/item');
	}
}
