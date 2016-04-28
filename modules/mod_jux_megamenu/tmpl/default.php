<?php
/**
 * @version		$Id$
 * @author		JoomlaUX
 * @package		Site
 * @subpackage	mod_jux_megamenu
 * @copyright	Copyright (C) 2008 - 2013 by JoomlaUX Solutions. All rights reserved.
 * @license		http://www.gnu.org/licenses/gpl.html GNU/GPL version 3
 */

// no direct access
defined('_JEXEC') or die('Restricted access');
$navigation_animation=$params->get('navigation_animation');
$animation_duration=$params->get('animation_duration');
$special_id=$params->get('special_id');
// Add some style, we must add it here because it depends on the 'layout' chosen by user.
$style = '#js-mainnav.' . $layout . ' ul.level1 .childcontent { margin: -20px 0 0 ' . ($params->get('megacss-colwidth',200) - 30) . 'px; }';
if($params->get('css3_noJS', 0) && $params->get('responsive_toggle_button', 1)) {
	$style .= '@media screen and (max-width: 767px) {#js-mainnav.megamenu.noJS ul.megamenu li { display: none; }}';
}
JFactory::getDocument()->addStyleDeclaration($style);
?>
<div id="jux_memamenu<?php echo $module->id;?>">
	<div id="megamenucss" class="megamenucss<?php echo $module->id;?>">
		<div id="js-mainnav" class="clearfix <?php echo $menuStyle; ?>  megamenu">
			<?php if($params->get('responsive_toggle_button', 1)) :
				// $toggle_type = !$params->get('css3_noJS', 0) ? 'js' : 'css3';
				$toggle_type = 'CSS3';
			?>
			<div id="<?php echo $toggle_type; ?>-megaMenuToggle" class="megaMenuToggle">
				<i class="jux-fa jux-fa-bars font-item-menu"></i>
			</div>
			<?php endif; ?>
			<?php $dropdownmenu->genMenu (0, -1); ?>
		</div>
	</div>
</div>

<style type="text/css">
	 #jux_memamenu<?php echo $module->id;?> ul.megamenu li.haschild.megacss:hover>div.childcontent.adddropdown,
	 #jux_memamenu<?php echo $module->id;?> .childcontent.open>.dropdown-menu{
	 	opacity:1;
	 	visibility:visible;
	 	display:block;
	 	-moz-animation:<?php echo $navigation_animation?> <?php echo $animation_duration ?>ms ease-in ;
       	-webkit-animation: <?php echo $navigation_animation?> <?php echo $animation_duration ?>ms ease-in ;
       	animation:<?php echo $navigation_animation?> <?php echo $animation_duration ?>ms ease-in ;}
	}
</style>
<script type="text/javascript">
    jQuery(document).ready(function ($) {
        $(".megamenucss<?php echo $module->id;?> #CSS3-megaMenuToggle").click(function () {
             $(".megamenucss<?php echo $module->id;?> .js-megamenu").toggleClass("dropdown-menucss<?php echo $special_id; ?>");
             
           
        });
  	   $(window).resize(function () {
            if (document.body.offsetWidth > 768) {
             	 $(".megamenucss<?php echo $module->id;?> .js-megamenu").removeClass("dropdown-menucss<?php echo $special_id; ?>"); 
            }
            
        });
    });
</script>

<?php

if (!$params->get('css3_noJS', 0)) {
	$stickyAlignment = $params->get('sticky_alignment', 'left');
	if($stickyAlignment == 'sameasmenu') {
		$stickyAlignment = $menuAlignment;
	}
	if($menuOrientation == 'horizontal') {
		$direction	= $params->get('horizontal_submenu_direction', 'down');
	} else {
		$direction	= $params->get('vertical_submenu_direction', 'lefttoright');
	}



	?>

	<?php
}

