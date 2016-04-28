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

require_once(dirname(__FILE__) . '/table.php');

class EasyBlogTableTeamBlog extends EasyBlogTable
{
	public $id = null;
	public $title = null;
	public $description	= null;
	public $published = null;
	public $created = null;
	public $alias = null;
	public $access = null;
	public $avatar = null;
	public $allow_join = null;

	public function __construct(& $db )
	{
		parent::__construct('#__easyblog_team', 'id', $db);
	}

	public function load($key = null , $permalink = false)
	{
		if (!$permalink) {

			if (EB::cache()->exists($key, 'team')) {
				return parent::bind(EB::cache()->get($key, 'team'));
			}

			return parent::load( $key );
		}

		$db		= $this->getDBO();

		$query	= 'SELECT id FROM ' . $this->_tbl . ' '
				. 'WHERE `alias`=' . $db->Quote( $key );
		$db->setQuery( $query );

		$id		= $db->loadResult();

		// Try replacing ':' to '-' since Joomla replaces it
		if( !$id )
		{
			$query	= 'SELECT id FROM ' . $this->_tbl . ' '
					. 'WHERE `alias`=' . $db->Quote( JString::str_ireplace( ':' , '-' , $key ) );
			$db->setQuery( $query );

			$id		= $db->loadResult();
		}

		return parent::load( $id );
	}

	/**
	 * Override the parent's delete method as we want to apply our own logics
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function delete($pk = null)
	{
		$state = parent::delete($pk);

		// After deleting the team, delete the team members and groups access as well
		$model = EB::model('TeamBlogs');

		// Delete members
		$this->deleteMembers();

		// Delete groups
		$model->deleteGroupRelations($this->id);


		return $state;
	}

	function deleteGroup( $groupId = '')
	{
		// Delete existing members first so we dont have to worry what's changed
		if( $this->id != 0 )
		{
			$db		= EasyBlogHelper::db();
			$query	= 'DELETE FROM `#__easyblog_team_groups` ';
			$query	.= ' WHERE `team_id`=' . $db->Quote( $this->id );

			if(! empty($groupId))
				$query	.= ' AND `group_id`=' . $db->Quote( $groupId );

			$db->setQuery( $query );
			$db->Query();
		}
	}

	/**
	 * Uploads a team avatar
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function uploadAvatar($file)
	{
		$config = EB::config();
		$acl    = EB::acl();

		// Default avatar file name
		$default = 'default_team.png';

		// Construct the storage path of the avatar
		$path = rtrim($config->get('main_teamavatarpath'), '/');

		// Set the relative path
		$relativePath = $path;

		// Set the absolute path
		$absolutePath = JPATH_ROOT . '/' . $path;

		// Check if the folder exists
		EB::makeFolder($absolutePath);

		// Generate a proper file name for the file
		$fileName = md5($file['name'] . JFactory::getDate()->toSql());
		$fileName .= EB::image()->getExtension($file['tmp_name']);

		// Reassign the image name
		$file['name'] = $fileName;

		if (!isset($file['name'])) {
			return $default;
		}

		// Check if the file upload itself contains errors.
		if ($file['error'] != 0) {
			$this->setError($file['error']);
			return false;
		}

		// Construct the relative and absolute file paths
		$relativeFile = $relativePath . '/' . $file['name'];
		$absoluteFile = $absolutePath . '/' . $file['name'];

		// Determine if the user can really upload this file
		$error = '';
		$state = EB::image()->canUpload($file, $error);

		// If user is not allowed to upload image, return proper error
		if (!$state) {
			$this->setError($err);

			return false;
		}

		// Get the old avatar first
		$oldAvatar = $this->avatar;

		$width  = EBLOG_AVATAR_LARGE_WIDTH;
		$height = EBLOG_AVATAR_LARGE_HEIGHT;

		// Load up the simple image library
		$image = EB::simpleimage();
		$image->load($file['tmp_name']);

		// Resize the avatar to our specified width / height
		$image->resizeToFill($width, $height);

		// Store the file now
		$image->save($absoluteFile, $image->image_type);

		$this->avatar = $file['name'];

		// Save the team again
		$state = $this->store();

		// If the team has avatar already, remove it
		if ($oldAvatar && $oldAvatar != $default) {

			$existingAbsolutePath = $absolutePath . '/' . $oldAvatar;

			// Test if the file exists before deleting it
			$exists = JFile::exists($existingAbsolutePath);

			if ($exists) {
				JFile::delete($existingAbsolutePath);
			}
		}

		return $state;
	}

	/**
	 * Method to unpublish teams
	 *
	 * @since	4.0
	 * @access	public
	 * @param	Array 	An array of ids
	 * @return	boolean
	 */
	public function unpublish($pks = null, $state = false)
	{
		return $this->publish($pks, $state);
	}

