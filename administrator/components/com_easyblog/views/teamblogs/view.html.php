<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(JPATH_COMPONENT . '/views.php');

class EasyBlogViewTeamblogs extends EasyBlogAdminView
{
	/**
	 * Method to display all team blogs on the site
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function display($tpl = null)
	{
		$layout = $this->getLayout();

		if (method_exists($this, $layout)) {
			return $this->$layout();
		}

		// @rule: Test for user access if on 1.6 and above
		$this->checkAccess('easyblog.manage.teamblog');

		$this->setHeading('COM_EASYBLOG_TEAMBLOGS_TITLE', '', 'fa-group');

		$filter_state	= $this->app->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_state', 'filter_state', '*', 'word' );
		$search			= $this->app->getUserStateFromRequest( 'com_easyblog.teamblogs.search', 			'search', 			'', 'string' );

		$search			= trim(JString::strtolower( $search ) );
		$order			= $this->app->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_order', 		'filter_order', 	'a.id', 'cmd' );
		$orderDirection	= $this->app->getUserStateFromRequest( 'com_easyblog.teamblogs.filter_order_Dir',	'filter_order_Dir',	'', 'word' );

		//Get data from the model
		$model  = EB::model('TeamBlogs');
		$result = $model->getTeams();
		$teams  = array();

		if ($result) {
			foreach ($result as $row) {
				$team = EB::table('TeamBlog');
				$team->bind($row);

				$teams[] = $team;
			}
		}

		$pagination = $model->getPagination();

		$browse = $this->input->get('browse', 0);
		$browsefunction = $this->input->get('browsefunction', 'insertTag');

		$this->set('browse', $browse);
		$this->set('browsefunction', $browsefunction);
		$this->set('teams', $teams );
		$this->set('pagination', $pagination );
		$this->set('state', JHTML::_('grid.state', $filter_state ) );
		$this->set('search', $search );
		$this->set('order', $order );
		$this->set('orderDirection', $orderDirection );

		parent::display('teamblogs/default');
	}

	/**
	 * Displays the team blog form
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function form()
	{
		// Try to load the team object
		$id = $this->input->get('id', 0, 'int');

		// Try to load up the team blog
		$team = EB::table('TeamBlog');
		$team->load($id);

		if ($id) {
			$this->setHeading('COM_EASYBLOG_EDITING_TEAMBLOG', '', 'fa-users');
		} else {
			$this->setHeading('COM_EASYBLOG_TEAMBLOGS_CREATE_TITLE', '', 'fa-users');
		}

		$blogAccess = array();
		$blogAccess[] = JHTML::_('select.option', '1', JText::_( 'COM_EASYBLOG_TEAM_MEMBER_ONLY' ) );
		$blogAccess[] = JHTML::_('select.option', '2', JText::_( 'COM_EASYBLOG_ALL_REGISTERED_USERS' ) );
		$blogAccess[] = JHTML::_('select.option', '3', JText::_( 'COM_EASYBLOG_EVERYONE' ) );
		$blogAccessList = JHTML::_('select.genericlist', $blogAccess, 'access', ' class="form-control"', 'value', 'text', $team->access );

		// Get the editor
		$editor = JFactory::getEditor();

		// get meta tags
		$metaModel = EB::model('Metas');
		$meta = $metaModel->getMetaInfo(META_TYPE_TEAM, $id);

		// Get a list of team members
		$members = $team->getMembers();

		// Get the groups associated with this team
		$groups = $team->getGroups();

		$this->set('groups', $groups);
		$this->set('members', $members);
		$this->set('editor', $editor);
		$this->set('team', $team);
		$this->set('meta', $meta);
		$this->set('blogAccess', $blogAccessList);

		parent::display('teamblogs/form');
	}

	/**
	 * Displays a list of team requests
	 *
	 * @since	4.0
	 * @access	public
	 */
	public function requests()
	{
		$this->setHeading('COM_EASYBLOG_TEAMBLOGS_REQUESTS_TITLE', '', 'fa-group');

		// Retrieve all the team requests
		$model 		= EB::model('TeamRequest');
		$requests	= $model->getData();

		if ($requests) {
			foreach ($requests as &$request) {
				$request->user 	= EB::user($request->user_id);
			}
		}

		// Get the pagination
 		$pagination 	= $this->get( 'Pagination' );

 		$this->set('requests', $requests);
 		$this->set('pagination', $pagination);

		parent::display('teamblogs/requests');
	}

	/**
	 * Registers the toolbar at the back end.
	 *
	 * @since	4.0
	 * @access	public
	 * @return
	 */
	public function registerToolbar()
	{
		$layout 	= JRequest::getCmd('layout');

		if ($layout == 'form') {

			$id 	= JRequest::getInt('id');

			if ($id) {
				JToolBarHelper::title(JText::_('COM_EASYBLOG_EDITING_TEAMBLOG'), 'teamblogs' );
			} else {
				JToolBarHelper::title(JText::_('COM_EASYBLOG_CREATE_NEW_TEAM'), 'teamblogs');
			}

			JToolBarHelper::apply('teamblogs.apply');
			JToolBarHelper::save('teamblogs.save');
			JToolBarHelper::save2new('teamblogs.savenew');
			JToolBarHelper::cancel('teamblogs.cancel');

			return;
		}

		if ($layout == 'requests') {
			JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TEAMBLOGS_TITLE' ), 'teamblogs' );

			JToolbarHelper::custom('teamblogs.approve', 'publish', '', JText::_('COM_EASYBLOG_TEAMBLOGS_APPROVE_REQUEST'));
			JToolbarHelper::custom('teamblogs.reject', 'unpublish', '', JText::_('COM_EASYBLOG_TEAMBLOGS_REJECT_REQUEST'));
			return;
		}

		JToolBarHelper::title( JText::_( 'COM_EASYBLOG_TEAMBLOGS_TITLE' ), 'teamblogs' );

		JToolbarHelper::addNew();
		JToolBarHelper::divider();
		JToolbarHelper::publishList('teamblogs.publish');
		JToolbarHelper::unpublishList('teamblogs.unpublish');
		JToolBarHelper::divider();
		JToolbarHelper::deleteList(JText::_('COM_EASYBLOG_TEAMBLOGS_DELETE_CONFIRM'), 'teamblogs.remove');
	}
}
