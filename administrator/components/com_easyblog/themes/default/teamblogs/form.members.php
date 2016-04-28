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
?>
<p><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_MEMBERS_DESC'); ?></p>

<div id="members-container">
    <ul class="teamblog-member-list list-unstyled" data-members-list>
        <?php if ($members) { ?>
            <?php foreach ($members as $member) { ?>
                <?php echo $this->output('admin/teamblogs/member.item', array('user' => JFactory::getUser($member->user_id), 'isAdmin' => $member->isadmin)); ?>
            <?php } ?>
        <?php } ?>
    </ul>
</div>

<div class="mt-20">
    <a class="btn btn-primary" href="javascript:void(0);" data-browse-members>
        <i class="fa fa-plus-circle"></i>&nbsp; <?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_ADD_MEMBER');?>
    </a>
</div>

<div style="display: none;" data-team-member-template>
    <li data-team-member class="members-item" data-id="" id="">
        <input type="hidden" name="members[]" value="" data-member-id />

        <span class="normal-member">  
            <a class="remove_item" href="javascript:void(0);" data-remove-member>
                <i class="fa fa-times-circle"></i>
            </a>
            <span data-member-name></span>

            <a href="javascript:void(0);" class="set_admin" data-set-admin><?php echo JText::_('COM_EASYBLOG_TEAMBLOGS_SET_ADMIN');?></a>
        </span>
    </li>
</div>