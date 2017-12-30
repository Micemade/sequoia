<?php
/*
 * Force URLs in srcset attributes into HTTPS scheme.
 * This is particularly useful when you're running a Flexible SSL frontend like Cloudflare
 */
function as_micemade_ssl_srcset( $sources ) {
	if (is_ssl()) {
		foreach ( $sources as &$source ) {
			$source['url'] = set_url_scheme( $source['url'], 'https' );
		}		
	}
	return $sources;
}
add_filter( 'wp_calculate_image_srcset', 'as_micemade_ssl_srcset' );
/**
 *	GET ALL IMAGE SIZES.
 *
 *	merging WP default image sizes and global $_wp_additional_image_sizes to return all registered image sizes.
 */
function all_image_sizes() {

	global $_wp_additional_image_sizes;
	$default_sizes = array();

	$default_sizes['thumbnail']['width'] = get_option( 'thumbnail_size_w' );
	$default_sizes['thumbnail']['height'] = get_option( 'thumbnail_size_h' );	
	
	$default_sizes['medium']['width'] = get_option( 'medium_size_w' );
	$default_sizes['medium']['height'] = get_option( 'medium_size_h' );	
	
	$default_sizes['large']['width'] = get_option( 'large_size_w' );
	$default_sizes['large']['height'] = get_option( 'large_size_h' );
	
	$imgSizes = array_merge( $default_sizes, $_wp_additional_image_sizes );
	
	return $imgSizes;
}
/**
 *	GET FULL SIZE IMAGE URL.
 *
 *	get full size image url for post thumbnail, attachement image, gallery or AS gallery using attachment ID's.
 *	
 *	var $get_image_ids - recieve image attachment ID's
 *	var $size - registered image size
 */
function as_get_full_img_url( $get_image_ids = '', $size = 'large', $url_or_id = 'url' ) {	
	
	
	if( defined('WPML_ON') ) { // if WMPL is active
		$id = icl_object_id( get_the_ID(), get_post_type(), false, ICL_LANGUAGE_CODE ); 
	}else{
		$id = get_the_ID(); // post id
	}
	
	// WP Gallery image ID's
	$wpgall_ids = apply_filters('as_wpgallery_ids','ids_wp_gallery');
	// AS Gallery image ID's
	$gall_img_array = get_post_meta( get_the_ID(),'as_gallery_images');
	
	$images_ids = $get_image_ids ? $get_image_ids : '';	
	
	if( !$get_image_ids ) { // if no img is send
		
		if( !empty($wpgall_ids) ) {
			$images_ids = implode(', ', $wpgall_ids); // get images from WP gallery
		}elseif( !empty($gall_img_array) ) {
			$images_ids = implode(', ', $gall_img_array); // get images from AS gallery or recieved args
		}else{
			$images_ids = '';
		}
	}
	
	if( $images_ids ) {
		// Using WP3.5; use post__in orderby option
		$ids = explode(',', $images_ids);
		$id = null;
		$orderby = 'post__in';
		$include = $ids;
	} else {
		$orderby = 'menu_order';
		$include = '';
	}
	
	$attached_images = get_posts( array(
			'include' => $include,
			'post_parent' => $id,
			'post_type' => 'attachment',
			'post_mime_type' => 'image',
			'orderby' => $orderby,
			'order' => 'ASC',
			'post_status' => null,
			'numberposts' => 1
			) 
		);	
	
	if ( has_post_thumbnail() && !$get_image_ids ) {
		$img_url	= wp_get_attachment_image_src( get_post_thumbnail_id($id), $size);
		$img_url	= $img_url[0];
		$img_id		= get_post_thumbnail_id($id);
	}elseif ( count($attached_images) ) {
		$attached_image = $attached_images[0];
		$img_url	= wp_get_attachment_image_src( $attached_image->ID, $size );
		$img_url	= $img_url[0];
		$img_id		= $attached_image->ID;
	}else{
		$img_url	= PLACEHOLDER_IMAGE;
		$img_id		= null;
	}
	if( $url_or_id == 'url') {
		return $img_url;
	}elseif( $url_or_id == 'id') {
		return $img_id;
	}
}
/**
 *	Action as_wpgallery_ids
 *
 *	get id's from WP gallery shortcode
 */
