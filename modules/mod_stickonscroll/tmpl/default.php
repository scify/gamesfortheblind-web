<?php
/**
* @package 	mod_stickonscroll - Stick on Scroll
* @version		1.0.1
* @created		October 2013

* @author		PluginValley
* @email		pluginvalley@ymail.com
* @website		http://www.pluginvalley.com
* @support		Forum - http://www.pluginvalley.com/forum.html
* @copyright	Copyright (C) 2012 pluginvalley. All rights reserved.
* @license		http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
*
*/
// no direct access
defined('_JEXEC') or die('');
defined('_JEXEC') or die('');
?>
<style>
#<?php echo $tbarid;?> {
<?php echo $tbarcss; ?>
<?php echo $tbarpos;?>: 0;
}
</style>
<script type="text/javascript">
var std = jQuery.noConflict();
    std(window).load(function(){
      std("#<?php echo $tbarid;?>").sticky({ topSpacing: <?php echo $topspacing; ?> });
    });
</script>
<div id="<?php echo $tbarid;?>">
<?php echo $tbarcontent; ?>
<?php if ($showclose): ?>
<span id="closefbar">X <?php echo JText::_('MOD_FLOATINGTOOLBAR_CLOSE');?></span>
<?php endif; ?>
</div>