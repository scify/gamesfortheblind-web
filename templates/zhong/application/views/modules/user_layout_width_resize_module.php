<?php
/**
 * @package   Zhong - accessibletemplate
 * @version   2.2.0
 * @author    Francesco Zaniol, accessibletemplate - http://www.accessibletemplate.com
 * @copyright Copyright (C) 2011-Present Francesco Zaniol
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 **/
defined('_ZHONGFRAMEWORK') or die( 'Restricted access' );

if(ZHONGFRAMEWORK_LAYOUT_MODE=="default-layout" && ZHONGFRAMEWORK_PARAMETER_ENABLE_USER_LAYOUT_WIDTH_RESIZE=="true"): ?>

<!-- LAYOUT WIDTH RESIZE HANDLERS -->
<div id="layout-width-resize-tool-container">
	<div class="layout-width-resize-handle layout-width-resize-trigger" id="layout-width-resize-handle_left"></div>
	<div class="layout-width-resize-handle layout-width-resize-trigger" id="layout-width-resize-handle_right"></div>
	<div class="layout-width-resize-trigger" id="layout-width-resize-icon">
		<span id="layout-width-resize-icon_arrow-left"></span><span id="layout-width-resize-icon_arrow-right"></span>
	</div>
</div>

<?php endif; ?>