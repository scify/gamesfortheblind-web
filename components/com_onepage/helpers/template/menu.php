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
// no direct access
defined('_JEXEC') or die;
?>
    <div id="onepage-nav" class="<?php echo ($this->menu_mode == 'horizontal') ? 'horizontal' : 'vertical'; ?>">
        <div class="menu-<?php echo ($this->menu_mode == 'horizontal') ? 'horizontal' : 'vertical'; ?>">  
            <ul>
                <li><a onclick="goToByScroll('home');">Home</a></li>
                <?php foreach($this->items as $items) { ?>  
                    <li><a onclick="goToByScroll('<?php echo 'item-'.$items->id ?>');" ><?php echo $items->title ?></a></li>
                <?php } ?>
            </ul>
        </div>  
    </div> 