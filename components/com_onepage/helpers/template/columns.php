<?php 
/*------------------------------------------------------------------------
# com_onepage - XIPAT Onepage component
# version: 1.0
# ------------------------------------------------------------------------
# author    Nguyen Huy Kien - www.xipat.com
# copyright Copyright (C) 2013 www.xipat.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.xipat.com
# Technical Support:  http://www.xipat.com/support.html
-------------------------------------------------------------------------*/
defined('_JEXEC') or die;
?>
<?php if($class!=""){ ?>
	<div class="<?php echo $class; ?>">
<?php } ?>
<div class='row-fluid'>
	<?php foreach($column_new_array as $key => $item){ ?>
		<div class='span<?php echo $item['col']; ?> <?php echo $item['class']; ?>'><?php echo do_xpshortcode($item['content']); ?></div>
	<?php } ?>
</div>
<?php if($class!=""){ ?>
</div>
<?php } ?>