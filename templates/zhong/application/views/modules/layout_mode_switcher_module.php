<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Chad Smith, http://twitter.com/chadsmith, http://detectmobilebrowsers.com/
 * @license   unlicensed, http://unlicense.org/
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' ); ?>

<div id="layout-switcher-module" class="accessibility-module accessibility-module_align-left"><section>

	<?php
	//Print the heading:
	echo '<h'.$accessibilityPanelHeadingsLevel.' class="accessibility-module-heading">';
	echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_HEADER;
	echo '</h'.$accessibilityPanelHeadingsLevel.'>'
	?>
	
	<ul>
		<li>
			<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutMode=default" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_DEFAULT_TITLE; ?>" id="default-mode-switcher" class="grey-button-style rounded-corners show-tooltip">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_DEFAULT_CONTENT; ?>
				<?php 
				//If default-layout or full-access, then add a hidden description of the layout (useful for svreen readers)
				if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="full-access"){
					echo '<span class="visually-hidden">. '.ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_DEFAULT_TITLE.'.</span>';
					}
				?>
			</a>
		</li>
		
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_FULL_ACCESS_LAYOUT=="true"){ ?>
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutMode=full-access" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_FULLACCESS_TITLE; ?>" id="full-access-mode-button" class="grey-button-style rounded-corners show-tooltip">
					<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_FULLACCESS_CONTENT; ?>
					<?php
					//If default-layout or full-access, then add a hidden description of the layout (useful for svreen readers)
					if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="full-access"){
						echo '<span class="visually-hidden">. '.ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_FULLACCESS_TITLE.'.</span>';
						}
					?>
				</a>
			</li>
		<?php } ?>
		
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_HIGH_VISIBILITY_LAYOUT=="true"){ ?>
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutMode=highcontrast" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_HIGHVIS_TITLE; ?>" id="high-contrast-button" class="grey-button-style rounded-corners show-tooltip">
					<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_HIGHVIS_CONTENT; ?>
					<?php
					//If default-layout or full-access, then add a hidden description of the layout (useful for svreen readers)
					if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" || ZHONGFRAMEWORK_LAYOUT_MODE=="full-access"){
						echo '<span class="visually-hidden">. '.ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_MODES_HIGHVIS_TITLE.'.</span>';
						}
					?>
				</a>
			</li>
		<?php } ?>
	</ul>

</section></div>