function as_wp_gallery() {

	global $post;
	$attachment_ids = array();
	$pattern = get_shortcode_regex();
	$ids = array();
	//finds the "gallery" shortcode and puts the image ids in an associative array at $matches[3]
	if (preg_match_all( '/'. $pattern .'/s', $post->post_content, $matches ) ) {  
		$count=count($matches[3]);      //in case there is more than one gallery in the post.
		for ($i = 0; $i < $count; $i++){
			$atts = shortcode_parse_atts( $matches[3][$i] );
			if ( isset( $atts['ids'] ) ){
				$attachment_ids = explode( ',', $atts['ids'] );
				$ids = array_merge($ids, $attachment_ids);
			}
		}
		
	}
	return $ids;	
	/* // this might work too:
	$post_content = $post->post_content;
	preg_match('/\[gallery.*ids=.(.*).\]/', $post_content, $ids);
	$array_id = explode(",", $ids[1]);
	return $array_id; 
	*/

}
add_filter( 'as_wpgallery_ids', 'as_wp_gallery' );
//
/**
 *	GALLERY OUTPUT
 *
 *	var $postid - post ID number
 *	var $image_ids - ID's of image attachments
 *	var $slider_thumbs - format of displaying gallery images - options slider|thumbnail
 *	var $thumb_columns - if $slider_thumbs is "thumbnails" get number of thumb columns
 *	var $thumb_size - registered image size
 *	var $number_posts - number of images to display
 */
if ( ! function_exists( 'as_gallery_output' ) ) :
function as_gallery_output( $postid, $images_ids, $slider_thumbs = 'slider', $thumb_columns = 3, $thumb_size = 'thumbnail' , $number_posts = -1 ) {

	global $post;
	$imgSizes = all_image_sizes(); // in this file up
	
	// image sizes
	$img_W = isset($imgSizes[$thumb_size]['width']) ? $imgSizes[$thumb_size]['width'] : 300 ;
	$img_H = isset($imgSizes[$thumb_size]['height']) ? $imgSizes[$thumb_size]['height'] : 300;
	
	
	if( $slider_thumbs == 'thumbnails' ) {
		$thumb_class  = $thumb_columns ? ' large-' . floor( 12 / $thumb_columns)  : ' large-4 column';
		$thumb_class .= ' medium-6 small-12 column';
	}else{
		$thumb_class = ' column';
	}
	
	if( $images_ids ) {
		// Using WP3.5; use post__in orderby option
		$image_ids = explode(',', $images_ids);
		$postid = null;
		$orderby = 'post__in';
		$include = $image_ids;
	} else {
		$orderby = 'menu_order';
		$include = '';
	}

	$gallery_images = get_posts(array( 
							'include' => $include,
							'post_parent' => $postid,
							'post_type' => 'attachment',
							'post_mime_type' => 'image',
							'orderby' => $orderby,
							'order' => 'ASC',
							'post_status' => null,
							'numberposts' => $number_posts ) 
							);
	
	if ( $gallery_images ) {
		
		if( defined('WPML_ON') ) { // if WMPL is active
			$pid = icl_object_id( get_the_ID(), get_post_type(), false, ICL_LANGUAGE_CODE ); 
		}else{
			$pid = get_the_ID();
		}
		
		$output = '';
		$output .= '<div id="gallery-'.$pid.'" class="gallery-main">';
		
		$output .= '<div class="gallery-wrap">';
		// POST META FOR SLIDER CONTROLS:
		if( $slider_thumbs == 'slider' ) {
			$slider_nav		= get_post_meta($pid, 'as_slider_nav', true);
			$slider_pagin	= get_post_meta($pid, 'as_slider_pagin', true);
			$slider_timer	= get_post_meta($pid, 'as_slider_timer', true);
			$slider_trans	= get_post_meta($pid, 'as_slider_trans', true);
			$items_desktop	= get_post_meta($pid, 'as_items_desktop', true);
			$items_desktop_s= get_post_meta($pid, 'as_items_desktop_s', true);
			$items_tablet	= get_post_meta($pid, 'as_items_tablet', true);
			$items_mobile	= get_post_meta($pid, 'as_items_mobile', true);
			
			$s_nav		= isset($slider_nav)	? ' data-navigation="'.$slider_nav.'"' : ' data-navigation="0"';
			$s_pag		= isset($slider_pagin)	? ' data-pagination="'.$slider_pagin.'"' : ' data-pagination="0"';
			$s_tim		= isset($slider_timer)	? ' data-auto="'.$slider_timer.'"' : ' data-auto="0"';
			$s_trans	= isset($slider_trans)	? ' data-trans="'.$slider_trans.'"' : '';
			$s_trans	= ($slider_trans == 'none')	? '' : $s_trans;
			
			$it_desk	= isset($items_desktop)	? ' data-desktop="'.$items_desktop.'"' : ' data-desktop="4"';
			$it_desk_s	= isset($items_desktop_s)	? ' data-desktop-small="'.$items_desktop_s.'"' : ' data-desktop-small="3"';
			$it_tablet	= isset($items_tablet)	? ' data-tablet="'.$items_tablet.'"' : ' data-tablet="2"';
			$it_mobile	= isset($items_mobile)	? ' data-mobile="'.$items_mobile.'"' : ' data-mobile="1"';
			
			$output .= '<input type="hidden" class="slides-config" '. $s_nav . $s_pag . $s_tim . $s_trans . $it_desk . $it_desk_s . $it_tablet . $it_mobile .'  />';
		}
		
		
		$output .= ($slider_thumbs == 'slider') ? '<div class="contentslides">' : '';
		
		$i = 0;
		
		foreach( $gallery_images as $image ) :
		
			$total_images = count( $gallery_images );
			$atts = array(
						'class'	=> "attachment-image-$thumb_size",
						'alt'   => esc_attr( get_the_title() ),
						'title' => esc_attr( get_the_title() ),
						
					);

			
			$img_url = as_get_full_img_url($image->ID);
				
				$thumb =  wp_get_attachment_image( $image->ID, $thumb_size, false , $atts );
				
				$output .= '<div class="item'. $thumb_class .'"><div class="item-img">';
				
				$output .= '<div class="front">';
				$output .= $thumb;
				$output .= '</div>'; //  .front
				
				
				$output .= '<div class="back"><div class="item-overlay"></div>';
				$output .= '<div class="back-buttons magnificgallery"><a class="button tiny gallery-image-zoom mfp-gallery" href="'. $img_url .'" title="'. esc_attr( get_the_title() ) . ' ">';
				$output .= '<div class="icon icon-zoom-in" aria-hidden="true"></div></a>';
				$output .= '</div>'; // .back-buttons
				
				$output .= $thumb; // <---- OUTPUT THE IMAGE
				
				$output .= '</div>'; // .back 
								
				$output .= '</div></div>';
				
			
			
		$i++;
		
		endforeach;
		
	$output .= ($slider_thumbs == 'slider') ? '</div>' : '';
	
	$output .= '</div></div>'; // .gallery-main / .gallery-wrap
	
	echo $output;
	
	}; // $gallery_images
}
endif; // as_gallery_output
//
//
//
/**
 *	AS IMAGE - custom function to retrieve image from posts or custom post types
 *
 *	var $thumb_size - registered image size
 *	var $img_Width - desired image width (to use with Freshizer script)
 *	var $img_Height - desired image size (to use with Freshizer script)
 */
