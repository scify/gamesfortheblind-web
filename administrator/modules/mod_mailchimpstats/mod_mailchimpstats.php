<?php
/**
 * Copyright (C) 2015  freakedout (www.freakedout.de)
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * (at your option) any later version.
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

jimport('joomla.filesystem.file');

if (!JFile::exists(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php')) {
    echo '<p style="padding: 15px;">Please install the joomlamailer component!</p>';
    return;
}

$cid = JRequest::getVar('cid', 0);
$document = JFactory::getDocument();
$document->addStyleSheet(JURI::root() . 'media/com_joomailermailchimpintegration/backend/css/campaigns.css');
$document->addStyleSheet(JURI::root() . 'media/mod_mailchimpstats/mailchimpstats.css');
$document->addScript('https://www.google.com/jsapi');
$document->addScript(JURI::root() . 'media/mod_mailchimpstats/mailchimpstats.js');


$lang = JFactory::getLanguage();
$lang->load('com_joomailermailchimpintegration', JPATH_ADMINISTRATOR);

if (!class_exists('joomlamailerMCAPI')) {
    require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/libraries/MCAPI.class.php');
}

require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/models/jmModel.php');
require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/models/campaigns.php');
require_once(JPATH_ADMINISTRATOR . '/components/com_joomailermailchimpintegration/models/main.php');
$model = new joomailermailchimpintegrationModelCampaigns();

$AIM = false;
$clientDetails = $model->getClientDetails();
if (is_array($clientDetails['modules'])) {
    foreach ($clientDetails['modules'] as $mod) {
	    if ($mod['name'] == 'AIM Reports') {
		    $AIM = true;
		    break;
	    }
    }
}

$campaigns = $model->getCampaigns(array('status' => 'sent'), 0, 1000);

if(!isset($campaigns[0])){
	echo '<p style="padding: 15px;">' . JText::_('JM_NO_CAMPAIGN_SENT') . '</p>';
    return;
}

$stats = $model->getCampaignStats($campaigns[$cid]['id']);
$successful = $campaigns[$cid]['emails_sent'] - $stats['soft_bounces'] - $stats['hard_bounces'];

// process opens and open percentage
$opens = $stats['unique_opens'];
$opens_percent = ($successful) ? ($opens / ($successful * 0.01)) : 0;
$opens_percent = round($opens_percent, 2);
// process bounces and bounce percentage
$bounced = $stats['hard_bounces'] + $stats['soft_bounces'];
$bounced_percent =($campaigns[$cid]['emails_sent']) ? ($bounced / ($campaigns[$cid]['emails_sent'] * 0.01)) : 0;
$bounced_percent = round($bounced_percent, 2);
// process not opened and not opened percentage
$not_opened = $campaigns[$cid]['emails_sent'] - $opens - $bounced;
$not_opened_percent =  ($campaigns[$cid]['emails_sent']) ? ($not_opened / ($campaigns[$cid]['emails_sent'] * 0.01)) : 0;
$not_opened_percent = round($not_opened_percent, 2);
// process clicks and click percentage
$clicks = $stats['users_who_clicked'];
$unique_opens = $stats['unique_opens'];
$clicks_per_open = ($unique_opens != 0) ? ($clicks_per_open = round($stats['clicks'] / $unique_opens, 2)) : 0;
$clicks_percent = ($clicks != 0) ? (round($clicks / ($unique_opens * 0.01), 2)) : 0;
// process unsubscribes and unsubscribe percentage
$unsubs = $stats['unsubscribes'];
$unsubs_percent = ($unsubs != 0) ? (round($unsubs / ($campaigns[$cid]['emails_sent'] * 0.01), 2)) : 0;

//    echo '<div style="height: 360px; float:left;">';
//    echo '<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$opens_percent.','.$bounced_percent.','.$not_opened_percent.'&chs=260x360&chdl='.JText::_('Opened').' ('.$opens_percent.'%)|'.JText::_('Bounced').' ('.$bounced_percent.'%)|'.JText::_('Not opened').' ('.$not_opened_percent.'%)&chco=93ccea,5c8ea9,275886" />';
//    echo '</div>';
?>
<script type="text/javascript">
    var mcStats = {
        opens: <?php echo $opens; ?>,
        bounced: <?php echo $bounced; ?>,
        notOpened: <?php echo $not_opened; ?>
    }
</script>
<div id="mcStatsContent">
    <div id="mcStatsSelectContainer">
	    <form action="index.php" method="post" name="mcStatsSelect" id="mcStatsSelect">
	        <select name="cid" onchange="document.mcStatsSelect.submit();">
		        <?php $x = 0; foreach($campaigns as $c){ ?>
		        <option value="<?php echo $x;?>" <?php if($cid==$x) echo 'selected="selected"';?>><?php echo $c['title'];?></option>
		        <?php $x++; } ?>
	        </select>
	    </form>
	    <a class="JMbutton" href="index.php?option=com_joomailermailchimpintegration&view=create"><?php echo JText::_( 'JM_CREATE_CAMPAIGN' ); ?></a>
	    <a class="JMbutton" href="index.php?option=com_joomailermailchimpintegration&view=campaigns"><?php echo JText::_( 'JM_REPORTS_' ); ?></a>
	    <div style="clear:both;"></div>
    </div>

    <div id="mcStatsDetails">
        <h3 style="text-align:center;">
            <?php echo $campaigns[$cid]['title'] . ' (' . $campaigns[$cid]['subject'] . ')'; ?>
        </h3>

        <div id="mcStatsPieChart"></div>

        <div id="detail-stats">
	        <div id="complaints">
                <span id="complaint-count">
                    <?php echo $stats['abuse_reports'] . ' ' . JText::_('JM_COMPLAINTS'); ?>
                </span>
	            <br />
                <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=abuse&cid=<?php echo $campaigns[$cid]['id']; ?>">
	                <?php echo JText::_('JM_VIEW_COMPLAINTS'); ?>
                </a>
	        </div>
	        <ul class="stats-list">
                <li>
	                <span class="value"><?php echo $campaigns[$cid]['emails_sent'];?></span>
	                <?php if ($AIM) { ?>
	                    <span class="name">
                            <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=recipients&cid=<?php echo $campaigns[$cid]['id'];?>">
                                <?php echo JText::_('JM_TOTAL_RECIPIENTS');?>
                            </a>
                        </span><?php
                    } else { ?>
	                    <span class="name"><?php echo JText::_('JM_TOTAL_RECIPIENTS'); ?></span><?php
	                } ?>
                </li>
                <li>
	                <span class="value"><?php echo $successful; ?></span>
	                <span class="name"><?php echo JText::_('JM_SUCCESSFUL_DELIVERIES'); ?></span>
	            </li>
	            <li>
	                <span class="value"><?php echo $stats['forwards']; ?></span>
	                <span class="name"><?php echo JText::_('JM_TIMES_FORWARDED'); ?></span>
	            </li>
                <li>
	                <span class="value"><?php echo $stats['forwards_opens']; ?></span>
	                <span class="name"><?php echo JText::_('JM_FORWARDED_OPENS'); ?></span>
                </li>
                <li>
                    <span class="value">
                        <span class="percent">(<?php echo $opens_percent; ?>%)</span> <?php echo $opens; ?>
                    </span>
                    <span class="name">
                        <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=opened&cid=<?php echo $campaigns[$cid]['id']; ?>">
                            <?php echo JText::_('JM_RECIPIENTS_WHO_OPENED'); ?>
                        </a>
                    </span>
                </li>
                <li>
                    <span class="value"><?php echo $stats['opens']; ?></span>
                    <span class="name"><?php echo JText::_('JM_TOTAL_TIMES_OPENED'); ?></span>
                </li>
                <li>
                    <span class="value"><?php echo substr($stats['last_open'], 0, -3); ?></span>
                    <span class="name"><?php echo JText::_('JM_LAST_OPEN_DATE'); ?></span>
                </li>
                <li>
                    <span class="value">
                        <span class="percent">(<?php echo $clicks_percent; ?>%)</span> <?php echo $stats['users_who_clicked']; ?>
                    </span><?php
                    if ($AIM) { ?>
                        <span class="name">
                            <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=clicked&cid=<?php echo $campaigns[$cid]['id']; ?>">
                                <?php echo JText::_('JM_RECIPIENTS_WHO_CLICKED'); ?>
                            </a>
                        </span><?php
                    } else { ?>
                        <span class="name"><?php echo JText::_('JM_RECIPIENTS_WHO_CLICKED'); ?></span><?php
                    } ?>
                </li>
                <li>
                    <span class="value">
                        <span class="percent"><?php echo $clicks_per_open; ?></span>
                    </span>
                    <span class="name"><?php echo JText::_('JM_CLICKS_UNIQUE_OPEN'); ?></span>
                </li>
                <li>
                    <span class="value"><?php echo $stats['clicks']; ?></span>
                    <span class="name">
                        <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=clickedlinks&cid=<?php echo $campaigns[$cid]['id']; ?>">
                            <?php echo JText::_('JM_TOTAL_CLICKS'); ?>
                        </a>
                    </span>
                </li>
                <li>
                    <span class="value"><?php echo $unsubs; ?></span>
                    <span class="name">
                        <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=unsubscribes&cid=<?php echo $campaigns[$cid]['id']; ?>">
                            <?php echo JText::_('JM_TOTAL_UNSUBSCRIBES'); ?>
                        </a>
                    </span>
                </li>
            </ul>
        </div>
        <div style="clear: both;"></div>
    </div>
</div>
