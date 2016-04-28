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
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

$engine = JPATH_ADMINISTRATOR . '/components/com_easyblog/includes/easyblog.php';

if (!JFile::exists($engine)) {
    return;
}

// Include the syndicate functions only once
require_once ($engine);
require_once(__DIR__ . '/helper.php');


// Ensure that all scripts are loaded
EB::init('module');

// Attach modules stylesheet
EB::stylesheet('module')->attach();

$db = EB::db();

$limit = (INT)trim($params->get('count', 0));
$filter = $params->get('excludeemptypost', 0)? 'showbloggerwithpost' : 'showallblogger';
$sort = $params->get('ordertype', 'latest');
$featuredOnly = $params->get('onlyfeatured', 0);

$model = EB::model('Blogger');
// $bloggers = $model->getBloggers($sort, $limit, $filter, '', array(), array(), $featuredOnly);


$bloggers = modLatestBloggerHelper::getBloggers($sort, $limit, $filter, $featuredOnly);


$config = EB::config();

if (!empty($bloggers)) {
    foreach ($bloggers as $row) {

	    $blogger = EB::user($row->id);
        $row->profile = $blogger;

        $biography = $blogger->getBiography();
        $biographyTotal = JString::strlen(strip_tags($biography));
        $bioLength = $params->get('bio_length', 50);

        if ($biographyTotal > $bioLength) {
            $biography = JString::substr($biography, 0, $bioLength) . '...';
        }

        $row->biography = $biography;
    }//end foreach
}

require(JModuleHelper::getLayoutPath('mod_easybloglatestblogger'));
