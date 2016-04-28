<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(dirname(__FILE__) . '/base.php');

class EasyBlogMigratorWordpress_xml extends EasyBlogMigratorBase
{
	public function migrate($fileName, $authorId)
	{
		$session = JFactory::getSession();

		$file = JPATH_ROOT . '/administrator/components/com_easyblog/xmlfiles/' . $fileName;

		if (JFile::exists($file)) {
			/* use for debugging - dun remove */
			/*
			libxml_use_internal_errors(true);
			var_dump( simplexml_load_file( $file ) );
		    $errors = libxml_get_errors();
			var_dump($errors);
		    foreach ($errors as $error) {
				var_dump($error);
		    }
		    libxml_clear_errors();
			exit;
			*/

			$parser = simplexml_load_file($file);

			if ($parser) {

				$baseUrl = $parser->xpath('/rss/channel/wp:base_site_url');
				$baseUrl = (string) trim($baseUrl[0]);

				$namespaces = $parser->getDocNamespaces();

				if (!isset($namespaces['wp'])) {
					$namespaces['wp'] = 'http://wordpress.org/export/1.1/';
				}

				if (!isset($namespaces['excerpt'])) {
					$namespaces['excerpt'] = 'http://wordpress.org/export/1.1/excerpt/';
				}

				$posts = array();
				$attachments = array();

				// process each items
				foreach ($parser->channel->item as $item) {

					$post = array(
						'post_title' => (string) $item->title,
						'guid' => (string) $item->guid,
						'link' => (string) $item->link,
					);

					$dc = $item->children('http://purl.org/dc/elements/1.1/');
					$post['post_author'] = (string) $dc->creator;

					$content = $item->children('http://purl.org/rss/1.0/modules/content/');
					$excerpt = $item->children($namespaces['excerpt']);

					$post['post_content'] = (string) $content->encoded;
					$post['post_excerpt'] = (string) $excerpt->encoded;

					$wp = $item->children($namespaces['wp']);

					$post['post_id'] = (int) $wp->post_id;
					$post['post_date_gmt'] = (string) $wp->post_date_gmt;
					$post['comment_status'] = (string) $wp->comment_status;
					$post['post_name'] = (string) $wp->post_name;
					$post['status'] = (string) $wp->status; // publish , draft
					$post['post_type'] = (string) $wp->post_type;
					$post['post_parent'] = (string) $wp->post_parent;
					$post['post_password'] = (string) $wp->post_password;
					$post['attachment_url'] = (string) $wp->attachment_url;

					if (($post['post_type'] != 'post') && ($post['post_type'] != 'attachment')) {
						continue;
					}

					if ($post['status'] == 'draft') {
						continue;
					}

					foreach ($item->category as $terms) {
						$att = $terms->attributes();
						if (isset($att['nicename'])){
							$post['terms'][] = array(
								'name' => (string) $terms,
								'slug' => (string) $att['nicename'],
								'domain' => (string) $att['domain']
							);
						}
					}

					foreach ($wp->postmeta as $meta) {
						$post['postmeta'][] = array(
							'key' => (string) $meta->meta_key,
							'value' => (string) $meta->meta_value
						);
					}

					$postComments = array();
					foreach ($wp->comment as $comment) {
						if (empty($comment->comment_content)) {
							continue;
						}

						$postComments[] = array(
							'comment_id' => (int) $comment->comment_id,
							'comment_author' => (string) $comment->comment_author,
							'comment_author_email' => (string) $comment->comment_author_email,
							'comment_author_IP' => (string) $comment->comment_author_IP,
							'comment_author_url' => (string) $comment->comment_author_url,
							'comment_date' => (string) $comment->comment_date,
							'comment_date_gmt' => (string) $comment->comment_date_gmt,
							'comment_content' => (string) $comment->comment_content,
							'comment_approved' => (string) $comment->comment_approved,
							'comment_type' => (string) $comment->comment_type,
							'comment_parent' => (string) $comment->comment_parent,
							'comment_user_id' => (int) $comment->comment_user_id
						);
					} //end foreach

					if ($post['post_type'] == 'attachment') {
						$post_parant = $post['post_parent'];
						$this->logXMLData($fileName, $post_parant, 'attachment',  $post);
					} else {

						$post_id = $post['post_id'];

						if (count($postComments) > 150) {
							$postComments = array_slice($postComments, 0, 150);
						}

						$this->logXMLData($fileName, $post_id, 'post', $post, $postComments);
					}

				} //end foreach

				$this->migrateWPXML($fileName, $authorId);
			}
			else {
				return $this->ajax->resolve('parseFailed');
			}// if parser
		}
		else {
			return $this->ajax->resolve('fileNotExist');
		}
	}

