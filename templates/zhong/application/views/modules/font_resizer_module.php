<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Chad Smith, http://twitter.com/chadsmith, http://detectmobilebrowsers.com/
 * @license   unlicensed, http://unlicense.org/
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' ); ?>

<div id="font-resizer-module" class="accessibility-module accessibility-module_align-right"><section>

	<?php
	//Print the heading:
	echo '<h'.$accessibilityPanelHeadingsLevel.' class="accessibility-module-heading" id="font-resizer-module-heading">';
	echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_HEADER;
	echo '</h'.$accessibilityPanelHeadingsLevel.'>'
	?>
	
	<script id="dynamic-font-resizer-module_template" type="text/template">
	<ul>
		<li>
			<button href="index.php" class="show-tooltip" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_INCREASE_TITLE; ?>" id="larger-font-button">
				<span class="visually-hidden"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_INCREASE_CONTENT; ?></span>
			</button>
		</li>
		<li>
			<button href="index.php" class="show-tooltip" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_REVERT_TITLE; ?>" id="reset-font-button">
				<span class="visually-hidden"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_REVERT_CONTENT; ?></span>
				</button>
				</li>
		<li>
			<button href="index.php" class="show-tooltip" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_DECREASE_TITLE; ?>" id="smaller-font-button">
				<span class="visually-hidden"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_DECREASE_CONTENT; ?></span>
			</button>
		</li>
	</ul>
	</script>
	<script type="text/javascript">
		//<![CDATA[
		document.write(document.getElementById('dynamic-font-resizer-module_template').innerHTML);
		//]]>
	</script>
	
	<noscript>
		<ul>
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?fontSize=increase" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_INCREASE_TITLE; ?>" id="larger-font-button" class="grey-button-style rounded-corners show-tooltip">
					<span class="visually-hidden"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_INCREASE_CONTENT; ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?fontSize=default" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_REVERT_TITLE; ?>" id="reset-font-button" class="grey-button-style rounded-corners show-tooltip">
					<span class="visually-hidden"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_REVERT_CONTENT; ?></span>
				</a>
			</li>
			<li>
				<a href="<?php echo ZHONGFRAMEWORK_WEBSITE_CURRENT_URI;?>?fontSize=decrease" rel="nofollow" title="<?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_DECREASE_TITLE; ?>" id="smaller-font-button" class="grey-button-style rounded-corners show-tooltip">
					<span class="visually-hidden"><?php echo ZHONGFRAMEWORK_LANGUAGE_ACCESS_BAR_FONT_DECREASE_CONTENT; ?></span>
				</a>
			</li>
		</ul>
	</noscript>

</section></div>