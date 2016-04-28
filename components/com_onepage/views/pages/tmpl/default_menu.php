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
    $checkmenu = explode("[pageitem_desi id='",$this->pageitem->code); 
    $a=array();
    for($j=0;$j<count($checkmenu);$j++){
       $b = explode("' ",$checkmenu[$j]); 
       $a[$j] = (int)$b[0];  
    }  
      
?>
    <div id="onepage-nav" class="<?php echo ($this->menu_mode == 'horizontal') ? 'horizontal' : 'vertical'; ?>">
        <div class="menu-<?php echo ($this->menu_mode == 'horizontal') ? 'horizontal' : 'vertical'; ?>">  
        <nav class="nav"> 
            <button class="btn btn-navbar" onclick="mobilebutton(this);" id="pull">Menu</button>
            <ul>
                <li class="active"><a href="#home">Home</a></li>
                <?php foreach($this->items as $key => $items) {
                    if(array_search($items->id,$a)) {
                    ?>                                     
                    <li><a href="#<?php echo 'item-'.$items->id ?>" ><?php echo $items->title ?></a></li>
                <?php } } ?>
            </ul> 
        </nav>
        </div>  
    </div> 