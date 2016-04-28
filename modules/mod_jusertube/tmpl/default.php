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
SrizonResourceLoader::load_elastislide();
SrizonResourceLoader::load_mag_popup();
if(strlen($pre_text)) echo '<div class="pre_text">'.nl2br($pre_text).'</div>';
echo '<div class="loading-wrap"><ul class="jtubegallery elastislide-list"  id="'.$scroller_id.'">';
$i=0;
for($j=0;$j<count($videos);$j++){
	$video = $videos[$i++];
	if(strpos($video['link'],'&')) $minlink = substr($video['link'],0,strpos($video['link'],'&'));
	else $minlink = $video['link'];
	$link = '<a class="magpopif" title="'.$video['title'].'" href="'.$minlink.'">';
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
	echo '<li>'.$link.$imgcode.'</a></li>';
}
echo '</ul></div>';
srizon_show_pagination($totalvideopagi, $totalvidall, $scroller_id, $paging_id);
echo <<<EOL
<script type="text/javascript">
	jQuery('#{$scroller_id}').matchImgHeight({
		'height': {$targetheight}
	});
	jQuery(window).load(function(){
		jQuery('#{$scroller_id}').unwrap().elastislide({
			speed : {$respslidespeed},
			start : {$respslidestart}
		});
		jQuery('#{$scroller_id}').autoscrollElastislide({
			interval: {$scroll_interval}
		});
	});
</script>
EOL;

if(strlen($post_text)) echo '<div class="post_text">'.nl2br($post_text).'</div>';