	public function migrateWPXML( $fileName, $authorId )
	{
		$session = JFactory::getSession();

		$migrateStat = $session->get('EBLOG_MIGRATOR_JOOMLA_STAT', '', 'EASYBLOG');

		if (empty($migrateStat)) {
			$migrateStat = new stdClass();
			$migrateStat->blog = 0;
			$migrateStat->category = 0;
			$migrateStat->user = array();
		}

		$posts = $this->getXMLPostData($fileName);

		// if posts is empty, exit.
		if (!$posts) {
			$this->ajax->append('[data-progress-status]', JText::_('COM_EASYBLOG_NO_ITEM') . '<br />');
			return $this->ajax->resolve('noitem');
		}

		foreach ($posts as $post) {

			// If no post_id, just continue to the next item
			if (!isset($post->post_id)) {
			    $this->clearXMLData($fileName, true);
				continue;
			}

			$data = $post->data;
			$contentId = $data['post_id'];

			if (empty($contentId)) {
				$this->clearXMLData($fileName, true);
				continue;
			}

			// Check in easyblog_migrate_content table if already exist
			$row = $this->checkData($fileName, $contentId);

			// If it returns a value means the post is already exist in easyblog_migrate_content
			if (!is_null($row)) {
				continue;
			}

			$date = EB::date();
			$blogObj = new stdClass();
			$adminId = $authorId;

			$wpCat = '';
			$wpTag = array();

			if (!empty($data['terms'])) {
			    foreach ($data['terms'] as $term) {
				    if ($term['domain'] == 'category' && empty($wpCat)) {
				        $wpCat  = new stdClass();
				        $wpCat->title  = $term['name'];
				        $wpCat->alias  = $term['slug'];
				    }
				    else if ($term['domain'] == 'post_tag') {
				        $tmpTag = new stdClass();
				        $tmpTag->title  = $term['name'];
				        $tmpTag->alias  = $term['slug'];
				        $wpTag[] = $tmpTag;
				    }
				}
			}

			//load user profile
			$profile = EB::user($adminId);

			// Migrate caption
			$pattern2 = '/\[caption.*caption="(.*)"\]/iU';
            $data['post_content'] = preg_replace($pattern2 , '<div class="caption">$1</div>' , $data['post_content']);
            $data['post_content'] = str_ireplace('[/caption]' , '<br />' , $data['post_content']);
            $data['comments'] = $post->comments;
			$data['post_excerpt'] = nl2br($data['post_excerpt']);
			$data['post_content'] = nl2br($data['post_content']);

			//translating the article state into easyblog publish status.
			$blogState = '0';
			$isPrivate = '0';
			if ($data['status'] == 'private') {
                $isPrivate = '1';
                $blogState = '1';
			}
			else if ($data['status'] == 'publish') {
                $isPrivate = '0';
                $blogState = '1';
			}

			// Assign category
			$categoryId = 1; //assume 1 is the uncategorized id.

			if (isset($wpCat->title)) {
			    $categoryId = $this->easyblogCategoryExists($wpCat);
			}

			//assigning blog data
			$blogObj->category_id = $categoryId;

			// this is needed because post lib actually use this to create the post - category relations.
			$blogObj->categories = array($categoryId);

			$blogObj->created_by = $profile->id;
			$blogObj->created = !empty($data['post_date_gmt']) ? $data['post_date_gmt'] : $date->toMySQL();
			$blogObj->modified = $date->toMySQL();
			$blogObj->title = $data['post_title'];
			$blogObj->permalink = $data['post_title']; // post lib will take care of the normalization of permalink
			$blogObj->intro = $data['post_excerpt'];
			$blogObj->content = $data['post_content'];
			$blogObj->blogpassword = $data['post_password'];
			$blogObj->access = $isPrivate;
			$blogObj->published = $blogState;
			$blogObj->publish_up = !empty($data['post_date_gmt'])? $data['post_date_gmt'] : $date->toMySQL();
			$blogObj->publish_down = '0000-00-00 00:00:00';
			$blogObj->ordering = 0;
			$blogObj->hits = 0;
			$blogObj->frontpage = 1;
			$blogObj->allowcomment = ($data['comment_status'] == 'open') ? 1 : 0;

            $blogObj->posttype = '';
            $blogObj->source_id = '0';
            $blogObj->source_type = EASYBLOG_POST_SOURCE_SITEWIDE;

			// lets create blank post which are legacy type.
			$post = EB::post();
            $post->create(array('overrideDoctType' => 'legacy'));

            // now let get the uid
            $blogObj->uid = $post->uid;
            $blogObj->revision_id = $post->revision->id;

            // binding
			$post->bind($blogObj, array());

            $saveOptions = array(
                            'applyDateOffset' => false,
                            'validateData' => false,
                            'useAuthorAsRevisionOwner' => true
                            );

			$post->save($saveOptions);

			// add tags.
			if (count($wpTag) > 0) {
				foreach ($wpTag as $item) {
				    $this->saveTag($item, $post);
			    }
			}

			if (!empty($data['comments']) && is_array($data['comments'])) {
				// Sort first
				usort( $data['comments'], array($this, 'sortWPXMLComments'));

				foreach($data['comments'] as $citem) {
					$item = JArrayHelper::toObject($citem);

					if ($item->comment_parent == 0) {
						$this->migrateWPComments('xml', $contentId, $post->id, 0, $item, $data['comments']);
					}
				}
			}

			//update session value
			$migrateStat->blog++;
			$statUser = $migrateStat->user;
			$statUserObj = null;

			if (!isset($statUser[$profile->id])) {
			    $statUserObj = new stdClass();
			    $statUserObj->name = $profile->nickname;
			    $statUserObj->blogcount = 0;
			} else {
			    $statUserObj = $statUser[$profile->id];
			}

			$statUserObj->blogcount++;
			$statUser[$profile->id] = $statUserObj;
			$migrateStat->user = $statUser;

			$session->set('EBLOG_MIGRATOR_JOOMLA_STAT', $migrateStat, 'EASYBLOG');

			//log the entry into migrate table.
			$migratorTable = EB::table('Migrate');

			$migratorTable->content_id = $contentId;
			$migratorTable->post_id = $post->id;
			$migratorTable->session_id = $session->getToken();
			$migratorTable->component = 'xml_wordpress';
			$migratorTable->filename = $fileName;
			$migratorTable->store();

			$this->ajax->append('[data-progress-status]', JText::_('COM_EASYBLOG_MIGRATOR_MIGRATED_WORDPRESS_XML') . ': ' . $data['post_id'] . JText::_('COM_EASYBLOG_MIGRATOR_EASYBLOG') . ': ' . $post->id . '<br />');
		} //end each

		$morePosts = $this->checkXMLPostData($fileName);

		if ($morePosts) {
			$this->migrateWPXML($fileName, $authorId);
		}

		return $this->ajax->resolve('success');
	}

