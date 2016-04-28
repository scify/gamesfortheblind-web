<?php
/**
 * @package			JUserTube
 * @version			8.1
 *
 * @author			Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link			http://www.srizon.com
 * @copyright		Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license			http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined( '_JEXEC' ) or die;
SrizonResourceLoader::load_mag_popup();
SrizonResourceLoader::load_srizon_custom_css();
if ($this->show_page_heading) echo '<h2 class="contentheading">' . $this->page_heading . '</h2>';
if(strlen($this->pre_text)) echo '<div class="pre_text">'.nl2br($this->pre_text).'</div>';
echo '<div class="srizon-ju-container srz-clearfix jtubegallery" id="'.$this->scroller_id.'">';

$paddingclass = ' padding'.$this->thumbpadding;
$roundingclass = ' '.$this->roundingclass;
$shadowclass = ' '.$this->shadowclass;
$outerwidthclass = ' outerwidthlarge'.$this->thumbsinarowl.' outerwidthsmall'.$this->thumbsinarows;
if($this->vidicon == 'yes') $vidicon = '<div class="vid_icon"></div>';
else $vidicon = '';

foreach($this->videos as $video){
	if(strpos($video['link'],'&')) $minlink = substr($video['link'],0,strpos($video['link'],'&'));
	else $minlink = $video['link'];
	$link = '<a class="magpopif" href="'.$minlink.'">';
	$img_res = 'img_'.$this->thumbres;
	if(! isset($video[$img_res])){
		if(isset($video['img_high']) && $img_res == 'img_standard'){
			$video[$img_res] = $video['img_high'];
		}
		else $video[$img_res] = $video['img_medium'];
	}
	$imgcode = str_replace("<img","<img alt=\"".$video['title']."\"",$video[$img_res]);
	$imgcode = str_replace('alt=""','',$imgcode);

	if($this->show_date){
		$datecode = '		<p class="pub_date"><i>'.date($this->date_format,strtotime($video['date'])).'</i></p>';
	}
	else{
		$datecode = '';
	}

	// show the thumb
	echo '<div class="yt-fp-outer'.$outerwidthclass.$roundingclass.'">';
	echo  '<div class="yt-fp-padding'.$paddingclass.'">';
	echo     '<div class="imgbox fpthumb'.$shadowclass.'" >'.$link.$imgcode.$vidicon.'</a></div>';
	if($this->showtitlethumb == 'yes'){
		if($this->truncate_len!='' and strlen($video['title'])>$this->truncate_len){
			$video['title'] = substr($video['title'],0,$this->truncate_len).'...';
		}
		if($this->truncate_len2!='' and strlen($video['desc'])>$this->truncate_len2){
			$video['desc'] = substr($video['desc'],0,$this->truncate_len2).'...';
		}
		echo '<div class="titlebelowthumb" style="height:'.$this->titlethumb_height.'px">'.$link.$video['title'].'</a>';
		echo '<p>'.$datecode.$video['desc'].'</p></div>';
	}
	echo   '</div>';
	echo '</div>';
}
echo '</div>';

if(strlen($this->post_text)) echo '<div class="post_text">'.nl2br($this->post_text).'</div>';

srizon_show_pagination($this->totalvid, $this->totalvidall, $this->scroller_id, $this->paging_id);
