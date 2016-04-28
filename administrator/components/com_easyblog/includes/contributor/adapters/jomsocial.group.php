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

require_once(__DIR__ . '/abstract.php');

class EasyBlogContributorJomsocialGroup extends EasyBlogContributorAbstract
{
    public $group = null;

	public function __construct($id, $type)
	{
        parent::__construct($id, $type);
        
        JTable::addIncludePath( JPATH_ROOT . '/components/com_community/tables' );
        $group  = JTable::getInstance( 'Group' , 'CTable' );
        $group->load( $id );

        $this->group = $group;
	}

    public function getAvatar()
    {
        return $this->group->getAvatar();
    }

    public function getTitle()
    {
        return $this->group->name;
    }

    public function getPermalink()
    {
        return $this->group->getLink();
    }

    public function getHeader()
    {
        $output = CMiniHeader::showGroupMiniHeader($this->group->id);

        echo $output;
        return $output;
    }
}
