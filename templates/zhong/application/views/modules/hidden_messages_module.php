<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' ); ?>

<?php
/*----------------------------------------------------------------
-  HIDDEN MESSAGE FOR SCREEN READER USERS
---------------------------------------------------------------- */
//If the users use a screen reader they're advised to switch to "full-access layout".
//The message is shown only the first time the user is visiting the website and only in default layout or mobile layout
//IMPORTANT: the message is HIDDEN to crawlers
if( /*!isset($_SESSION['firstVisit']) &&*/
    (ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="mobile-layout") &&
    ZHONGFRAMEWORK_PARAMETER_ENABLE_SCREENREADER_HIDDEN_MESSAGE=="true" &&
    !ZHONGFRAMEWORK_IS_CLIENT_CRAWLER ): ?>

	<div id="message-screen-users-block" class="visually-hidden"><section>
		<h1><?php echo ZHONGFRAMEWORK_LANGUAGE_SCREEN_READER_MESSAGE_HEADING; ?></h1>
		<?php echo ZHONGFRAMEWORK_LANGUAGE_SCREEN_READER_MESSAGE; ?>
	</section></div>

<?php endif; ?>

<?php
/*----------------------------------------------------------------
-  HIDDEN MESSAGE FOR IE USERS
---------------------------------------------------------------- */
/* If the user is using an obsolete browser (IE<7) a non obstructive message is shown.
The user is advised to download an appropriate browser.
The message is shown only the first time the user is visiting the website ( only in default layout mode ) */
if( !isset($_SESSION['firstVisit']) &&
    ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" &&
    ZHONGFRAMEWORK_PARAMETER_SHOW_MESSAGE_IEUSERS=="true" ): ?>

	<!--[if lte IE 7]>
		<div class="obsolete-browser-alert">
			<div class="obsolete-browser-alert-inner">
				<?php if(ZHONGFRAMEWORK_PARAMETER_SHOW_MESSAGE_IEUSERS=="true") {
						echo ZHONGFRAMEWORK_LANGUAGE_IEMESSAGE_DOWNLOAD_FIREFOX_CONTENT;
						}
					?>
			</div>
			<a href="#" id="hide-IE-message">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_HIDE_THIS_MESSAGE_CONTENT; ?>
			</a>
		</div>
		<script type="text/javascript">
			jQuery('#hide-IE-message').click(function(){
				jQuery('.obsolete-browser-alert').hide();
				});
		</script>
	<![endif]-->
	<!--[if lte IE 6]>
		<div class="obsolete-browser-alert">
			<div class="obsolete-browser-alert-inner">
				<?php 
					echo ZHONGFRAMEWORK_LANGUAGE_IEMESSAGE_CONTENT;
					?>
			</div>
			<a href="#" id="hide-IE-message">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_HIDE_THIS_MESSAGE_CONTENT; ?>
			</a>
		</div>
	<![endif]-->
	
<?php endif; ?>

<?php $_SESSION['firstVisit']=false; ?>
