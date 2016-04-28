<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Chad Smith, http://twitter.com/chadsmith, http://detectmobilebrowsers.com/
 * @license   unlicensed, http://unlicense.org/
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' ); ?>

<div id="legibility-switcher-module" class="accessibility-module accessibility-module_align-left"><section>
	
	<?php
	//Print the heading:
	echo '<h'.$accessibilityPanelHeadingsLevel.' class="accessibility-module-heading">';
	echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_GRAPHICMODE_HEADER;
	echo '</h'.$accessibilityPanelHeadingsLevel.'>'
	?>
	
	<ul>
		<li>
			<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?graphicMode=default" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_GRAPHICMODE_DEFAULT_TITLE; ?>"  id="default-font-style-button" class="grey-button-style rounded-corners show-tooltip">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_GRAPHICMODE_DEFAULT_CONTENT; ?>
			</a>
		</li>
		
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_BEST_LEGIBILITY_MODE=="true"){ ?>
		<li>
			<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?graphicMode=best" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_GRAPHICMODE_BEST_TITLE; ?>" id="best-reading-button" class="grey-button-style rounded-corners show-tooltip">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_GRAPHICMODE_BEST_CONTENT; ?>
			</a>
		</li>
		<?php } ?>
		
	</ul>
	
</section></div>