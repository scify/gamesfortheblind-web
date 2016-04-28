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
<select name="<?php echo $name;?>[]" multiple="multiple">
    <option value="all"<?php echo in_array('all', $value) ? ' selected="selected"' : '';?>><?php echo JText::_('COM_EASYBLOG_ALL_PARENT_CATEGORIES'); ?></option>
    <?php foreach ($categories as $category) { ?>
        <option value="<?php echo $category->id;?>"<?php echo in_array($category->id, $value) ? ' selected="selected"' : '';?>><?php echo $category->title;?></option>
    <?php } ?>
</select>