	function deleteMembers($userId = '')
	{
		// Delete existing members first so we dont have to worry what's changed
		if( $this->id != 0 )
		{
			$db		= EasyBlogHelper::db();
			$query	= 'DELETE FROM #__easyblog_team_users ';
			$query	.= ' WHERE `team_id`=' . $db->Quote( $this->id );
			if(! empty($userId))
				$query	.= ' AND `user_id`=' . $db->Quote( $userId );

			$db->setQuery( $query );
			$db->Query();
		}
	}

	/**
	 * Retrieves a list of team members from this team
	 *
	 * @since	4.0
	 * @access	public
	 * @return	Array
	 */
	public function getMembers()
	{
		static $members = array();

		if (!isset($members[$this->id])) {
			$model = EB::model('TeamBlogs');

			$members[$this->id] = $model->getMembers($this->id);
		}

		return $members[$this->id];
	}

	/**
	 * Returns the total number of members in a team including users from associated joomla groups.
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAllMembersCount()
	{
		static $counter = array();

		if (!isset($counter[$this->id])) {

			if (EB::cache()->exists($this->id, 'teamblogs')) {
				$data = EB::cache()->get($this->id, 'teamblogs');

				if (isset($data['count'])) {
					$counter[$this->id] = $data['count'];
				} else {
					$counter[$this->id] = '0';
				}

			} else {
				$model = EB::model('TeamBlogs');
				$counter[$this->id] = $model->getAllMembersCount($this->id);
			}

		}

		return $counter[$this->id];
	}


	/**
	 * Returns the total number of members in a team
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getMembersCount()
	{
		static $counter = array();

		if (!isset($counter[$this->id])) {
			$model = EB::model('TeamBlogs');

			$counter[$this->id] = $model->getMembersCount($this->id);
		}

		return $counter[$this->id];
	}


	/**
	 * Use @getMembersCount instead
	 *
	 * @deprecated	4.0
	 */
	public function getMemberCount()
	{
		return $this->getMembersCount();
	}

	/**
	 * Determines if the user is a team admin.
	 *
	 * @access	public
	 * @param	int		$userId		The user's id.
	 *
	 * @return	boolean				True if the user is an admin.
	 **/
	public function isTeamAdmin($userId = null)
	{
		$user = JFactory::getUser($userId);
		$db = EB::db();

		// We need to test if the user has admin access.
		$query	= 'SELECT COUNT(1) FROM ' . $db->qn('#__easyblog_team_users') . ' WHERE `team_id`=' . $db->Quote($this->id);
		$query	.= ' AND ' . $db->qn('user_id') . ' = ' . $db->Quote($user->id) . ' AND `isadmin`= ' . $db->Quote(1);
		$db->setQuery($query);

		$isAdmin = $db->loadResult() > 0;

		return $isAdmin;
	}

