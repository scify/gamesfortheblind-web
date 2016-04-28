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
<div class="eb-composer-toolbar">
    <div>
        <?php echo $this->output('site/composer/document/toolbar/messages'); ?>
        <?php echo $this->output('site/composer/document/toolbar/document'); ?>
        <?php echo $this->output('site/composer/document/toolbar/block_actions'); ?>
        <?php echo $this->output('site/composer/document/toolbar/block_drop'); ?>
        <?php echo $this->output('site/composer/document/toolbar/block_move'); ?>
    </div>
</div>