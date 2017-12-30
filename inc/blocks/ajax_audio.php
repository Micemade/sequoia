<?php
/*
*	Getting video host provider video via AJAX.
*	
*	@since sequoia 1.0
*/
//
define('WP_USE_THEMES', false);
require('../../../../../wp-blog-header.php');

$audio_file		= $_GET['audio_file'];
$large_image	= $_GET['large_image'];
$post_id		= $_GET['post_id'];
$params 		= array( 'width' => 800, 'height' => 600 );

echo '<div class="audio-modal-holder ">';

echo '<img src="'. bfi_thumb( $large_image, $params ) .'" title="'.$post_id.'" alt="'.$post_id.'" class="audio-featured-image" />';

if ( $audio_file ) : 
	/* 
	function my_enqueue_scripts(){
		wp_enqueue_style( 'wp-mediaelement' );
		wp_enqueue_script('wp-mediaelement');
	}

	add_action( 'wp_enqueue_scripts', 'my_enqueue_scripts' );
	 */
	$post = get_post( $post_id );
	
	if ($post) {
	
		setup_postdata($post);

		
		$attr = array(
			'src'      => $audio_file,
			'loop'     => false,
			'autoplay' => false,
			'preload'  => 'none'
		);
		
		echo wp_audio_shortcode($attr);

	};
	?>
	<script>
	(function($) {
		"use strict";
		$(document).ready( function() {
			$("video,audio").mediaelementplayer();
		});
	})(jQuery);
	</script>
	<?php
	
else: 
	
	echo '<h4>'. __('Error fetching audio. No audio file is provided.','sequoia') .'</h4>';

endif; 

echo '</div>';
?>