//
if ( ! function_exists( 'as_image' ) ):
function as_image( $thumb_size = 'as-portrait',  $img_width = '', $img_height = '' ) {
	
	global $post;
	
	if( defined('WPML_ON') ) { // if WMPL is active
		$id = icl_object_id( $post->ID, get_post_type(), false, ICL_LANGUAGE_CODE ); 
	}else{
		$id = $post->ID;
	}
	
	$title = strip_tags( $post->post_title );
	$atts = array(
		'class'	=> "attachment-image-$thumb_size ",
		'alt'   => esc_attr( $title ),
		'title' => esc_attr( $title )
	);
	
	
	$imgSizes = all_image_sizes(); // get registered img sizes
	// actual image sizes
	$img_W = $imgSizes[$thumb_size]['width'];
	$img_H = $imgSizes[$thumb_size]['height'];

	if( has_post_thumbnail() ){
		
		$imageID = get_post_thumbnail_id();
		$imageUrl = as_get_full_img_url(null, null, 'url' );
		
		if( $img_width && $img_height ) { // if width AND height defined 
					
			$params = array( 'width' => $img_width, 'height' => $img_height );
			$slika = '<img src="' . bfi_thumb( $imageUrl, $params ) . '" alt="'. $title .'" />';
			
		}elseif( $thumb_size && (!$img_width && !$img_height) ) { // else use registered image sizes
			
			$slika = wp_get_attachment_image( $imageID, $thumb_size, false , $atts );
			
		}else{
			
			$slika = wp_get_attachment_image( $imageID, array( 1400, 1000 ), false , $atts );
		}		
		
	}else{
		
		$params = array( 'width' => $img_W, 'height' => $img_H );
		$slika = '<img src="' . bfi_thumb( PLACEHOLDER_IMAGE, $params ) . '" alt="'. $title .'" />';
	}
	
	$output = '<div class="entry-image">';
	// finaly - output the image:
	$output .= $slika;	
	$output .= '<div class="clearfix"></div>';
	$output .= '</div>';
	
	return $output;

}//function as_image
endif; //if function as_image
//
/**
 *	GET IMAGES BY ATTACHMENT ID.
 *
 *	get images independent of attachment - used for WP gallery
 */

