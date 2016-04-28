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
<div class="st-module module<?php echo $moduleclass; ?>" id="Mod<?php echo $module->id; ?>">
	<div class="module-inner">
		<?php if ($showtitle != 0) : ?>
			<h3 class="module-title">
				<span><?php echo $module->title; ?></span>
			</h3>
		<?php endif; ?>
		<div class="module-ct">
			<?php echo $module->content; ?>
		</div>
	</div>
</div>