<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/

defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_BREADCRUMB_MOD_EXISTS): ?>

<!-- BREADCRUMBS -->
<nav role="navigation">

	<?php //Print section heading
	printSectionHeading(ZHONGFRAMEWORK_LANGUAGE_BREADCRUMBS,2,$ZHONGFRAMEWORK_PARAMETER_CUSTOM_HEADING_LEVEL['locationPath'],false,''); ?>

	<div id="breadcrumbs" class="top-bar-module">
		<?php $parentCMSHandler->printBreadcrumbsModule(); ?>
	</div>

</nav>
<!-- END breadcrumbs -->

<?php endif; ?>
