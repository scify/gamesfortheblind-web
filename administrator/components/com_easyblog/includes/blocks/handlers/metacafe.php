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

require_once(dirname(__FILE__) . '/abstract.php');

class EasyBlogBlockHandlerMetaCafe extends EasyBlogBlockHandlerAbstract
{
    public $icon = 'icon-videos-metacafe';

    public function meta()
    {
        static $meta;

        if (isset($meta)) {
            return $meta;
        }

        $meta = parent::meta();

        // We do not want to display the font attributes and font styles
        $meta->properties['fonts'] = false;
        $meta->properties['textpanel'] = false;

        $template = EB::template();
        $meta->player = $template->output('site/composer/blocks/handlers/metacafe/player');
        $meta->wrapper = $template->output('site/composer/blocks/handlers/metacafe/wrapper');

        return $meta;
    }

    /**
     * Defines the data structure for this block
     *
     * @since   5.0
     * @access  public
     * @param   string
     * @return
     */
    public function data()
    {
        $data = new stdClass();

        // The url to the video
        $data->author = '';
        $data->url = '';
        $data->width = '';
        $data->height = '';
        $data->fluid = true;
        $data->embed = '';

        return $data;
    }

    public function getEditableHtml($block)
    {
        return;
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
     * Standard method to format the output for displaying purposes
     *
     * @since   4.0
     * @access  public
     * @param   string
     * @return
     */
    public function getHtml($block, $textOnly = false)
    {
        // If configured to display text only, nothing should appear at all for this block.
        if ($textOnly) {
            return;
        }

        $uid = uniqid();

        // Need to ensure that we have the "source"
        if (!isset($block->data->source) || !$block->data->source) {
            return;
        }

        $template = EB::template();
        $template->set('data', $block->data);
        $contents = $template->output('site/blogs/blocks/metacafe');

        return $contents;
    }
}
