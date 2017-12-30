<?php
/*
*	Getting video host provider video via AJAX
*
*/
//
define('WP_USE_THEMES', false);
require('../../../../../wp-blog-header.php');

$video_host = $_GET['video_host'];
$video_id = $_GET['video_id'];
$w = $_GET['vid_width'];
$h = $_GET['vid_height'];


if ( $video_host && $video_id ) : 
	
	do_action('as_embed_video_action', $video_host, $video_id, $w, $h );
	
	echo '<div class="clearfix"></div>';
	
else: 
	
	echo '<h4>'. __('Error fetching video. No video host or video id is provided.','sequoia') .'</h4>';

endif; 

?>
