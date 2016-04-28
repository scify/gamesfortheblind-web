<?php
/**
* @package      EasyBlog
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

require_once(dirname(__FILE__) . '/abstract.php');

class EasyBlogBlockHandlerLinks extends EasyBlogBlockHandlerAbstract
{
    public $icon = 'fa fa-external-link';

    public function meta()
    {
        static $meta;

        if (isset($meta)) {
            return $meta;
        }

        $meta = parent::meta();

        // We do not want to display the font attributes and font styles
        $meta->properties['fonts'] = false;

        return $meta;
    }

    public function data()
    {
        $data = new stdClass();
        $data->title = '';
        $data->content = '';
        $data->url = '';
        $data->images = array();
        $data->image = '';
        $data->showImage = true;

        return $data;
    }

    /**
     * Validates if the block contains any contents
     *
     * @since   5.0
     * @access  public
     * @param   string
     * @return
     */
    public function validate($block)
    {
        // if no url specified, return false.
        if (!isset($block->data->url) || !$block->data->url) {
            return false;
        }

        return true;
    }

    /**
     * We need to format the block data before passing it back to json_encode
     *
     * @since   5.0
     * @access  public
     * @param   string
     * @return  
     */
    public function normalizeData($data)
    {
        // Ensure that title and content is proper entity
        if (isset($data->title) && $data->title) {
            $data->title = htmlentities($data->title);
        }

        if (isset($data->content) && $data->content) {
            $data->content = htmlentities($data->content);
        }

        return $data;
    }

    /**
     * Standard method to format the output for displaying purposes
     *
     * @since   4.0
     * @access  public
     * @param   string
     * @return
     */
    public function getHtml($block, $textOnly = false)
    {
        if ($textOnly) {
            return;
        }

        // If there's no data associated with the block skip this
        if (!isset($block->data) || !$block->data) {
            return;
        }

        return $block->html;

        // $theme = EB::template();
        // $theme->set('data', $block->data);
        // $contents = $theme->output('site/blogs/blocks/links');

        return $contents;
    }
}
