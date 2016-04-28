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

require_once(dirname(__FILE__) . '/model.php');

class EasyBlogModelPostTag extends EasyBlogAdminModel
{
	/**
	 * Constructor
	 *
	 * @since 1.5
	 */
	function __construct()
	{
		parent::__construct();
	}

	/**
	 * Retrieves a list of tags associated with a post
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function getBlogTags($id, $published = true)
	{
		$db = EB::db();

		$query = array();

		$query[] = 'SELECT a.' . $db->quoteName('id') . ', a.' . $db->quoteName('title') . ', a.' . $db->quoteName('alias');
		$query[] = 'FROM ' . $db->quoteName('#__easyblog_tag') . ' AS a';
		$query[] = 'LEFT JOIN ' . $db->quoteName('#__easyblog_post_tag') . ' AS b';
		$query[] = 'ON a.' . $db->quoteName('id') . '= b.' . $db->quoteName('tag_id');
		$query[] = 'WHERE b.' . $db->quoteName('post_id') . '=' . $db->Quote($id);

		if ($published) {
			$query[] = 'AND a.' . $db->quoteName('published') . '=' . $db->Quote(1);
		}

		$query = implode(' ', $query);
		$db->setQuery($query);

		$result = $db->loadObjectList();

		if (!$result) {
			return array();
		}

		$tags = array();

		foreach ($result as $row) {
			$tag = EB::table('Tag');
			$tag->bind($row);


			$tags[] = $tag;
		}

		return $tags;
	}


	/**
	 * This method reduces the number of query hit on the server
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return	
	 */
	public function preload($postIds = array())
	{
		if (!$postIds) {
			return $postIds;
		}

		$db = EB::db();


		$query = array();

		$query[] = 'SELECT a.' . $db->qn('id') . ', a.' . $db->qn('title') . ', a.' . $db->qn('alias') . ', b.' . $db->qn('post_id');
		$query[] = 'FROM ' . $db->qn('#__easyblog_tag') . ' AS a';
		$query[] = 'LEFT JOIN ' . $db->qn('#__easyblog_post_tag') . ' AS b';
		$query[] = 'ON a.' . $db->qn('id') . '= b.' . $db->qn('tag_id');
		$query[] = 'WHERE b.' . $db->qn('post_id') . ' IN(' . implode(',', $postIds) . ')';
		$query[] = 'AND a.' . $db->qn('published') . '=' . $db->Quote(1);

		$query = implode(' ', $query);

		$db->setQuery($query);
		$result = $db->loadObjectList();

		$tags = array();

		// Initialize the values first
		foreach ($postIds as $id) {
			$tags[$id] = array();
		}

		if ($result) {
			foreach ($result as $item) {
				$tag = EB::table('Tag');
				$tag->bind($item);

				$tags[$item->post_id][] = $tag;
			}
		}
		
		return $tags;
	}

	function add( $tagId , $blogId , $creationDate )
	{
		$db				= EasyBlogHelper::db();

		$obj			= new stdClass();
		$obj->tag_id	= $tagId;
		$obj->post_id	= $blogId;
		$obj->created	= $creationDate;

		return $db->insertObject( '#__easyblog_post_tag' , $obj );
	}

	function savePostTag($value)
	{
		$db	= EasyBlogHelper::db();

		$query	= 'INSERT INTO ' . $db->nameQuote('#__easyblog_post_tag') . ' '
								 . '(' . ' '
								 . $db->nameQuote('tag_id') . ', '
								 . $db->nameQuote('post_id') . ', '
								 . $db->nameQuote('created') . ' '
								 . ') ' . ' '
				. 'VALUES ' . $value;

		$db->setQuery($query);
		$result	= $db->Query();

		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}

	function deletePostTag($blogId)
	{
		$db	= EasyBlogHelper::db();

		$query	= ' DELETE FROM ' . $db->nameQuote('#__easyblog_post_tag')
				. ' WHERE ' . $db->nameQuote('post_id') . ' =  ' . $db->quote($blogId);

		$db->setQuery($query);
		$result	= $db->Query();

		if($db->getErrorNum()){
			JError::raiseError( 500, $db->stderr());
		}

		return $result;
	}

	/**
	 * Tests if a particular tag id is associated with a blog post already.
	 *
	 * @access	public
	 * @param	int		$blogId		The blog id.
	 * @param	int		$tagId		The tag id.
	 * @return	boolean				True if exists, false otherwise.
	 */
	public function isAssociated( $blogId , $tagId )
	{
		$db		= EB::db();
		$query	= 'SELECT COUNT(1) FROM';
		$query	.= ' ' . $db->nameQuote( '#__easyblog_post_tag' );
		$query	.= ' WHERE ' . $db->nameQuote( 'post_id' ) . '=' . $db->Quote( $blogId );
		$query	.= ' AND ' . $db->nameQuote( 'tag_id' ) . '=' . $db->Quote( $tagId );

		$db->setQuery( $query );
		$exists	= $db->loadResult() >= 1;

		return $exists;
	}
}
