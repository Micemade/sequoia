<?php
function as_post_formats_media( $post_id, $block_id=null, $img_format=null, $img_width=null, $img_height=null ) {

	$post_format = get_post_format();
	
	if( $post_format == 'video' ) { // <---------- VIDEO POST FORMAT

		$featured_or_thumb	= get_post_meta($post_id,'as_video_thumb', true);
		$video_host			= get_post_meta($post_id,'as_video_host', true);
		$video_id			= get_post_meta($post_id,'as_video_id', true);
		$width				= get_post_meta($post_id,'as_video_width', true);
		$height				= get_post_meta($post_id,'as_video_height', true);
		
		$img_url = get_template_directory_uri() . '/inc/blocks/ajax_video.php?video_host='.$video_host.'&amp;video_id='.$video_id.'&amp;vid_width='.$width.'&amp;vid_height='.$height.'&amp;ajax=true';
		
		$params = array( 'width' => $img_width, 'height' => $img_height );
		
		if ( $featured_or_thumb == 'host_thumb' ) { // if thumbs from video hosts
		
			$img = as_video_thumbs();
			$image_output = '<div class="entry-image"><img src="'. bfi_thumb( $img, $params ) .'" alt="'. esc_attr(strip_tags(get_the_title())) .'" /></div>';
					
		}else{
		
			$img = as_get_full_img_url();
			$image_output = '<div class="entry-image"><img src="'. bfi_thumb( $img, $params )  .'" alt="'. esc_attr(strip_tags(get_the_title())) .'" /></div>';
			
		}
		$pP_rel = '[ajax-'.$post_id.'-'.$block_id.']';
		$img_urls_gallery = ''; // avoid duplicate gallery image urls
		$quote_html			= '';
		
	}elseif( $post_format == 'gallery' ) { // <------------- GALLERY POST FORMAT
		
		// WP gallery img id's:
		$wpgall_ids 	= apply_filters('as_wpgallery_ids','as_wp_gallery');
		// AS gallery img id's (from custom meta):
		$gall_img_array = get_post_meta( get_the_ID(),'as_gallery_images');
									
		$img_urls_gallery = $feat_gall_image_id = '';
		$n = 0;
		// if has WP gallery
		if( !empty($wpgall_ids) ) {
			
			foreach ( $wpgall_ids as $wpgall_img_id ){
				
				if( $n == 0 ) {
					$img_url = as_get_full_img_url( $wpgall_img_id );
				}else{
					$img_urls_gallery .= '<a href="' .as_get_full_img_url( $wpgall_img_id ) .'" class="invisible-gallery-urls mfp-gallery" title="'.esc_attr(strip_tags(get_the_title())).'"></a>';
				}
				$n++;
			}
			
			$feat_gall_image_id = $wpgall_ids[0];
			
		// else if has THEME gallery
		}elseif( !empty($gall_img_array) ) {
			
			foreach ( $gall_img_array as $gall_img_id ){
				
				if( $n == 0 ) {
					$img_url = as_get_full_img_url( $gall_img_id );
				}else{
					$img_urls_gallery .= '<a href="' .as_get_full_img_url( $gall_img_id ) .'" class="invisible-gallery-urls mfp-gallery" title="'.esc_attr(strip_tags(get_the_title())).'"></a>';
				}
				$n++;
			
			}
			
			$feat_gall_image_id = $gall_img_array[0];
			
		}
		
		if( has_post_thumbnail() ) {
			$image_output = as_image( $img_format, $img_width, $img_height );
		}else{
			$image_output = as_get_unattached_image( $feat_gall_image_id, $img_format, $img_width, $img_height );
		}
		
		$pP_rel			= '[pp_gal-'.$post_id.'-'.$block_id.']';
		$quote_html			= '';

	}elseif( $post_format == 'audio' ){ // <--------------AUDIO POST FORMAT
		
		$audio_file_id		= get_post_meta($post_id,'as_audio_file', true);
		$audio_file			= wp_get_attachment_url( $audio_file_id );	
		$large_image		= as_get_full_img_url();
		
		// finals:
		$img_url			= get_template_directory_uri() . '/inc/blocks/ajax_audio.php?audio_file='.$audio_file.'&amp;large_image='.$large_image.'&amp;post_id='.$post_id.'&amp;ajax=true';
		$image_output		= as_image( $img_format, $img_width, $img_height );
		$pP_rel				= '';
		$img_urls_gallery	= ''; // avoid duplicate gallery image urls
		$quote_html			= '';
		
		//wp_enqueue_style( 'wp-mediaelement' );
		//wp_enqueue_script( 'wp-mediaelement' );
		
	}elseif( $post_format == 'quote' ){ // <---------------- QUOTE POST FORMAT
		
		$quote_author	= get_post_meta($post_id,'as_quote_author', true);
		$quote_url		= get_post_meta($post_id,'as_quote_author_url', true);
		$avatar			= get_post_meta($post_id,'as_avatar_email', true);
		
		$quote_html = '<div class="quote-inline format-quote">';
		
		if( $avatar || has_post_thumbnail() ) {
			
		$quote_html		.= '<div class="avatar-img">';
			
			$quote_html .= $quote_url ? '<a href="'.$quote_url.'" title="'. $quote_author .'">' : '';
			if( $avatar ) {
				$quote_html .= get_avatar( $avatar , 120 );
			}elseif( has_post_thumbnail() ){
				$quote_html .= get_the_post_thumbnail('thumbnail');
			}
			$quote_html		.= $quote_url ? '</a>' : '';
			
			$quote_html		.= '</div>';

		}; 

		$quote_html .= '<div class="quote">';
			
			$quote_html		.= '<p>'. get_the_content() .'</p>'; 
			$quote_html		.=  $quote_url ? '<a href="'.$quote_url.'" title="'. $quote_author .'">' : '';
			$quote_html		.=  $quote_author ? '<h5>'.$quote_author.'</h5>' : '';
			$quote_html		.=  $quote_url ? '</a>' : '';

		// finals:
		$quote_html .= '</div></div>';
		$img_url			= '#quote-'.$post_id;
		$image_output		= as_image( $img_format, $img_width, $img_height );
		$pP_rel				= '[inline-'.$post_id.'-'.$block_id.']';
		$img_urls_gallery	= ''; // avoid duplicate gallery image urls
		
	}else{ // <---------------- STANDARD POST FORMAT
		
		$img_url			= as_get_full_img_url();
		$image_output		= as_image( $img_format, $img_width, $img_height );
		$pP_rel				= '';
		$img_urls_gallery	= ''; // avoid duplicate gallery image urls
		$quote_html			= '';
	}
	
	$func_output = array();
	$func_output['img_url']			= $img_url;
	$func_output['image_output']	= $image_output;
	$func_output['pP_rel']			= $pP_rel;
	$func_output['img_urls_gallery']= $img_urls_gallery;
	$func_output['quote_html']		= $quote_html;
	
	return $func_output;
}
?>