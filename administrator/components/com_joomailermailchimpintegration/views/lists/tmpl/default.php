<?php
/**
 * Copyright (C) 2015  freakedout (www.freakedout.de)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die('Restricted Access');

JHTML::_('behavior.modal');
JHTML::_('behavior.tooltip');

$ttImage = JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/info.png';

$params = JComponentHelper::getParams('com_joomailermailchimpintegration');
$MCapi  = $params->get('params.MCapi');
$JoomlamailerMC = new JoomlamailerMC();

if (!$MCapi) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing();
    return;
}
if (!$JoomlamailerMC->pingMC()) {
	echo '<table>' . $JoomlamailerMC->apiKeyMissing(1);
    return;
} ?>
<form action="index.php" method="post" id="adminForm"><?php
    if (!isset($this->lists[0])) {
        echo '<h2>' . JText::_('JM_CREATE_A_LIST') . '</h2>';
        return;
    } ?>
    <div id="editcell">
        <table class="adminlist">
	        <thead>
	            <tr>
		        <th width="15">#</th>
		        <th nowrap="nowrap">
		            <?php echo JText::_('JM_NAME'); ?>
		        </th>
		        <th width="100" nowrap="nowrap">
		            <?php echo JText::_('JM_MERGE_FIELDS'); ?>
		        </th>
		        <th width="100" nowrap="nowrap">
		            <?php echo JText::_('JM_CUSTOM_FIELDS'); ?>
		        </th>
		        <th width="100">
		            <?php echo JText::_('JM_LIST_RATING');
		              echo '<a href="http://www.mailchimp.com/kb/article/how-do-you-determine-my-list-rating" target="_blank">';
		              echo '&nbsp;'.JHTML::tooltip(JText::_('JM_TOOLTIP_LIST_RATING'), JText::_('JM_LIST_RATING'), $ttImage, '');
		              echo '</a>';
                        ?>
		        </th>
		        <th width="8%">
		            <?php echo JText::_('JM_SUBSCRIBERS'); ?>
		        </th>
		        <th width="8%">
		            <?php echo JText::_('JM_UNSUBSCRIBED'); ?>
		        </th>
		        <th width="8%">
		            <?php echo JText::_('JM_CLEANED'); ?>
		        </th>
	            </tr>
	        </thead>
	        <?php
	        $k = 0;
	        for ($i = 0, $n = count($this->lists); $i < $n; $i++) {
	            $row = $this->lists[$i];
	            $checked = JHTML::_('grid.id', $i, $row['id']);
	            ?>
	            <tr class="<?php echo "row$k"; ?>">
		            <td align="center">
		                <?php echo $i+1; ?>
		            </td>
		            <td nowrap="nowrap">
		                <a href="index.php?option=com_joomailermailchimpintegration&view=subscribers&listid=<?php echo $row['id'];?>&type=s">
			                <?php echo $row['name']; ?>
		                </a>
		            </td>
		            <td align="center">
		                <a href="index.php?option=com_joomailermailchimpintegration&view=fields&listid=<?php echo $row['id'];?>&name=<?php echo urlencode($row['name']);?>">
                            <?php echo JText::_('JM_MANAGE'); ?>
                        </a>
		            </td>
		            <td align="center">
		                <a href="index.php?option=com_joomailermailchimpintegration&view=groups&listid=<?php echo $row['id'];?>&name=<?php echo urlencode($row['name']);?>">
                            <?php echo JText::_('JM_MANAGE'); ?>
                        </a>
		            </td>
		            <td align="center">
		                <a href="http://www.mailchimp.com/kb/article/how-do-you-determine-my-list-rating" target="_blank" title="<?php echo JText::_('JM_WHAT_IS_LIST_RATING');?>">
			                <span class="ratingBG">
			                    <?php $ratingWidth = $row['list_rating'] * 2 * 10;?>
			                    <span class="rating5" style="width:<?php echo $ratingWidth;?>%"></span>
			                </span>
		                </a>
		            </td>
		            <td align="center">
		                <a href="index.php?option=com_joomailermailchimpintegration&view=subscribers&listid=<?php echo $row['id'];?>&type=s">
			                <?php echo $row['member_count']; ?>
		                </a>
		            </td>
		            <td align="center">
		                <a href="index.php?option=com_joomailermailchimpintegration&view=subscribers&listid=<?php echo $row['id'];?>&type=u">
			                <?php echo $row['unsubscribe_count']; ?>
		                </a>
		            </td>
		            <td align="center">
		                <a href="index.php?option=com_joomailermailchimpintegration&view=subscribers&listid=<?php echo $row['id'];?>&type=c">
			                <?php echo $row['cleaned_count']; ?>
		                </a>
		            </td>
	            </tr><?php
	            $k = 1 - $k;
	        } ?>
	    </table>
    </div>
    <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
    <input type="hidden" name="task" value="" />
    <input type="hidden" name="boxchecked" value="0" />
    <input type="hidden" name="controller" value="lists" />
</form>