function as_get_unattached_image( $imageID, $thumb_size = 'thumbnail', $img_width = '', $img_height = '', $caption = '' ){
	
	// get image caption for atts
	$unattach_img = get_posts(array('p' => $imageID, 'post_type' => 'attachment'));
	if ($unattach_img && isset($unattach_img[0])) {
		$caption = $unattach_img[0]->post_excerpt ;
		$alt	 = $unattach_img[0]->post_excerpt ;
	}
	$atts = array(
		'class'	=> "attachment-image-$thumb_size ",
		'alt'   => $caption ? $caption : $imageID,
		'title' => $caption ? $caption : $imageID
	);	

	if( $imageID ) {
	
		$img_url	= wp_get_attachment_image_src( $imageID );
		$img_url	= $img_url[0];
		
		if( $img_width && $img_height  ) { // if width AND height defined - image resize script
						
			$params = array( 'width' => $img_width, 'height' => $img_height );
			$slika = '<img src="' . bfi_thumb( $img_url, $params ) . '"  title="'. $atts['title'] .'" alt="' . $atts['title'] .'" class="'.$atts['class'].'" />';
			
		}elseif( !$img_width && !$img_height ) { // else use registered image sizes
			
			$slika = wp_get_attachment_image( $imageID, $thumb_size, false , $atts );
			
		}else{
			
			$slika = wp_get_attachment_image( $imageID, 'full', false , $atts );

		}	
	
	}else{
		
		if( $img_width && $img_height  ) {
			$img_W = $img_width;
			$img_H = $img_height;
		
		}else{
			$imgSizes = all_image_sizes(); // get registered img sizes
			$img_W = $imgSizes[$thumb_size]['width'];
			$img_H = $imgSizes[$thumb_size]['height'];
		}
				
		$params = array( 'width' => $img_W, 'height' => $img_H );
		$slika = '<img src="' . bfi_thumb( PLACEHOLDER_IMAGE, $params ) . '"  title="'. $atts['title'] .'" alt="' . $atts['title'] .'" class="'.$atts['class'].'" />';
	
	
	}
	
	$output = '<div class="entry-image">';
	// show actual image
	$output .= $slika;	
	$output .= '<div class="clearfix"></div>';
	$output .= '</div>';
	
	return $output;
}
//
//
/**
 *	POST_THUMBNAIL_CAPTION() - GET POST THUMBNAIL CAPTION.
 *
 */
function as_post_thumbnail_caption() {

	global $post;

	$thumbnail_id    = get_post_thumbnail_id($post->ID);
	$thumbnail_image = get_posts(array('p' => $thumbnail_id, 'post_type' => 'attachment'));

	if ($thumbnail_image && isset($thumbnail_image[0])) {
	
		$caption = $thumbnail_image[0]->post_excerpt ;
		
		return $caption;
	}
	
}
/**
 *	GALLERY HTML FILTER
 *	- overrides default WP output html, replace with theme html 
 *
 */
