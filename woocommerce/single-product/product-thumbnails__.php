<?php
/**
 * Single Product Thumbnails
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-thumbnails.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see 	    https://docs.woocommerce.com/document/template-structure/
 * @author 		WooThemes
 * @package 	WooCommerce/Templates
 * @version     3.1.0
 */
 
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $post, $product, $woocommerce;

$of_img_format = apply_filters( 'sequoia_options', 'single_product_image_format', 'as-portrait' );
if( $of_img_format == 'plugin' ) {
	$img_format = 'shop_single';
}else{
	$img_format = $of_img_format;
}

$single_product_images = apply_filters( 'sequoia_options', 'single_product_images', 'slider' );
$magnifier = false;
if( $single_product_images == 'magnifier' ) {
	$magnifier = true; 
};

// 3.0.0 < Fallback conditional
if( apply_filters( 'sequoia_wc_version', '3.0.0' )	) {
	$attachment_ids   = $product->get_gallery_image_ids();
}else{
	$attachment_ids   = $product->get_gallery_attachment_ids();
}
$gall_id = '';
if ( $magnifier &&  $attachment_ids  ) {
	$attachment_ids[] = get_post_thumbnail_id( $post->ID );
	$gall_id = ' id="gallery-'.$post->ID.'"';
}

if ( $attachment_ids ) {
	$loop = 0;
	$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );
	?>

	<div class="thumbnails"<?php echo $gall_id; ?>>
	
		<?php
		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop == 0 || $loop % $columns == 0 )
				$classes[] = 'first';

			if ( ( $loop + 1 ) % $columns == 0 )
				$classes[] = 'last';

			$image_link_src	= wp_get_attachment_image_src( $attachment_id, $img_format );
			$image_link		= $image_link_src[0];
			
			if ( ! $image_link )
				continue;
			
			$image_class	= esc_attr( implode( ' ', $classes ) );
			$image_title	= esc_attr( get_the_title(  ) );
			$image			= wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'thumbnail' ), array(
				'title' => $image_title
				));
				
			$full_image_src = wp_get_attachment_image_src( $attachment_id, 'full' );
			$full_image		= $full_image_src[0];
			$product_title	= esc_attr( get_the_title() );
			$product_link	= esc_attr( get_permalink() );
			

			// CONDITIONAL VARS FOR IMAGE AND LARGE IMAGE LINK:
			$href = $magnifier ? '#' : $full_image;
			
			$a_attrs = $magnifier ? 'class="button tiny" data-image="'.$image_link.'" data-zoom-image="'.$image_link.'"  data-full-image="'.$full_image	.'"'  : 'class="button tiny mfp-gallery"';
			
			$icon = $magnifier ? 'icon icon-arrow-up-7' : 'icon icon-zoom-in';

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '
			
<div class="item %2$s">

	<div class="item-content">
	
		<div class="item-img">
		
			<div class="front">%4$s</div>
			
			<div class="back">
			
				<div class="item-overlay"></div><!-- item-overlay -->
				
				<div class="back-buttons">
				
					<a href="'.$href.'" title="%3$s" '. $a_attrs .'>
					<div class="'.$icon.'" aria-hidden="true"></div>
					</a>
				
				</div>
				

			</div><!-- back -->
			
		</div><!-- item-img -->
		
	</div><!-- item-content -->
	
</div><!-- item -->', 
			
			$image_link,	// 1
			$image_class,	// 2
			$image_title,	// 3
			$image,			// 4
			$full_image,	// 5
			$product_title  // 6
			
			), $attachment_id, $post->ID, $image_class );
			
			$loop++;
		}

	?></div>
	
	<script type="text/javascript">
	(function($) {
		"use strict";
		
		$(document).ready( function($) {

			var aButton		= $('.thumbnails').find('a.button'),
				target		= $('.images').find('a.larger-image-link');
							
			aButton.on('mousedown' ,function (e) {
							
				e.preventDefault();
				
				var _this		= $(this),
					img_Link	= _this.data('full-image');
				
				target.attr('href', img_Link);
				
			});
			
		}); // end doc ready
		
	})(jQuery);
	</script>
	
	
	<?php
}