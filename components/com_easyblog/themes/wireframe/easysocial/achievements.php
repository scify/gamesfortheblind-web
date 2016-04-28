<?php
/**
* @package		EasyBlog
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasyBlog is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');
?>
<?php if ($achievements) { ?>
<div class="es-badges">
	<h3><?php echo JText::_('COM_EASYBLOG_ACHIEVEMENTS'); ?></h3>	
	<ul class="es-badges-list reset-list float-list clearfix">
		<?php foreach ($achievements as $badge) { ?>
		<li>
			<a href="<?php echo FRoute::badges(array('id' => $badge->id, 'layout' => 'item'));?>"><img src="<?php echo $badge->getAvatar();?>" width="32" /></a>
		</li>
		<?php } ?>
	</ul>
</div>
<?php } ?>
