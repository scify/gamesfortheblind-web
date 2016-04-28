<?php
/*------------------------------------------------------------------------
# JoomShaper Accordion Module by JoomShaper.com
# ------------------------------------------------------------------------
# author    JoomShaper http://www.joomshaper.com
# Copyright (C) 2010 - 2012 JoomShaper.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.joomshaper.com
-------------------------------------------------------------------------*/
// no direct access
defined('_JEXEC') or die('Restricted access');
?>
<script type="text/javascript">
window.addEvent('domready', function() {
	var myAccordion<?php echo $uniqid; ?> = new Fx.Accordion(document.getElements('#accordion_sp1_id<?php echo $uniqid; ?> .toggler'), document.getElements('#accordion_sp1_id<?php echo $uniqid; ?> .sp-accordion-inner'), {
		opacity: <?php echo $opacity ?>,
		<?php if ($hidefirst) { ?>
		display:-1,
		<?php } ?>
		alwaysHide: true,		
		onActive: function(toggler){
			toggler.addClass('active');
		},
		onBackground: function(toggler){
			toggler.removeClass('active');
		}
	});
});
</script>
<div id="accordion_sp1_id<?php echo $uniqid; ?>" class="sp-accordion sp-accordion-<?php echo $style ?>">
	<?php foreach ( $list as $item ) { ?>
		<div class="toggler">
			<span><span><?php echo $item->title; ?></span></span>
		</div>
		<div class="clr"></div>
		<div class="sp-accordion-inner">
			<p></p>
			<?php if ($showauthor || $showdate) { ?>
				<div class="sp-accordion-info">
					<?php if ($showauthor ) echo '<small class="sp-accordion-author">' . JText::_('WRITTEN') . ' ' . $item->author . '</small>'; ?>
					<?php if ($showauthor ) echo '<small class="sp-accordion-date">' . JText::_('JON') . ' ' . Jhtml::_('date', $item->created, JText::_($date_format)) . '</small>'; ?>
				</div>
			<?php } ?>
			<?php echo $item->introtext; ?>
			<div class="clr"></div>
			<?php if ($showreadon ) { ?>
				<a class="readmore" href="<?php echo $item->link; ?>"><span><?php echo jText::_('SP_READ_MORE') ?></span></a>
			<?php } ?>
			<p></p>
		</div>
	<?php } ?>
</div>