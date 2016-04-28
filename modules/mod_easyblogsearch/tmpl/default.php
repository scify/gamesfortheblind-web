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
?>
<div id="fd" class="eb eb-mod mod-easyblogsearch<?php echo $params->get('moduleclass_sfx'); ?>">
    <form name="search-blogs" action="<?php echo JRoute::_('index.php');?>" method="post">
        <input type="text" name="query" id="search-blogs" class="mod-input" />
        <input type="hidden" name="option" value="com_easyblog" />
        <input type="hidden" name="view" value="search" />
        <button class="mod-btn mod-btn-primary"><?php echo JText::_('MOD_EASYBLOGSEARCH_SEARCH');?></button>
    </form>
</div>
