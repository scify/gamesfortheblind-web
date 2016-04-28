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
<div class="adsense-wrap <?php echo (isset($alignment)) ? $alignment : ''; ?>" style="<?php echo (isset($alignment)) && $alignment == ' alignright' ? 'float:right;' : 'float:left;';?>">
    <script type="text/javascript"><!--
    <?php echo html_entity_decode("$code\n"); ?>
    //--></script>
    <script type="text/javascript" src="https://pagead2.googlesyndication.com/pagead/show_ads.js"></script>
</div>
