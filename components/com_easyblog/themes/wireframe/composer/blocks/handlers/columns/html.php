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
?>
<div class="row" data-responsive="400,300,200,100">
    <div class="col col-md-6" data-size="6">
        <div class="ebd-nest" data-type="block" data-col-wrapper>
            <?php echo EB::blocks()->renderEditableBlock(EB::blocks()->createBlock('text', array(), array('nested' => true)));?>
        </div>
    </div>
    <div class="col col-md-6" data-size="6">
        <div class="ebd-nest" data-type="block" data-col-wrapper>
            <?php echo EB::blocks()->renderEditableBlock(EB::blocks()->createBlock('text', array(), array('nested' => true)));?>
        </div>
    </div>
</div>
