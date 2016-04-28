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
?>
<div class="eb-help-bookmark" id="bookmark-link">
    <div class="addthis_toolbox addthis_default_style ">
        <a href="http://www.addthis.com/bookmark.php?v=250&amp;pubid=<?php echo $code; ?>" class="addthis_button_compact"><?php echo $text; ?></a>
        <span class="addthis_separator">|</span>
        <a class="addthis_button_preferred_1"></a>
        <a class="addthis_button_preferred_2"></a>
        <a class="addthis_button_preferred_3"></a>
        <a class="addthis_button_preferred_4"></a>
    </div>
    <script type="text/javascript" src="https://s7.addthis.com/js/250/addthis_widget.js#pubid=<?php echo $code; ?>"></script>
</div>