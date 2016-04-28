<?php 
defined('_JEXEC') or die('Restricted access'); ?>

<div id="jomSocialGroups">
	<p>
		User Belongs to the following groups:
	</p>
	<ul>
		<?php
		if (is_array($this->jomSocialGroups)) {
			foreach($this->jomSocialGroups as $key => $group) {
				$class = 'groupLeft';
				$link = JRoute::_(JURI::root().'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id);
				$name = $group->name;
				if (JString::strlen($group->name) > 30) {
					$name = JString::substr($group->name, 0, 30) . '...';
				}
				echo '<li class="'. $class .'"><a href="'.$link.'" target="_blank">' . $name . '</a></li>';
				echo (($key%2) == 1) ? '<div class="clear-both"></div>' : '';
			}
		}
		?>
	</ul>
	<div class="clear-both"></div>
</div>
<div id="jomSocialDiscussions">
	<p>
	Discussions that the user has started: <b><?php echo ($this->totalDiscussionsOfUser); ?></b>
	</p>
	<p>
		<?php echo JText::_('Recent discussions'); ?>:
	</p>
	<ul>
		<?php
			if (is_array($this->jomSocialDiscussions)) {
				foreach($this->jomSocialDiscussions as $key => $discussion) {
					$link = JRoute::_(JURI::root().'index.php?option=com_community&view=groups&task=viewgroup&groupid='.$group->id);
					$name = $discussion->title;
					if (JString::strlen($discussion->title) > 30) {
						$name = JString::substr($discussion->title , 0, 30) . '...';
					}
					$link = JRoute::_(JURI::root().'index.php?option=com_community&view=groups&task=viewdiscussion&groupid='.$discussion->groupid.'&topicid='.$discussion->id);
					echo '<li class="groupLeft"><a href="'.$link.'" target="_blank">' . $name . '</a></li>';
					echo (($key%2) == 1) ? '<div class="clear-both"></div>' : '';
				}
			}
		?>
	</ul>
</div>