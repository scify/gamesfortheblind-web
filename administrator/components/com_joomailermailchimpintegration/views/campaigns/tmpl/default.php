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
require_once(JPATH_COMPONENT . '/libraries/TwitterZoid.php');

$document = JFactory::getDocument();
$params = JComponentHelper::getParams('com_joomailermailchimpintegration');
$MCapi = $params->get('params.MCapi');
$JoomlamailerMC = new JoomlamailerMC();

if (!$MCapi) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing();
    return;
} else if (!$JoomlamailerMC->pingMC()) {
    echo '<table>' . $JoomlamailerMC->apiKeyMissing(1);
    return;
} else if (!isset($this->items)) { // no campaigns sent yet?
    echo JText::_('JM_NO_RECENT_CAMPAIGNS');
    $i = $n = 1;
} else {
    $AIM = false;
    $clientDetails = $this->getModel('main')->getClientDetails();
    foreach($clientDetails['modules'] as $mod){
        if ($mod['name'] == 'AIM Reports') {
            $AIM = true;
            break;
        }
    } ?>
    <div class="note">
        <?php echo JText::sprintf('JM_REPORTS_DATE', strftime("%Y-%m-%d %H:%M", $this->cacheDate)).' '; ?> <a href="javascript:joomlamailerJS.functions.clearReportsCache()"><?php echo JText::_('JM_CLICK_HERE_TO_REFRESH'); ?></a>
        <span id="cacheLoader"><img src="<?php echo JURI::root();?>media/com_joomailermailchimpintegration/backend/images/loader_16.gif" style="margin: 0 0 0 10px;"/></span>
    </div>

    <script language="javascript" type="text/javascript">
        Joomla.submitbutton = function(pressbutton) {
            if (pressbutton == 'delete') {
                if (confirm('<?php echo JText::_('JM_ARE_YOU_SURE_TO_DELETE_THIS_REPORT');?>?')){
                    joomlamailerJS.functions.preloader();
                    Joomla.submitform(pressbutton);
                }
            } else {
                joomlamailerJS.functions.preloader();
                Joomla.submitform(pressbutton);
            }
        }

        function toggleSlider(id, button, down){
            if (!down){
                jQuery("#"+id).slideUp();
                if (button){
                    jQuery("#"+button).addClass("optionsHeader_rc");
                    jQuery("#"+button)[0].onclick = function(){ toggleSlider(id, button, 1); };
                }
            } else {
                jQuery("#"+id).slideDown();
                if (button){
                    jQuery("#"+button).removeClass("optionsHeader_rc");
                    jQuery("#"+button)[0].onclick = function(){ toggleSlider(id, button); };
                }
            }
        }
    </script>

    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
        google.load('visualization', '1', {'packages':['corechart']});
        var i = 0;
        function drawLineChart(title){

            i = 0;
            var myData = [];
            jQuery(".cid:checked").each(function(){
                myData[i] = [chartData[ jQuery(this).val() ]['name'], chartData[ jQuery(this).val() ][title]];
                i = i+1;
            });
            if (i<=1){
                alert('<?php echo JText::_('JM_PLEASE_SELECT_AT_LEAST_TWO_CAMPAIGNS_FIRST');?>');
                window.location.hash = "campaignList";
            } else {
                jQuery("#comparisonChartTitle").css("display","");
                var width = jQuery("#graph_container").width();

                var data = new google.visualization.DataTable();
                data.addColumn('string', 'Campaign');
                data.addColumn('number', chartTitles[title]);
                data.addRows(myData.reverse());

                var chart = new google.visualization.LineChart(document.getElementById('comparisonChart'));
                chart.draw(data, {width: width, height: 300, is3D: true, title: chartTitles[title], colors:['#ff9012','#35689a'], titleTextStyle: {color: '#c0c0c0'}, legend: 'none' });
                window.location.hash = "comparisonChart";
            }
        }
        function checkAllCampaigns(uncheck){
            var i = 1;
            jQuery(".cid").each(function(){
                if (uncheck){
                    jQuery(this).attr('checked', false);
                    jQuery("#toggle").attr('checked', false);
                    jQuery("#toggle")[0].onclick = function(){ checkAllCampaigns(); };
                    jQuery("#boxchecked").val(0);
                } else {
                    jQuery(this).attr('checked', true);
                    jQuery("#toggle").attr('checked', true);
                    jQuery("#toggle")[0].onclick = function(){ checkAllCampaigns(1); };
                    jQuery("#boxchecked").val(i);
                    i++;
                }
            });
        }
    </script>

    <div id="graph_container"></div>

    <form action="index.php?option=com_joomailermailchimpintegration&view=campaigns" method="post" name="adminForm" id="adminForm">
        <table width="100%">
            <tbody>
                <tr>
                    <td align="right"><?php echo $this->foldersDropDown; ?></td>
                </tr>
            </tbody>
        </table>

        <div id="editcell">

            <table class="adminlist">
                <thead>
                    <tr>
                        <th width="20">
                            <input type="checkbox" name="toggle" id="toggle" value="" onclick="checkAllCampaigns()" />
                            <a name="campaignList"></a>
                        </th>
                        <th nowrap="nowrap">
                            <?php echo JText::_('JM_CAMPAIGN_NAME').' ('.JText::_('JM_SUBJECT').')'; ?>
                        </th>
                        <th width="125" nowrap="nowrap">
                            <?php echo JText::_('JM_SENT_DATE'); ?>
                        </th>
                        <th width="100" nowrap="nowrap">
                            <?php echo JText::_('JM_TOTAL_RECIPIENTS'); ?>
                        </th>
                        <th width="100" nowrap="nowrap">
                            <?php echo JText::_('JM_UNIQUE_OPENS'); ?>
                        </th>
                        <th width="80" nowrap="nowrap">
                            <?php echo JText::_('JM_BOUNCED'); ?>
                        </th>
                        <th width="80" nowrap="nowrap">
                            <?php echo JText::_('JM_UNIQUE_CLICKS'); ?>
                        </th>
                        <th width="80" nowrap="nowrap">
                            <?php echo JText::_('JM_UNSUBSCRIBES'); ?>
                        </th>
                        <th width="80" nowrap="nowrap">
                            <?php echo JText::_('JM_SHARE'); ?>
                        </th>
                    </tr>
                </thead>

                <tfoot>
                    <tr>
                        <td colspan="15">
                            <?php echo $this->pagination->getListFooter(); ?>
                        </td>
                    </tr>
                </tfoot><?php
                $k = 0;
                $i = 0;
                $graphPreload = '';
                $chartData  = "var chartTitles = [];\n";
                $chartData .= "chartTitles['recipients'] = '".JText::_('JM_TOTAL_RECIPIENTS')."';\n";
                $chartData .= "chartTitles['deliveries'] = '".JText::_('JM_SUCCESSFUL_DELIVERIES')."';\n";
                $chartData .= "chartTitles['forwarded'] = '".JText::_('JM_TIMES_FORWARDED')."';\n";
                $chartData .= "chartTitles['forwardedOpens'] = '".JText::_('JM_FORWARDED_OPENS')."';\n";
                $chartData .= "chartTitles['recipientOpens'] = '".JText::_('JM_RECIPIENTS_WHO_OPENED')." (%)';\n";
                $chartData .= "chartTitles['opens'] = '".JText::_('JM_TOTAL_TIMES_OPENED')."';\n";
                $chartData .= "chartTitles['recipientClicks'] = '".JText::_('JM_RECIPIENTS_WHO_CLICKED')." (%)';\n";
                $chartData .= "chartTitles['clicks-opens'] = '".JText::_('JM_CLICKS_UNIQUE_OPEN')."';\n";
                $chartData .= "chartTitles['clicks'] = '".JText::_('JM_TOTAL_CLICKS')."';\n";
                $chartData .= "chartTitles['unsubs'] = '".JText::_('JM_TOTAL_UNSUBSCRIBES')."';\n";

                $chartData .= "var chartData = [];\n";

                if (!count($this->items)) {
                    echo '<tr><td colspan="20">'.JText::_('JM_NO_RECENT_CAMPAIGNS').'</td></tr>';
                }
                foreach ($this->items as $id => $row) {
                    // load individual campaign data
                    //$summary = $this->getModel()->getSummary($row['CampaignID']);
                    $successful = $row['emails_sent'] - $row['stats']['soft_bounces'] - $row['stats']['hard_bounces'];

                    // process opens and open percentage
                    $opens  =  $row['stats']['unique_opens'];
                    $opens_percent = ($successful) ? $opens / ($successful * 0.01) : 0;
                    $opens_percent = round($opens_percent,2);

                    // process bounces and bounce percentage
                    $bounced  = $row['stats']['hard_bounces']+$row['stats']['soft_bounces'];
                    $bounced_percent =($row['emails_sent']) ? $bounced / ($row['emails_sent'] * 0.01) : 0;
                    $bounced_percent = round($bounced_percent,2);

                    // process clicks and click percentage
                    $clicks =  $row['stats']['users_who_clicked'];
                    $unique_opens = $row['stats']['unique_opens'];
                    if ($unique_opens != 0){
                        $clicks_per_open = round($row['stats']['clicks'] / $unique_opens, 2);
                    } else {
                        $clicks_per_open = 0;
                    }
                    if ($clicks != 0) {
                        $clicks_percent = $clicks / ($unique_opens * 0.01);
                        $clicks_percent = round($clicks_percent,2);
                    } else {
                        $clicks_percent  = 0;
                    }
                    // process unsubscribes and unsubscribe percentage
                    $unsubs =  $row['stats']['unsubscribes'];
                    if ($unsubs != 0) {
                        $unsubs_percent = $unsubs / ($row['emails_sent'] * 0.01);
                        $unsubs_percent = round($unsubs_percent,2);
                    } else {
                        $unsubs_percent = 0;
                    }

                    $not_opened         =  $row['emails_sent'] -  $opens -  $bounced;
                    $not_opened_percent =  ($row['emails_sent']) ? $not_opened / ($row['emails_sent'] * 0.01) : 0;
                    $not_opened_percent = round($not_opened_percent,2);

                    $graph[$i]  = '<table width="100%"><tr valign="top"><td>';
                    $graph[$i] .= '<h2>'.JText::_('JM_REPORTS').'</h2>';

                    $graph[$i] .= '<h3>'.$row['title'].' ('.$row['subject'].')</h3>';
                    $graph[$i] .= '<h4>'.JText::_('JM_SENT').' '.substr($row['send_time'], 0, -3).'</h4>'
                    .'<img src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$opens_percent.','.$bounced_percent.','.$not_opened_percent.'&chs=360x360&chdl='.JText::_('JM_OPENED').' ('.$opens_percent.'%)|'.JText::_('JM_BOUNCED').' ('.$bounced_percent.'%)|'.JText::_('JM_NOT_OPENED').' ('.$not_opened_percent.'%)&chco=93ccea,5c8ea9,275886" />';

                    /*
                    $graph[$i] .= '<table style="width:100%"><tr><td style="padding:0 0 0 85px;"><span style="font-size: 25px;color:#ff8800;">'.$row['emails_sent'].'</span></td></tr>'.
                    '<tr><td style="padding:0 0 0 55px;"><span style="font-size: 18px;color:#ff8800;">'.JText::_('JM_MESSAGES_SENT').'</span></td></tr></table>';
                    */
                    /*
                    $graph[$i] .= '<div style="width:150px;text-align:center;padding: 0 0 0 55px;"><span style="font-size: 25px;color:#ff8800;">'.$row['emails_sent'].'</span><br />'.
                    '<span style="font-size: 18px;color:#ff8800;">'.JText::_('JM_MESSAGES_SENT').'</span></div>';
                    */

                    $graph[$i] .= '</td><td width="320" style="padding: 0pt 10px;">';
                    $graph[$i] .= '<h2>'.JText::_('JM_STATS').'</h2>';

                    $graph[$i] .= '<div id="detail-stats" style="float:left;">'
                    . '<div id="complaints">'
                    . '<div id="complaint-count">'
                    .$row['stats']['abuse_reports'].' '.JText::_('JM_COMPLAINTS')
                    . '</div> '
                    . '<a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=abuse&cid='.$id.'">'
                    . JText::_('JM_VIEW_COMPLAINTS').'</a>'
                    . '</div>'
                    . '<ul class="stats-list">'
                    . '<li>'
                    . '<span class="value">'.$row['emails_sent'].'</span>';
                    if ($AIM){
                        $graph[$i] .= '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="recipients" /> <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=recipients&cid='.$id.'">'.JText::_('JM_TOTAL_RECIPIENTS').'</a></span>';
                    } else {
                        $graph[$i] .= '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="recipients" /> '.JText::_('JM_TOTAL_RECIPIENTS').'</span>';
                    }

                    $graph[$i] .= '</li>'
                    . '<li>'
                    . '<span class="value">'.$successful.'</span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="deliveries" /> '.JText::_('JM_SUCCESSFUL_DELIVERIES').'</span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value">'.$row['stats']['forwards'].'</span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="forwarded" /> '.JText::_('JM_TIMES_FORWARDED').'</span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value">'.$row['stats']['forwards_opens'].'</span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="forwardedOpens" /> '.JText::_('JM_FORWARDED_OPENS').'</span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value"> <span class="percent">('.$opens_percent.'%)</span> '.$opens.' </span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="recipientOpens" /> <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=opened&cid='.$id.'">'.JText::_('JM_RECIPIENTS_WHO_OPENED').'</a></span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value">'.$row['stats']['opens'].'</span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="opens" /> '.JText::_('JM_TOTAL_TIMES_OPENED').'</span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value">'.substr($row['stats']['last_open'], 0, -3).'</span>'
                    . '<span class="name"><input type="radio" disabled="disabled" value="" /> '.JText::_('JM_LAST_OPEN_DATE').'</span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value"> <span class="percent">('.$clicks_percent.'%)</span> '.$row['stats']['users_who_clicked'].'</span>';
                    if ($AIM){
                        $graph[$i] .= '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="recipientClicks" /> <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=clicked&cid='.$id.'">'.JText::_('JM_RECIPIENTS_WHO_CLICKED').'</a></span>';
                    } else {
                        $graph[$i] .= '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="recipientClicks" /> '.JText::_('JM_RECIPIENTS_WHO_CLICKED').'</span>';
                    }
                    $graph[$i] .= '</li>'
                    . '<li>'
                    . '<span class="value"> <span class="percent">'.$clicks_per_open.'</span> </span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="clicks-opens" /> '.JText::_('JM_CLICKS_UNIQUE_OPEN').'</span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value">'.$row['stats']['clicks'].'</span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="clicks" /> <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=clickedlinks&cid='.$id.'">'.JText::_('JM_TOTAL_CLICKS').'</a></span>'
                    . '</li>'
                    . '<li>'
                    . '<span class="value">'.$unsubs.'</span>'
                    . '<span class="name"><input type="radio" name="compare" class="compare" onclick="drawLineChart(this.value)" value="unsubs" /> <a href="index.php?option=com_joomailermailchimpintegration&view=campaigns&layout=unsubscribes&cid='.$id.'">'.JText::_('JM_TOTAL_UNSUBSCRIBES').'</a></span>'
                    . '</li>'
                    //		    . '<li class="last">'
                    //		    . '<span class="value">...</span>'
                    //		    . '<span class="name"> '.JText::_('Recipients Who Liked on Facebook').'</a></span>'
                    //		    . '</li>'
                    . '</ul>'
                    . '</div>';

                    $chartData .= "chartData['".$id."'] = [];\n";
                    $chartData .= "chartData['".$id."']['name'] = '".$row["title"]." (".substr($row['send_time'], 0, -9).")';\n";
                    $chartData .= "chartData['".$id."']['recipients'] = ".$row["emails_sent"].";\n";
                    $chartData .= "chartData['".$id."']['deliveries'] = ".$successful.";\n";
                    $chartData .= "chartData['".$id."']['forwarded'] = ".$row["stats"]["forwards"].";\n";
                    $chartData .= "chartData['".$id."']['forwardedOpens'] = ".$row["stats"]["forwards_opens"].";\n";
                    $chartData .= "chartData['".$id."']['recipientOpens'] = ".$opens_percent.";\n";
                    $chartData .= "chartData['".$id."']['opens'] = ".$opens.";\n";
                    $chartData .= "chartData['".$id."']['recipientClicks'] = ".$clicks_percent.";\n";
                    $chartData .= "chartData['".$id."']['clicks-opens'] = ".$clicks_per_open.";\n";
                    $chartData .= "chartData['".$id."']['clicks'] = ".$row["stats"]["clicks"].";\n";
                    $chartData .= "chartData['".$id."']['unsubs'] = ".$unsubs.";\n";

                    $graph[$i] .= '</td><td width="34%">';
                    $graph[$i] .= '<h2>'.JText::_('JM_SOCIAL_MEDIA').'</h2>';

                    // twitter stats
                    $twitter = $row['twitter'];
                    $tweets = 0;
                    if (isset($twitter['twitter']['tweets'])){
                        $tweets = $tweets + (int)$twitter['twitter']['tweets'];
                    }
                    if (isset($twitter['twitter']['retweets'])){
                        $tweets = $tweets + (int)$twitter['twitter']['retweets'];
                    }

                    $graph[$i] .= '<div class="twitterstats">';
                    $graph[$i] .= '<div class="optionsHeader" rel="twitter_'.$id.'"><span>'.$tweets.' '.JText::_('JM_TWEETS_AND_RETWEETS').'</span><div id="toggler_twitter_'.$id.'" class="optionsHeader_r" onclick="toggleSlider(\'twitter_'.$id.'\', \'toggler_twitter_'.$id.'\')"></div></div>';
                    $graph[$i] .= '<div class="optionsContent" id="twitter_'.$id.'">';
                    if ($tweets){
                        foreach($twitter['twitter']['statuses'] as $status){
                            $graph[$i] .= '<div class="tweet">';
                            $graph[$i] .= '<div class="twitterStatus">';
                            if ($status['is_retweet']){
                                $graph[$i] .= '<img style="position:relative;top:3px;" src="'.JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/retweet.png" /> ';
                            }

                            $graph[$i] .= twitterit($status['status'], $status['screen_name']);
                            $graph[$i] .= '</div>';

                            $graph[$i] .= '<span class="twitterInfo">';
                            $graph[$i] .= '<a href="http://twitter.com/'.$status['screen_name'].'" target="_blank" style="text-decoration:none!important;">';
                            $graph[$i] .= $status['screen_name'].'</a> | ';
                            //	$graph[$i] .= $status['datetime'];
                            $graph[$i] .= timesince(strtotime($status['datetime']), substr($status['datetime'],0,-3));

                            $graph[$i] .= '<span style="float:right;">';
                            $graph[$i] .= '<a href="http://twitter.com/home?status='.urlencode($status['status']).'" target="_blank">'.JText::_('JM_RETWEET').'</a>';
                            $graph[$i] .= ' | ';
                            $graph[$i] .= '<a href="http://twitter.com/home?status=@'.$status['screen_name'].'+" target="_blank">'.JText::_('JM_REPLY').'</a>';
                            $graph[$i] .= '<span style="clear:both;"</span>';
                            $graph[$i] .= '</span>';

                            $graph[$i] .= '</span>';
                            $graph[$i] .= '</div>';

                        }
                    }
                    $graph[$i] .= '</div>';
                    $graph[$i] .= '</div>';


                    // geo stats
                    $countries  = $row['geo'];
                    $graph[$i] .= '<div class="countrystats">';
                    $graph[$i] .= '<div class="optionsHeader" rel="countries_'.$id.'"><span>'.count($countries).' '.JText::_('JM_COUNTRIES').'</span><div id="toggler_countries_'.$id.'" class="optionsHeader_r" onclick="toggleSlider(\'countries_'.$id.'\', \'toggler_countries_'.$id.'\')"></div></div>';
                    $graph[$i] .= '<div class="optionsContent" id="countries_'.$id.'">';
                    $graph[$i] .= '<table>';
                    $graph[$i] .= '<tr><td></td><td>'.JText::_('JM_COUNTRY').'</td><td align="center" width="80">'.JText::_('JM_OPENS').'</td></tr>';

                    if (is_array($countries)){
                        $limit = 5;
                        $x = 0;
                        foreach($countries as $c){
                            if ($c['name'] == ''){ $c['name'] = 'unknown'; }
                            $graph[$i] .= '<tr><td width="25">';
                            $graph[$i] .= '<img src="'.JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/flags/' . strtolower($c['code']) . '.png" /></td><td>';
                            $graph[$i] .= JText::_($c['name']).'</td><td align="center">';
                            $graph[$i] .= $c['opens'];
                            $graph[$i] .= '</td></tr>';
                            $x++;
                            if ($x==$limit){ break; }
                        }
                    }
                    $graph[$i] .= '</table>';

                    if (is_array($countries) && count($countries) > 5){
                        $graph[$i] .= '<div id="otherCountries_'.$id.'" style="display: none;">';
                        $graph[$i] .= '<table><tr>';
                        $x = 0;
                        foreach($countries as $c){
                            if ($x >= 5){
                                if ($c['name'] == ''){ $c['name'] = 'unknown'; }
                                $graph[$i] .= '<tr><td width="25">';
                                $graph[$i] .= '<img src="'.JURI::root() . 'media/com_joomailermailchimpintegration/backend/images/flags/' . strtolower($c['code']) . '.png" /></td><td>';
                                $graph[$i] .= JText::_($c['name']).'</td><td align="center" width="80">';
                                $graph[$i] .= $c['opens'];
                                $graph[$i] .= '</td></tr>';
                            }
                            $x++;
                        }
                        $graph[$i] .= '</table>';
                        $graph[$i] .= '</div>';
                        $graph[$i] .= '<a id="otherCountriesShow_'.$id.'" href="javascript:void(0)" onclick="jQuery(\'#otherCountries_'.$id.'\').toggle();jQuery(this).css(\'display\',\'none\');jQuery(\'#otherCountriesHide_'.$id.'\').css(\'display\',\'\');" style="float: right;">'.JText::_('JM_SHOW_ALL').'</a>';
                        $graph[$i] .= '<a id="otherCountriesHide_'.$id.'" style="display:none;" href="javascript:void(0)" onclick="jQuery(\'#otherCountries_'.$id.'\').toggle();jQuery(this).css(\'display\',\'none\');jQuery(\'#otherCountriesShow_'.$id.'\').css(\'display\',\'\');" style="float: right;">'.JText::_('JM_HIDE').'</a>';
                        $graph[$i] .= '<div style="clear:both;"></div>';
                    }

                    $graph[$i] .= '</div>';
                    $graph[$i] .= '</div>';
                    $graph[$i] .= '</td></tr></table>';

                    if (isset($row['advice'][0]) && $row['advice'][0]['msg']) {
                        $msg = str_replace('/reports/export-all','http://us1.admin.mailchimp.com/reports/export-all',$row['advice'][0]['msg']);
                        $graph[$i] .= '<div class="'.$row['advice'][0]['type'].'">'.JText::_('JM_CAMPAIGN_ADVICE').': '.$msg.'</div>';
                    }

                    $graph[$i] .= '<h2 id="comparisonChartTitle" style="display:none;margin-top: 1em;">'.JText::_('JM_COMPARE_STATS').'<a name="comparisonChart"></a></h2>';
                    $graph[$i] .= '<div id="comparisonChart"></div>';

                    $graphPreload .= '<img align="left" src="http://chart.apis.google.com/chart?cht=p&chd=t:'.$opens_percent.','.$bounced_percent.','.$not_opened_percent.'&chs=420x360&chdl='.JText::_('JM_OPENED').' ('.$opens_percent.'%)|'.JText::_('JM_BOUNCED').' ('.$bounced_percent.'%)|'.JText::_('JM_NOT_OPENED').' ('.$not_opened_percent.'%)&chco=9999cc,99cccc,003366" />';

                    $document->addScriptDeclaration("function showGraph_".$i."(){
                        document.getElementById('graph_container').innerHTML = '".addslashes($graph[$i])."';
                        $$('.row0').removeClass('active_row');
                        $$('.row1').removeClass('active_row');
                        $('row".$i."').addClass('active_row');
                    }"); ?>

                    <tr class="<?php echo "row$k"; ?>" id="<?php echo "row$i";?>" style="cursor: pointer;" onclick="showGraph_<?php echo $i;?>();">
                        <td align="center">
                            <input type="checkbox" name="cid[]" class="cid" id="<?php echo "cb$k";?>" value="<?php echo $id;?>" onclick="Joomla.isChecked(this.checked);" />
                        </td>
                        <td>
                            <a href="#" onclick="javascript:showGraph_<?php echo $i;?>();return false;">
                                <?php echo $row['title'].' ('.$row['subject'].')'; ?>
                            </a>
                        </td>
                        <td align="center"><?php echo substr($row['send_time'], 0, -3); ?></td>
                        <td align="center"><?php echo $row['emails_sent']; ?></td>
                        <td align="center"><?php echo $opens.' ('.$opens_percent.'%)'; ?></td>
                        <td align="center"><?php echo $bounced.' ('.$bounced_percent.'%)'; ?></td>
                        <td align="center"><?php echo $row['stats']['users_who_clicked'].' ('.$clicks_percent.'%)'; ?></td>
                        <td align="center"><?php echo $unsubs.' ('.$unsubs_percent.'%)'; ?></td>
                        <td align="center">
                            <a class="modal" rel="{handler: 'iframe', size: {x: 200, y: 200} }" href="<?php echo 'index.php?option=com_joomailermailchimpintegration&view=share&format=raw&url='.$row['archive_url'].'&title='.$row['title'];?>">
                                <img src="../media/com_joomailermailchimpintegration/backend/images/share.png" alt="Share" title="Share" height="17"/>
                            </a>
                        </td>
                    </tr>
                    <?php
                    $k = 1 - $k;
                    $i++;
                } ?>
            </table>
            <?php $document->addScriptDeclaration($chartData); ?>
            <script type="text/javascript">showGraph_<?php echo JRequest::getVar('active', 0, 'get', 'int');?>();</script>
            <input type="hidden" name="option" value="com_joomailermailchimpintegration" />
            <input type="hidden" name="task" value="" />
            <input type="hidden" name="boxchecked" id="boxchecked" value="0" />
            <input type="hidden" name="controller" value="campaigns" />
        </div>
    </form>
    <div style="display: none;"><?php echo $graphPreload;?></div><?php
}
