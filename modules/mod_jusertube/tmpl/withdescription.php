<?php
/**
 * @package			JUserTube 
 * @version			8.1
 *
 * @author			Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link			http://www.srizon.com
 * @copyright                   Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
 // no direct access
defined( '_JEXEC' ) or die;
SrizonResourceLoader::load_mag_popup();
SrizonResourceLoader::load_srizon_custom_css();
if(strlen($pre_text)) echo '<div class="pre_text">'.nl2br($pre_text).'</div>';
echo '<div class="jtubegallery srizon-ju-container" id="'.$scroller_id.'">';
foreach ($videos as $video){
	if(strpos($video['link'],'&')) $minlink = substr($video['link'],0,strpos($video['link'],'&'));
	else $minlink = $video['link'];
	$link = '<a class="magpopif" href="'.$minlink.'">';
	$img_res = 'img_'.$thumbres;
	if(! isset($video[$img_res])){
		if(isset($video['img_high']) && $img_res == 'img_standard'){
			$video[$img_res] = $video['img_high'];
		}
		else $video[$img_res] = $video['img_medium'];
	}
    $imgcode = str_replace("<img","<img alt=\"".$video['title']."\"",$video[$img_res]);
    $imgcode = str_replace('alt=""','',$imgcode);
    if($vidicon == 'yes') $imgcode.='<span class="vid_icon"></span>';
    if($truncate_len!='' and strlen($video['title'])>$truncate_len){
        $video['title'] = substr($video['title'],0,$truncate_len).'...';
    }
    
    if($truncate_len2!='' and strlen($video['desc'])>$truncate_len2){
        $video['desc'] = substr($video['desc'],0,$truncate_len2).'...';
    }
	if($show_date){
		$datecode = '		<p class="pub_date"><i>'.date($date_format,strtotime($video['date'])).'</i></p>';
	}
	else{
		$datecode = '';
	}

	echo '<div class="descbox '.$ratioclass.'">';
	echo '	<div class="yt-twd-outer '.$roundingclass.'">';
	echo '		<div class="imgbox twdthumb '.$shadowclass.'" >'.$link.$imgcode.'</a></div>';
    echo '</div>';
	echo ' <div class="titlendesc">';
	echo '		<h5 class="titledesc">'.$link.$video['title'].'</a></h5>';
	echo '		<div class="descdesc">'.$datecode.$video['desc'].'</div>';
	echo '	</div>';
	echo '</div><div class="divider"></div>';
}
echo '<div style="clear:both;"></div> </div>';

srizon_show_pagination($totalvideopagi, $totalvidall, $scroller_id, $paging_id);
if(strlen($post_text)) echo '<div class="post_text">'.nl2br($post_text).'</div>';
