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
<script type="text/javascript">
    $( document ).ready(function() {     
       // $('#preview').load(link+' '+value);
        <?php 
        foreach($pageitem as $items) { 
            $mystring = $items->link;
            $findme   = 'http';
            $pos = strpos($mystring, $findme);            
            if ($pos === false) {
                $url = JURI::base().$items->link;
            } else {
                $url = $items->link;
            }
        ?>  
        var value = '<?php echo $items->value ?>';
        $( "#item-<?php echo $items->id ?>" ).load( "<?php echo $url ?>" + ' ' +value );   
        <?php } ?>       
        
    }); 
    
</script>
<div class="onepage<?php echo $moduleclass;?>">  
<?php foreach($pageitem as $items) { ?>  
    <?php if ($showtitle != 0) : ?>
        <h3 class="page-title">
            <span><?php echo $items->title; ?></span>
        </h3>
    <?php endif; ?> 
    <div id="item-<?php echo $items->id ?>" class="grid-contener"></div>    
<?php } ?>
</div>
