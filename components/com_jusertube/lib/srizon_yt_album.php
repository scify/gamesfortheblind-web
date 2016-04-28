<?php
/**
 * @package            JUserTube
 * @version            8.1
 * @author             Md. Afzal Hossain <afzal.csedu@gmail.com>
 * @link               http://www.srizon.com
 * @copyright          Copyright 2012 Md. Afzal Hossain All Rights Reserved
 * @license            http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
 */
// no direct access
defined('_JEXEC') or die('Restricted access');

if(!class_exists('SrizonYoutubeFeedReader')) {
	class SrizonYoutubeFeedReader {
		var $cachetime;
		var $filepath;
		var $videos = array();
		var $api_key='AIzaSyBEreAv5Ps2kTKcYDVnUTWARTh_KoLjweA';

		function SrizonYoutubeFeedReader($updatefeed = 600, $new_key = '') {
			$this->cachetime = $updatefeed;
			if (!is_dir(JPATH_CACHE . '/jusertube')) {
				if (is_writable(JPATH_CACHE)) {
					mkdir(JPATH_CACHE . '/jusertube');
				}
			}
			$this->filepath = JPATH_CACHE . '/jusertube/';
			if($new_key) $this->api_key = $new_key;
		}

		protected function get_remote_youtube_album($youtubeuser, $totalvid){
			if ( ! count($this->videos) ) {
				$pl_id = $this->get_pl_id($youtubeuser);
				if($pl_id !== false){
					$this->get_videos_from_pl_id($pl_id,$totalvid);
				}

				if ( ! count($this->videos) and isset( $_GET['debugsrzyt'] ) ) {
					echo 'Looks like your server cannot connect with youtube. Ask your hosting provider to enable remote connection or try it on another server.';
				}
			}
		}

		function get_youtube_top($youtubeuser, $totalvid) {
			if ($this->sync_required($youtubeuser)) {
				$this->get_remote_youtube_album($youtubeuser, $totalvid); // try to sync
				if (count($this->videos)) {
					$this->cache_it($youtubeuser); // sync successful so cache it
				} else {
					$this->read_cache($youtubeuser); // sync unsuccessful try to read from cache
					$this->cache_it($youtubeuser);
				}
			}
			else $this->read_cache($youtubeuser);

			return $this->videos;
		}

		function get_pl_id( $youtubeuser ){
			$api_id = $youtubeuser;
			$type = '';

			// url entered
			if((strpos($api_id,'http://') !== false) or (strpos($api_id,'https://') !== false)){
				$api_id = $this->extract_id_or_username($api_id, $type);
			}
			// bare id or username entered - for legacy support
			else{
				$type = $this->find_type($api_id);
			}

			if($type == 'user'){
				$pl_id = $this->get_user_upload_playlist($api_id);
			}
			else if($type == 'channel'){
				$pl_id = $this->get_channel_upload_playlist($api_id);
			}
			else if($type == 'playlist'){
				$pl_id = $api_id;
			}
			else{
				$this->set_debug_message('Error: ','Channel/Playlist URL seems incorrect - '. $youtubeuser);
				return false;
			}

			return $pl_id;
		}

		function get_videos_from_pl_id($pl_id,$total){
			$i = 0;
			$page_token= '';
			while(true) {
				$url = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&key=' . $this->api_key . '&playlistId=' . $pl_id . '&fields=nextPageToken,pageInfo,items(snippet(publishedAt,title,description,resourceId/videoId,thumbnails))&maxResults=50'.$page_token;
				$this->set_debug_message( 'Trying to get data from:', '<a href="' . $url . '" target="_blank">' . $url . '</a>' );
				$json_str = $this->remote_get($url);
				$json     = json_decode( $json_str->body );

				foreach ( $json->items as $item ) {
					if(!isset($item->snippet->thumbnails)) continue;
					$this->videos[ $i ]['id']    = $item->snippet->resourceId->videoId;
					$this->videos[ $i ]['link']  = 'https://www.youtube.com/watch?v=' . $this->videos[ $i ]['id'];
					$this->videos[ $i ]['title'] = $item->snippet->title;
					$this->videos[ $i ]['date'] = $item->snippet->publishedAt;
					$this->videos[ $i ]['desc']  = $item->snippet->description;
					$this->videos[ $i ]['img_medium']   = '<img src="' . $item->snippet->thumbnails->medium->url . '" />';
					if(isset($item->snippet->thumbnails->high))
						$this->videos[ $i ]['img_high']   = '<img src="' . $item->snippet->thumbnails->high->url . '" />';
					if(isset($item->snippet->thumbnails->standard))
						$this->videos[ $i ]['img_standard']   = '<img src="' . $item->snippet->thumbnails->standard->url . '" />';
					if(isset($item->snippet->thumbnails->maxres))
						$this->videos[ $i ]['img_maxres']   = '<img src="' . $item->snippet->thumbnails->maxres->url . '" />';
					$i ++;
				}
				if($i>$total) break;
				if(isset($json->nextPageToken)) $page_token = '&pageToken=' . $json->nextPageToken;
				else break;
			}
		}

		function get_user_upload_playlist($userid){
			$url = 'https://www.googleapis.com/youtube/v3/channels?forUsername='.$userid.'&key='.$this->api_key.'&part=contentDetails&fields=items/contentDetails/relatedPlaylists';
			$this->set_debug_message('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
			$json_str = $this->remote_get($url);
			$json = json_decode($json_str->body);
			if(isset($json->error)){
				$this->set_debug_message('Error:',$json->error->message);
				return false;
			}
			else if(isset($json->items[0]->contentDetails->relatedPlaylists->uploads)){
				$api_id = $json->items[0]->contentDetails->relatedPlaylists->uploads;
				$this->set_debug_message('Got the ID:',$api_id);
				return $api_id;
			}
			else{
				$this->set_debug_message('Error:','No Uploads Found');
				return false;
			}
		}

		function get_channel_upload_playlist($channel_id){
			$url = 'https://www.googleapis.com/youtube/v3/channels?id='.$channel_id.'&key='.$this->api_key.'&part=contentDetails&fields=items/contentDetails/relatedPlaylists';
			$this->set_debug_message('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
			$json_str = $this->remote_get($url);
			$json = json_decode($json_str->body);
			if(isset($json->error)){
				$this->set_debug_message('Error:',$json->error->message);
				return false;
			}
			else if(isset($json->items[0]->contentDetails->relatedPlaylists->uploads)){
				$api_id = $json->items[0]->contentDetails->relatedPlaylists->uploads;
				$this->set_debug_message('Got the ID:',$api_id);
				return $api_id;
			}
			else{
				$this->set_debug_message('Error:','No Uploads Found');
				return false;
			}

		}

		function extract_id_or_username($url, &$type){
			//first change https links to http
			$url = str_replace('https:', 'http:', $url);

			//handle format https://www.youtube.com/user/trailers
			if(strpos($url,'http://www.youtube.com/user/') !== false){
				$url = str_replace('http://www.youtube.com/user/','',$url);
				$type = 'user';
			}

			//handle format https://www.youtube.com/channel/UCK7eHebP6b5JbkpX6zvJkRQ

			else if(strpos($url,'http://www.youtube.com/channel/') !== false){
				$url = str_replace('http://www.youtube.com/channel/','',$url);
				$type = 'channel';
			}

			//handle playlist
			else if(strpos($url,'list=') !== false){
				$url = parse_url($url);
				parse_str($url['query'],$parsed_url);
				$url = $parsed_url['list'];
				$type = 'playlist';
			}
			else{
				$type = 'unknown';
			}

			return $url;
		}

		function find_type($id){
			// see if it's a username
			$url = 'https://www.googleapis.com/youtube/v3/channels?forUsername='.$id.'&key='.$this->api_key.'&part=id';
			$this->set_debug_message('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
			$json_str = $this->remote_get($url);
			$json = json_decode($json_str->body);
			if(isset($json->items[0]->id)) return 'user';

			// see if it's a channel id
			$url = 'https://www.googleapis.com/youtube/v3/channels?id='.$id.'&key='.$this->api_key.'&part=id';
			$this->set_debug_message('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
			$json_str = $this->remote_get($url);
			$json = json_decode($json_str->body);
			if(isset($json->items[0]->id)) return 'channel';

			// see if it's a playlist id
			$url = 'https://www.googleapis.com/youtube/v3/playlists?id='.$id.'&key='.$this->api_key.'&part=id';
			$this->set_debug_message('Trying to get data from:','<a href="'.$url.'" target="_blank">'.$url.'</a>');
			$json_str = $this->remote_get($url);
			$json = json_decode($json_str->body);
			if(isset($json->items[0]->id)) return 'playlist';
			return '';
		}

		protected function remote_get($url){
			$fget_data = file_get_contents($url);
			$fget_status = $http_response_header[0];
			if(strpos($fget_status,'200')){
				$response = new stdClass();
				$response->code = 200;
				$response->body = $fget_data;
			}
			elseif(function_exists('curl_exec')){
				$s = curl_init();
				curl_setopt($s, CURLOPT_URL, $url);
				curl_setopt($s, CURLOPT_RETURNTRANSFER, true);
				//curl_setopt($s, CURLOPT_INTERFACE, "37.61.237.50"); //set your ip address if necessary
				$curl_data = curl_exec($s);
				$curl_status_code = curl_getinfo($s, CURLINFO_HTTP_CODE);
				curl_close($s);
				if($curl_status_code == 200){
					$response = new stdClass();
					$response->code = 200;
					$response->body = $curl_data;
				}
				elseif(class_exists('JHttp')){
					$http = new JHttp();
					$response = $http->get($url);
				}
				else{
					$response = false;
				}
			}
			elseif(class_exists('JHttp')){
				$http = new JHttp();
				$response = $http->get($url);
			}
			else{
				$response = false;
			}

			return $response;
		}

		protected function sync_required($album_id) {
			if (isset($_GET['forcesyncyt'])) return true;
			$filename = JPATH_CACHE . '/jusertube/' . md5($album_id);
			if (is_file($filename)) {
				$utime = filemtime($filename);
				$chtime = time() - 60 * $this->cachetime;
				if ($utime > $chtime) return false;
				return true;
			}
			return true;
		}

		protected function read_cache($id) {
			$filename = JPATH_CACHE . '/jusertube/' . md5($id);
			$filename_backup = JPATH_CACHE . '/jusertubebackup/' . md5($id);
			if (is_file($filename)) {
				$data = file_get_contents($filename);
				$this->videos = json_decode($data, true);
			} else if (is_file($filename_backup)) {
				$data = file_get_contents($filename_backup);
				$this->videos = json_decode($data, true);
			}
		}

		protected function cache_it($id) {
			if (!count($this->videos)) return;
			if (!is_dir(JPATH_CACHE . '/jusertube')) {
				if (is_writable(JPATH_CACHE)) {
					mkdir(JPATH_CACHE . '/jusertube');
				}
			}
			if (!is_dir(JPATH_CACHE . '/jusertubebackup')) {
				if (is_writable(JPATH_CACHE)) {
					mkdir(JPATH_CACHE . '/jusertubebackup');
				}
			}
			if (!is_writable(JPATH_CACHE . '/jusertube')) {
				$this->set_debug_message('Cache folder is not writable');
			}
			$filename = JPATH_CACHE . '/jusertube/' . md5($id);
			$filename_backup = JPATH_CACHE . '/jusertubebackup/' . md5($id);
			$data = json_encode($this->videos);
			file_put_contents($filename, $data);
			file_put_contents($filename_backup, $data); // keep a backup
		}

		protected function set_debug_message($title, $value = '') {
			if (!isset( $_GET['debugsrzyt'] )) return;
			echo '<h3>' . $title . '</h3>';
			echo '<pre>';
			print_r($value);
			echo '</pre>';
		}
	}
}
