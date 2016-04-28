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
<div class="eb-composer-panel is-multipanel eb-composer-panel-blocks is-empty" data-eb-composer-panel data-id="blocks">

    <div class="eb-composer-panel-content">
        <?php echo $this->output('site/composer/panel/blocks/block'); ?>
        <?php echo $this->output('site/composer/panel/blocks/removal'); ?>
    </div>
</div>