	/**
	 * Determines if the user or group is part of a team
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isMember($userId, $gid = '', $findTeamAccess = true)
	{
		if( $this->id != 0 )
		{

			// lets check from the cache first.
			if (EB::cache()->exists($this->id, 'teamblogs')) {

				if ($findTeamAccess) {
					// since current logged in user able to see this team, mean the user has the access. just return true.
					return true;
				}

				$data = EB::cache()->get($this->id, 'teamblogs');

				if (isset($data['member'])) {
					if (isset($data['member'][$userId])) {
						return true;
					}
				} else {
					return false;
				}
			}

			$db		= EB::db();

			$gids   = '';
			if( !empty($gid) )
			{
				if( count( $gid ) > 0 )
				{
				    foreach( $gid as $id)
				    {
				        $gids   .= ( empty($gids) ) ? $db->Quote( $id ) : ',' . $db->Quote( $id );
				    }
				}
			}

			$query	= 'SELECT `user_id` FROM `#__easyblog_team_users`';
			$query  .= ' WHERE `team_id`=' . $db->Quote( $this->id );
			$query  .= ' AND `user_id` = ' . $db->Quote( $userId );
			$db->setQuery( $query );

			$result = $db->loadResult();

			//if not found, lets find on the team access.
			if( empty($result) && !empty( $gids ) && $findTeamAccess )
			{
				$query  = 'SELECT count(1) FROM `#__easyblog_team_groups`';
				$query  .= ' WHERE `team_id` = ' . $db->Quote( $this->id );
				$query  .= ' AND `group_id` IN (' . $gids . ')';

				$db->setQuery( $query );
				$result = $db->loadResult();
			}

			return $result;
		}

		return false;
	}

	/**
	 * Binds the posted data with the object
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function bind($data, $ignore = array())
	{
		parent::bind( $data, $ignore );

		if( empty( $this->created ) )
		{
			$date			= EB::date();
			$this->created	= $date->toMySQL();
		}

		// we check the alias only when this teamblog is a new teamblog or the alias is empty
		if (empty($this->id) || empty($this->alias)) {
			jimport( 'joomla.filesystem.filter.filteroutput');

			$i	= 1;
			while( $this->aliasExists() || empty($this->alias) )
			{
				$this->alias	= empty($this->alias) ? $this->title : $this->alias . '-' . $i;
				$i++;
			}

			$this->alias = EBR::normalizePermalink($this->alias);
		}
	}

	function aliasExists()
	{
		$db		= $this->getDBO();

		$query	= 'SELECT COUNT(1) FROM ' . $db->nameQuote( '#__easyblog_team' ) . ' '
				. 'WHERE ' . $db->nameQuote( 'alias' ) . '=' . $db->Quote( $this->alias );

		if( $this->id != 0 )
		{
			$query	.= ' AND ' . $db->nameQuote( 'id' ) . '!=' . $db->Quote( $this->id );
		}
		$db->setQuery( $query );

		return $db->loadResult() > 0 ? true : false;
	}

	/**
	 * Determines if the team is featured
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function isFeatured()
	{
		$featured = EB::isFeatured('teamblog', $this->id);

		return $featured;
	}

	/**
	 * Retrieves the team blog's avatar
	 *
	 * @since	4.0
	 * @access	public
	 * @return	string
	 */
	public function getAvatar()
	{
		// Default team blog avatar
		$link = '/components/com_easyblog/assets/images/default_teamblog.png';

		if ($this->avatar) {

			// Construct the relative path to the avatar
			$link = '/' . EB::image()->getAvatarRelativePath('team') . '/' . $this->avatar;
		}

		// Reconstruct with the site url
		$link = rtrim(JURI::root(), '/') . $link;


		return $link;
	}

	/**
	 * @deprecated since 3.5
	 *
	 */
	function getTeamAdminEmails()
	{
		return EasyBlogHelper::getHelper( 'Notification' )->getTeamAdminEmails( $this->id );
	}

	function allowSubscription($access, $userid, $ismember, $aclallowsubscription=false)
	{
		$allowSubscription = false;

		$config = EasyBlogHelper::getConfig();

		if($config->get('main_teamsubscription', false))
		{
			switch($access)
			{
				case EBLOG_TEAMBLOG_ACCESS_MEMBER:
					if($ismember && $aclallowsubscription)
						$allowSubscription = true;
					else
						$allowSubscription = false;
					break;
				case EBLOG_TEAMBLOG_ACCESS_REGISTERED:
					if($userid != 0 && $aclallowsubscription)
						$allowSubscription = true;
					else
						$allowSubscription = false;
					break;
				case EBLOG_TEAMBLOG_ACCESS_EVERYONE:
					if($aclallowsubscription || (empty($userid) && $config->get('main_allowguestsubscribe')))
						$allowSubscription = true;
					else
						$allowSubscription = false;
					break;
				default:
					$allowSubscription = false;
			}
		}

		return $allowSubscription;
	}

	/**
	 * Get the title of the team
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getTitle()
	{
		return JText::_($this->title);
	}

	/**
	 * Retrieve a list of tags created by this team
	 **/
	public function getTags()
	{
		$db			= EB::db();

		$query		= 'SELECT a.* FROM ' . $db->qn( '#__easyblog_tag' ) .  ' AS a '
					. 'INNER JOIN ' . $db->qn( '#__easyblog_post_tag' ) . ' AS b '
					. 'ON b.' . $db->qn( 'tag_id' ) . '=a.' . $db->qn( 'id' ) . ' '
					. 'INNER JOIN ' . $db->qn( '#__easyblog_post' ) . ' AS d '
					. 'ON d.' . $db->qn( 'id' ) . '=b.' . $db->qn( 'post_id' ) . ' '
					. 'where d.' . $db->qn('source_type') . ' = ' . $db->Quote(EASYBLOG_POST_SOURCE_TEAM) . ' '
					. 'AND d.' . $db->qn( 'source_id' ) . '=' . $db->Quote( $this->id ) . ' '
					. 'AND d.' . $db->qn( 'published' ) . '=' . $db->Quote( EASYBLOG_POST_PUBLISHED ) . ' '
					. 'AND d.' . $db->qn( 'state' ) . '=' . $db->Quote( EASYBLOG_POST_NORMAL ) . ' '
					. 'GROUP BY a.' . $db->qn( 'id' );

		$db->setQuery( $query );

		$rows	= $db->loadObjectList();
		$tags	= array();

		foreach( $rows as $row )
		{
			$tag	= EB::table('Tag');
			$tag->bind( $row );
			$tags[]	= $tag;
		}

		return $tags;
	}

