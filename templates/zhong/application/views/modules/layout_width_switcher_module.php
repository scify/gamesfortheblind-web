<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Chad Smith, http://twitter.com/chadsmith, http://detectmobilebrowsers.com/
 * @license   unlicensed, http://unlicense.org/
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' ); ?>

<div id="layout-width-switcher-module" class="accessibility-module accessibility-module_align-right"><section>
	
	<?php
	//Print the heading:
	echo '<h'.$accessibilityPanelHeadingsLevel.' class="accessibility-module-heading">';
	echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_HEADER;
	echo '</h'.$accessibilityPanelHeadingsLevel.'>'
	?>
	
	<script id="dynamic-layout-width-switcher-module_template" type="text/template">
	<ul>
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_LIQUID_LAYOUT_WIDTH=="true"): ?>
		<li>
			<button href="index.php" class="show-tooltip" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_LIQUID_TITLE; ?>" id="liquid-width-button">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_LIQUID_CONTENT; ?>
			</button>
		</li>
		<?php endif; ?>
		
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_FIXED_LAYOUT_WIDTH=="true"): ?>		
		<li>
			<button href="index.php" class="show-tooltip" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FIXED_TITLE; ?>" id="fixed-width-button">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FIXED_CONTENT; ?>
			</button>
		</li>
		<?php endif; ?>	
			
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_FULL_LAYOUT_WIDTH=="true"): ?>		
		<li>
			<button href="index.php" class="show-tooltip" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FULL_TITLE; ?>" id="full-width-button">
				<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FULL_CONTENT; ?>
			</button>
		</li>
		<?php endif; ?>	
	</ul>
	</script>
	<script type="text/javascript">
		//<![CDATA[
		document.write(document.getElementById('dynamic-layout-width-switcher-module_template').innerHTML);
		//]]>
	</script>
	
	<noscript>
		<ul>
		
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_LIQUID_LAYOUT_WIDTH=="true"): ?>		
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutWidth=liquid" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_LIQUID_TITLE; ?>" id="liquid-width-button" class="grey-button-style rounded-corners show-tooltip">
					<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_LIQUID_CONTENT; ?>
				</a>
			</li>
		<?php endif; ?>
					
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_FIXED_LAYOUT_WIDTH=="true"): ?>			
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutWidth=fixed" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FIXED_TITLE; ?>" id="fixed-width-button" class="grey-button-style rounded-corners show-tooltip">
					<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FIXED_CONTENT; ?>
				</a>
			</li>
		<?php endif; ?>
					
		<?php if(ZHONGFRAMEWORK_PARAMETER_ENABLE_FULL_LAYOUT_WIDTH=="true"): ?>			
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?layoutWidth=full" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FULL_TITLE; ?>" id="full-width-button" class="grey-button-style rounded-corners show-tooltip">
					<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_LAYOUT_FULL_CONTENT; ?>
				</a>
			</li>
		<?php endif; ?>
					
		</ul>
	</noscript>

</section></div>