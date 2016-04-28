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

class EasyBlogBlockHandlerReadmore extends EasyBlogBlockHandlerAbstract
{
    public $icon = 'fa fa-ellipsis-h';
    public $nestable = false;

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
        //readmore is not consider a content. always return false.
        return false;
    }

    /**
     * Renders the output of the block in viewing mode
     *
     * @since   5.0
     * @access  public
     * @param   string
     * @return
     */
    public function getHtml($block, $textOnly = false)
    {
        // Since read more blocks shouldn't be visible on the site, we don't need output anything here.
        return '';
    }

    public function data()
    {

        $data = (object) array();


        return $data;
    }
}