	/**
	 * Retrieves the total number of posts available in this team
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPostCount()
	{
		static $counter = array();

		if (!isset($counter[$this->id])) {

			if (EB::cache()->exists($this->id, 'teamblogs')) {
				$data = EB::cache()->get($this->id, 'teamblogs');

				if (isset($data['postcount'])) {
					$counter[$this->id] = $data['postcount'];
				} else {
					$counter[$this->id] = '0';
				}

			} else {
				$model = EB::model('TeamBlogs');
				$counter[$this->id] = $model->getPostCount($this->id);
			}

		}

		return $counter[$this->id];
	}

	/**
	 * Retrieves a list of categories in this team
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getCategories()
	{
		$model = EB::model('TeamBlogs');
		$total = $model->getCategories($this->id);

		return $total;
	}

	/*
	 * Determines whether the current blog entry belongs to the team.
	 *
	 * @param	int		$entryId	The subject's id.
	 * @return	boolean		True if entry was contributed to the team and false otherwise.
	 */
	public function isPostOwner( $postId )
	{
		if( empty( $postId ) )
		{
		    return false;
		}

	    $db		= EB::db();

		$query = 'select count(1) from ' . $db->qn('#__easyblog_post') . ' as a';
		$query .= ' where a.' . $db->qn('id') . ' = ' . $db->Quote($postId);
		$query .= ' and a.' . $db->qn('source_type') . ' = ' . $db->Quote(EASYBLOG_POST_SOURCE_TEAM);
		$query .= ' and a.' . $db->qn('source_id') . ' = ' . $db->Quote($this->id);


		$db->setQuery( $query );
		$result	= $db->loadResult();

		return $result > 0;
	}

	function getRSS()
	{
		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=teamblog&id=' . $this->id );
	}

	function getAtom()
	{
		return EasyBlogHelper::getHelper( 'Feeds' )->getFeedURL( 'index.php?option=com_easyblog&view=teamblog&id=' . $this->id , true );
	}

	/**
	 * Retrieves the alias of
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getAlias()
	{
		$config = EB::config();
		$alias = $this->alias;

		if ($config->get('main_sef_unicode') || !EBR::isSefEnabled()) {
			$alias = $this->id . '-' . $this->alias;
		}

		return $alias;
	}

	/**
	 * Retrieves rss link for the category
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getRssLink()
	{
		return EB::feeds()->getFeedURL('index.php?option=com_easyblog&view=teamblog&layout=listings&id=' . $this->id, false, 'teamblog');
	}

	/**
	 * Retrieves the permalink of a team blog
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getPermalink()
	{
		$link = EBR::_('index.php?option=com_easyblog&view=teamblog&layout=listings&id=' . $this->id);

		return $link;
	}

	/**
	 * Retrieves the external permalink for this team
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getExternalPermalink()
	{
		static $link = array();

		if (!isset($link[$this->id])) {

			$url = 'index.php?option=com_easyblog&view=teamblog&layout=listings&id=' . $this->id;

			// If blog post is being posted from the back end and SH404 is installed, we should just use the raw urls.
			$sh404 = EBR::isSh404Enabled();

			$link[$this->id] = EBR::getRoutedURL($url, true, true);

			$app = JFactory::getApplication();

			// If this is being submitted from the back end we do not want to use the sef links because the URL will be invalid
			if ($app->isAdmin() && $sh404) {
				$link[$this->id] = rtrim(JURI::root(), '/') . $link[$this->id];
			}
		}

		return $link[$this->id];
	}

	public function getDescription($raw = false)
	{
		return $this->description;
	}

	/**
	 * Retrieves a list of Joomla user groups that is linked to this team
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function getGroups()
	{
		$model 	= EB::model('TeamBlogs');

		$groups	= $model->getUserGroups($this->id);

		return $groups;
	}

	/**
	 * Determines if the team's content is viewable
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function viewable($userId = '')
	{
		$db 	= EB::db();

		if (!$userId) {
			$my 	= JFactory::getUser();
		} else {
			$my 	= JFactory::getUser($userId);
		}

		// For guests, we only allow them to view that is set to "Everyone"
		if ((!$my->id && $this->access == 3) || $this->isMember($my->id)) {
			return true;
		}

		// Try to check against the user groups
		$userGroups 	= EB::getUserGids($my->id);

		// Something is weird because the user doesn't belong to any groups.
		if (!$userGroups) {
			return false;
		}

		// Get a list of groups associated with this team
		$groups = $this->getGroups();

		if (in_array($userGroups, $groups)) {
			return true;
		}

		return false;
	}
}
