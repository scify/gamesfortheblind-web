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

require_once(__DIR__ . '/table.php');

class EasyBlogTablePost extends EasyBlogTable
{
	public $id = null;
	public $created_by = null;
	public $modified = null;
	public $created = null;
	public $publish_up = null;
	public $publish_down = null;
	public $title = null;
	public $permalink = null;
	public $intro = null;
	public $content = null;
	public $document = null;
	public $category_id = null;
	public $published = null;
	public $state = null;
	public $ordering = null;
	public $vote = null;
	public $hits = null;
	public $access = null;
	public $allowcomment = null;
	public $subscription = null;
	public $frontpage = null;
	public $isnew = null;
	public $blogpassword = null;
	public $latitude = null;
	public $longitude = null;
	public $address = null;
	public $posttype = null;
	public $robots = null;
	public $copyrights = null;
	public $image = null;
	public $language = null;
	public $send_notification_emails = null;
	public $locked = false;
	public $ip = null;
	public $doctype = null;
	public $revision_id = null;
	public $source_id = null;
	public $source_type = null;

	public function __construct(&$db)
	{
		parent::__construct('#__easyblog_post', 'id', $db);
	}

	/**
	 * Loads a blog post
	 *
	 * @since	4.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function load($id=null, $reset=true)
	{
		// Load post from post table
		$state = parent::load($id);

        // Posts without doctypes are legacy posts.
        if (is_null($this->doctype)) {
            $this->doctype = 'legacy';
        }

		return $state;
	}

	/**
	 * override save method
	 *
	 * @since	5.0
	 * @access	public
	 * @param	string
	 * @return
	 */
	public function store($updateNulls = false)
	{
		$state = false;

		// if created_by is empty, we do not want to save this record.
		if ($this->created_by) {
			$state = parent::store($updateNulls);
		}

		return $state;
	}


}
