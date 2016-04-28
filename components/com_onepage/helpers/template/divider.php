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
if($style=="none"){
	$st = "border:none;";
}else{
	$st= "border:1px solid #333;";
}

?>
<div class='st-divider <?php echo $class; ?>' <?php echo ($margin!='')?"style='padding:{$margin}px 0;{$st}'":"style='{$st}'"; ?> ></div>
