<?php
defined('_JEXEC') or die('Restricted access');
?>
<div id="socialTab">

	<p class="twitter-stuff">
		<?php if ($this->twitterName) : ?>
			<img src="<?php echo JURI::root(); ?>/media/com_joomailermailchimpintegration/backend/views/subscriber/twitter.png" alt="twitter logo"/>
		<?php echo $this->twitterName; ?>

			<span class="twitter-links">
				<a href="http://twitter.com/#!/<?php echo $this->twitterName ?>" target="_blank">
				<?php echo JText::_('twitter profile'); ?>
			</a>
				|
			<a href="http://twitter.com/#search?q=@<?php echo $this->twitterName ?>" target="_blank">
				<?php echo JText::_('@Mention'); ?>
			</a>
		</span>
		<?php else : ?>
			This user has not provided any twitter information
		<?php endif; ?>
	</p>
	<p class="twitter-stuff">
		<?php if ($this->facebookName) : ?>
			<img src="<?php echo JURI::root(); ?>/media/com_joomailermailchimpintegration/backend/views/subscriber/facebook.png" alt="facebook" />
			<a href="http://www.facebook.com/profile.php?id=<?php echo $this->facebookName ?>" target="_blank">
				Facebook profile
			</a>
		<?php else : ?>
			This user has not provided any facebook information
		<?php endif; ?>
	</p>
</div>