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
<span class="input-append">
    <input type="text" id="<?php echo $id;?>_name" readonly="readonly" value="<?php echo $title; ?>" disabled="disabled" class="input-large disabled" data-author-title />
    <a rel="{handler: 'iframe', size: {x: 900, y: 500}}" href="<?php echo JRoute::_( 'index.php?option=com_easyblog&view=bloggers&tmpl=component&browse=1&browsefunction=insertBlogger' );?>" class="modal btn btn-primary">
        <i class="icon-user"></i> <?php echo JText::_('COM_EASYBLOG_SELECT_AUTHOR'); ?>
    </a>
</span>

<input type="hidden" id="<?php echo $id;?>_id" name="<?php echo $name;?>" value="<?php echo $value;?>" data-author-id />

<script type="text/javascript">
EasyBlog.ready(function($){

    window.insertBlogger = function(id, title) {

        $('[data-author-id]').val(id);
        $('[data-author-title]').val(title);
        SqueezeBox.close();
    }
});
</script>