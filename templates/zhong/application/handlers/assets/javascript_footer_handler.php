<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );?>

<!-- JAVASCRIPTs -->

<?php
/*----------------------------------------------------------------
-  INCLUDE THE PHP/JS BRIDGE
---------------------------------------------------------------- */
?>
<script type="text/javascript">
	<?php
	//Load php variables into javascript variables
	require_once(ZHONGFRAMEWORK_ABSOLUTE_PATH_DIR.'application/handlers/PHP_JS_bridge.php');
	?>
</script>

<?php 
/*----------------------------------------------------------------
-  INCLUDE THE MAIN SCRIPTS
---------------------------------------------------------------- */
//If the compressing method is enabled, include the JS aggregator
if(ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS=="true"){
	echo '<script type="text/javascript" src="'.ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/mixers/js/';
	echo 'main.js.php?layoutMode='.ZHONGFRAMEWORK_LAYOUT_MODE.'&amp;graphicMode='.ZHONGFRAMEWORK_GRAPHIC_MODE.'&amp;minify='.ZHONGFRAMEWORK_PARAMETER_ENABLE_MINIFY_METHODS;
	echo '"></script>';
	}
//else, if disabled, include the js directly
else{
	//Include main.js & plugins.js
	echo '<script type="text/javascript" src="'.ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/javascript/plugins.js"></script>';
	echo '<script type="text/javascript" src="'.ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/javascript/main.js"></script>';
	//Include custom JavaScript	
	echo '<script type="text/javascript" src="'.ZHONGFRAMEWORK_WEBSITE_TEMPLATE_URI.'/assets/custom-overrides/js/custom-scripts.js"></script>';
	}

?>

<?php
/*----------------------------------------------------------------
-  WELCOME MESSAGE
---------------------------------------------------------------- */
//Show the welcome message (only in default layout mode)
if( ZHONGFRAMEWORK_PARAMETER_SHOW_WELCOME_MESSAGE=="true"
&& !isset($_SESSION['welcomeMessageShowed'])
&& ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout"
&& ZHONGFRAMEWORK_GRAPHIC_MODE=='default-graphic-mode'
&& ZHONGFRAMEWORK_PARAMETER_ENABLE_ACCESSIBILITY_PANEL=="true"
&& ZHONGFRAMEWORK_PARAMETER_ENABLE_TOP_BAR=="true"): ?>
	
	<?php //Define a template for the message ?>
	<script id="floating-welcome-message" type="text/template">
		<div id="top-bar-preferences-message" aria-live="off" aria-hidden="true">
			<div id="top-bar-preferences-message-inner">
				<p>
					<span id="top-bar-preferences-message-welcome">
						<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_MESSAGE_WELCOME; ?>
					</span>
					<br/>
					<span id="top-bar-preferences-message-clickHere">
						<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_MESSAGE_CLICKHERE; ?>
					</span>
				</p>
			</div>
		<div id="top-bar-preferences-message-arrow"></div>
		</div>
	</script>
	
	<script type="text/javascript">
	jQuery(window).load(function(){
		//On window loaded, add the message to the accessibility button container
		jQuery('#show-accessibility-panel-button-container').append(jQuery('#floating-welcome-message').html());
		//Show the message, then remove it 
		jQuery('#top-bar-preferences-message')
			.fadeOut(0)
			.delay(600)
			.fadeIn(1000)
			.delay(4000)
			.fadeOut(1800,function(){jQuery(this).remove();});
		});
	</script>
	<?php //Set a session variable, so that the message is showed only the first time
	$_SESSION['welcomeMessageShowed']=true; ?>

<?php endif; ?>

<?php
/*----------------------------------------------------------------
-  GOOGLE ANALYTICS
---------------------------------------------------------------- */
if(ZHONGFRAMEWORK_PARAMETER_ENABLE_GOOGLE_ANALYTICS=='true'): ?>

<script>
	var _gaq=[['_setAccount','UA-<?php echo ZHONGFRAMEWORK_PARAMETER_GOOGLE_ANALYTICS_ID; ?>'],['_trackPageview']];
	(function(d,t){var g=d.createElement(t),s=d.getElementsByTagName(t)[0];
	g.src='//www.google-analytics.com/ga.js';
	s.parentNode.insertBefore(g,s)}(document,'script'));
</script>

<?php endif; ?>

<!-- END JS -->