	public function saveTag($item, $blog)
	{
		$now    = EB::date();
		$tag	= EB::table('Tag');

		if($tag->exists($item->title)) {
		    $tag->load( $item->title, true);
		}
		else {
		    $tagArr = array();
		    $tagArr['created_by'] = $blog->created_by;
		    $tagArr['title'] = $item->title;
		    $tagArr['alias'] = $item->alias;
		    $tagArr['published'] = '1';
		    $tagArr['created'] = $now->toMySQL();

            $tag->bind($tagArr);
		    $tag->store();
		}

		$postTag = EB::table('PostTag');
		$postTag->tag_id = $tag->id;
		$postTag->post_id = $blog->id;
		$postTag->created = $now->toMySQL();
		$postTag->store();
	}

	public function checkData($fileName, $contentId)
	{
		$query = 'SELECT content_id FROM `#__easyblog_migrate_content` AS b';
		$query .= ' WHERE b.`content_id` = '. $this->db->Quote( $contentId );
		$query .= '  and `component` = ' . $this->db->Quote( 'xml_wordpress' );
		$query .= '  and `filename` = ' . $this->db->Quote( $fileName );

		$this->db->setQuery($query);
		$row = $this->db->loadResult();

		return $row;
	}

	function migrateWPComments($wpTableNamePrex, $postId, $blogId, $parentId, $item, $comments = array())
	{
		//var_dump('here');exit;
		$now	= EB::date();
		$db		= EB::db();
		$commt	= EB::table('Comment');

		//we need to rename the esname and esemail back to name and email.
		$post = array();
		$post['name'] = (isset($item->comment_author))? $item->comment_author : '';
		$post['email'] = (isset($item->comment_author_email))? $item->comment_author_email : '';
		$post['post_id'] = $blogId;
		$post['comment'] = (isset($item->comment_content))? $item->comment_content : '';
		$post['title'] = '';
        $post['url'] = (isset($item->comment_author_url))? $item->comment_author_url : '';
        $post['ip'] = (isset($item->comment_author_IP))? $item->comment_author_IP : '';
		$commt->bindPost($post);

		$commt->created_by= ($wpTableNamePrex == 'xml')? '0' : $item->user_id;
		$commt->created	= (isset($item->comment_date))? $item->comment_date : '';
		$commt->modified = (isset($item->comment_date))? $item->comment_date : '';
		$commt->published= 1;
		$commt->parent_id = $parentId;
		$commt->sent= 1;

		$commt->store();


		foreach($comments as $citem) {
			$child = JArrayHelper::toObject($citem);

			if ($child->comment_parent == $item->comment_id) {
				$this->migrateWPComments('xml', $postId, $blogId, $commt->id, $child, $comments);
			}
		}
        return true;
	}

	public function sortWPXMLComments($a, $b)
	{
		$date1 = new DateTime($a['comment_date']);
		$date2 = new DateTime($b['comment_date']);

		return $date1 < $date2 ? -1 : 1;
	}

}
