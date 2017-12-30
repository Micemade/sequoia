<?php
/**
 *	AS_EMBED_VIDEO - embedding video from varoius video host sites
 *
 */
function as_embed_video( $video_host='youtube', $video_id='', $w='100', $h='400px'  ) {
	
	if ( $video_host == "youtube" ) { $src = 'http://www.youtube-nocookie.com/embed/'.$video_id; }
	else if ( $video_host == "screenr" ) { $src = 'http://www.screenr.com/embed/'.$video_id; }
	else if ( $video_host == "vimeo" ) { $src = 'http://player.vimeo.com/video/'.$video_id; }
	else if ( $video_host == "dailymotion" ) { $src = 'http://www.dailymotion.com/embed/video/'.$video_id; }
	else if ( $video_host == "yahoo" ) { $src = 'http://d.yimg.com/nl/vyc/site/player.html#vid='.$video_id; }
	else if ( $video_host == "bliptv" ) { $src = 'http://a.blip.tv/scripts/shoggplayer.html#file=http://blip.tv/rss/flash/'.$video_id; }
	else if ( $video_host == "veoh" ) { $src = 'http://www.veoh.com/static/swf/veoh/SPL.swf?videoAutoPlay=0&permalinkId='.$video_id; }
	else if ( $video_host == "viddler" ) { $src = 'http://www.viddler.com/simple/'.$video_id; }
	
	// VIDEO WIDTH IS HARDCODED IN PERCENTAGE !
	if ( $video_id != '' ) {
		echo '<iframe style="width:'.$w.'%; height:'.$h.';" src="'.$src.'" class="vid iframe-'.$video_host.'"></iframe>';
	}
}
add_action('as_embed_video_action','as_embed_video', 10, 4 );
//
/**
 *	Video SHORTCODES - extract video shorcodes for usage out of content enviroment.
 *
 */
if ( ! function_exists( 'as_video_shortcode' ) ) {
function as_video_shortcode() {
	global $post;
	if($post) {
		$pattern = get_shortcode_regex();
		preg_match('/'.$pattern.'/s', $post->post_content, $matches);
		if( $matches ) :
			if (is_array($matches) && $matches[2] == 'video') {
			   $shortcode = $matches[0];
			   echo do_shortcode($shortcode);
			}
		endif; //$matches
	} // endif $post
}// end func. as_video_shortcode
}
add_action( 'init', 'as_video_shortcode' );
//
/**
 *	REMOVE VIDEO SHORTCODE - remove video shortcode from the content.
 *
 */
if ( ! function_exists( 'remove_as_video_shortcode' ) ) {
	function remove_as_video_shortcode( $content = null ){
		global $post;
	 
		//if( is_single() && is_main_query() && $post->post_type == 'portfolio' ){
		if( is_main_query() && $post->post_type == 'portfolio' ){
			$pattern = get_shortcode_regex();
			preg_match('/'.$pattern.'/s', $content, $matches);
			if ( isset($matches[2]) && is_array($matches) && $matches[2] == 'video_sc') {
				//shortcode is being used
				$content = str_replace( $matches[0], '', $content );
			}
		}
		return $content;
	}
}
/**
 *	as_video_thumbs() - retrieve video screenshot image from video hosted on varoius video host services.
 *
 */
if ( ! function_exists( 'as_video_thumbs' ) ) {
	function as_video_thumbs() {
		
		global $post;
		
		$host			= get_post_meta( get_the_ID(),'as_video_host', true);
		$video_id		= get_post_meta( get_the_ID(),'as_video_id', true);
		
		if( $host == 'youtube' ) {
		
			$img_url	= 'http://img.youtube.com/vi/'. $video_id .'/maxresdefault.jpg';
			
		}elseif( $host == 'screenr' ) {
		
			$json_url	= wp_remote_fopen('http://www.screenr.com/api/oembed.json?url=http://www.screenr.com/'.$video_id);
			$scr_array	= json_decode($json_url, true);
			$img_url	= $scr_array['thumbnail_url'];
		
		}elseif ( $host == 'vimeo' ){ 
			
			$hash		= unserialize( wp_remote_fopen("http://vimeo.com/api/v2/video/$video_id.php") );
			$img_url	= $hash[0]['thumbnail_large'];
			
		}elseif( $host == 'dailymotion') {
		
			$img_url = 'http://www.dailymotion.com/thumbnail/video/'.$video_id;
			
		}elseif( $host == 'bliptv' ) {
			
			require_once('xml-to-array.php');
			
			$blip_xml = wp_remote_fopen( 'http://blip.tv/rss/'. $video_id );
			$x = XML2Array::createArray($blip_xml);
			$img_url = $x['rss']['channel']['item']['media:thumbnail']['@attributes']['url'];
			
		}else{
			
			$img_url = PLACEHOLDER_IMAGE;
		}
		
		return $img_url;
	}
}
?>