add_filter( 'post_gallery', 'as_post_gallery', 10, 2 );
function as_post_gallery( $output, $attr ) {
    global $post, $wp_locale;

    static $instance = 0;
    $instance++;

    // We're trusting author input, so let's at least make sure it looks like a valid orderby statement
    if ( isset( $attr['orderby'] ) ) {
        $attr['orderby'] = sanitize_sql_orderby( $attr['orderby'] );
        if ( !$attr['orderby'] )
            unset( $attr['orderby'] );
    }

    extract(shortcode_atts(array(
        'order'      => 'ASC',
        'orderby'    => 'menu_order ID',
        'id'         => $post->ID,
        'itemtag'    => 'div',
        'icontag'    => 'div',
        'captiontag' => 'p',
        'columns'    => 3,
        'size'       => 'thumbnail',
        'include'    => '',
        'exclude'    => ''
    ), $attr));

    $id = intval($id);
    if ( 'RAND' == $order )
        $orderby = 'none';

    if ( !empty($include) ) {
        $include = preg_replace( '/[^0-9,]+/', '', $include );
        $_attachments = get_posts( array('include' => $include, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );

        $attachments = array();
        foreach ( $_attachments as $key => $val ) {
            $attachments[$val->ID] = $_attachments[$key];
        }
    } elseif ( !empty($exclude) ) {
        $exclude = preg_replace( '/[^0-9,]+/', '', $exclude );
        $attachments = get_children( array('post_parent' => $id, 'exclude' => $exclude, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    } else {
        $attachments = get_children( array('post_parent' => $id, 'post_status' => 'inherit', 'post_type' => 'attachment', 'post_mime_type' => 'image', 'order' => $order, 'orderby' => $orderby) );
    }

    if ( empty($attachments) )
        return '';

    if ( is_feed() ) {
        $output = "\n";
        foreach ( $attachments as $att_id => $attachment )
            $output .= wp_get_attachment_link($att_id, $size, true) . "\n";
        return $output;
    }

    $itemtag	= tag_escape($itemtag);
    $captiontag = tag_escape($captiontag);
    $columns	= intval($columns);
    $itemwidth	= $columns > 0 ? floor(100/$columns) : 100;
    $float		= is_rtl() ? 'right' : 'left';

    $thumb_class  = $columns ? ' large-' . floor( 12 / $columns)  : ' large-4 column';
	$thumb_class .= ' medium-6 small-12 column';
	
	$selector = "gallery-{$instance}";

    $output = apply_filters('gallery_style', "
        <!-- see gallery_shortcode() in wp-includes/media.php -->
        <div id='$selector' class='gallery gallery-wrap galleryid-{$id}'>");

    $i = 0;
    foreach ( $attachments as $id => $attachment ) {
        
		$link = isset($attr['link']) && 'file' == $attr['link'] ? wp_get_attachment_link($id, $size, false, false) : wp_get_attachment_link($id, $size, true, false);

        $img_url	= as_get_full_img_url( $id );
		$thumb		= wp_get_attachment_image( $id, 'thumbnail', false  );
		
		$output .= "<{$itemtag} class='item".$thumb_class."'><div class='item-img'>";
        $output .= "
            <{$icontag} class='front'>
                $thumb
            </{$icontag}>";
        
		$output .= '<div class="back"><div class="item-overlay"></div>';	
		
		$output .= '<div class="back-buttons"><a class="button tiny gallery-image-zoom magnificgallery mfp-gallery" href="'. $img_url .'" title="'. esc_attr( get_the_title() ) . ' ">';
		$output .= '<div class="icon icon-zoom-in" aria-hidden="true"></div></a>';
		$output .= '</div>'; // .back-buttons
		
		$output .= $thumb; // <---- OUTPUT THE IMAGE
		
		if ( $captiontag && trim($attachment->post_excerpt) ) {
            $output .= "
                <{$captiontag} class='gallery-caption'>
                " . wptexturize($attachment->post_excerpt) . "
                </{$captiontag}>";
        }
		
		$output .= "</div>"; // back
        
		$output .= "</div></{$itemtag}>"; // .item-img / .item
		
        if ( $columns > 0 && ++$i % $columns == 0 )
            $output .= '<br style="clear: both" />';
    }

    $output .= "
            <br style='clear: both;' />
        </div>\n";

    return $output;
}


/*
Plugin Name: Thumbnail Upscale
Plugin URI: http://wordpress.org/plugins/thumbnail-upscale/
Description: Enables upscaling of thumbnails for small media attachments
Version: 1.0
Author: Stanislav Khromov
Author URI: http://khromov.wordpress.com
License: GPLv2
*/
if ( !class_exists('ThumbnailUpscaler') ) {
	class ThumbnailUpscaler {
		/** http://wordpress.stackexchange.com/questions/50649/how-to-scale-up-featured-post-thumbnail **/
		static function image_crop_dimensions($default, $orig_w, $orig_h, $new_w, $new_h, $crop)
		{
			if(!$crop)
				return null; // let the wordpress default function handle this
		
			$aspect_ratio = $orig_w / $orig_h;
			$size_ratio = max($new_w / $orig_w, $new_h / $orig_h);
		
			$crop_w = round($new_w / $size_ratio);
			$crop_h = round($new_h / $size_ratio);
		
			$s_x = floor( ($orig_w - $crop_w) / 2 );
			$s_y = floor( ($orig_h - $crop_h) / 2 );
		
			return array( 0, 0, (int) $s_x, (int) $s_y, (int) $new_w, (int) $new_h, (int) $crop_w, (int) $crop_h );
		}
	}

	add_filter('image_resize_dimensions', array('ThumbnailUpscaler', 'image_crop_dimensions'), 10, 6);
}
?>