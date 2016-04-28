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
<div class="eb-composer-view eb-composer-blocks" data-eb-composer-view data-name="blocks" data-eb-composer-blocks>
    <?php echo $this->output('site/composer/blocks/toolbar'); ?>
    <?php echo $this->output('site/composer/blocks/viewport'); ?>
    <div class="hide" data-eb-block-template>
        <?php echo $this->output('site/document/blocks/editable'); ?>
    </div>
</div>