<?php
/**
 * WooCommerce Image Functions
 * 
 * @since 1.5.0
 * @package WordPress
 * @subpackage Sequoia
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// First remove default image functions.
remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );

/**
 * PROD.ARCHIVE PAGE IMAGES:
 */
function sequoia_loop_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0 ) {

	global $post, $product, $yith_wcwl;

	$products_settings = apply_filters( 'sequoia_options', 'products_settings', array( 'disable_zoom_button' => false, 'disable_link_button' => false ) );

	// get image format from theme options:
	$of_imgformat = apply_filters( 'sequoia_options', 'shop_image_format', 'as-portrait' );
	if( 'as-portrait' === $of_imgformat || 'as-landscape' === $of_imgformat ){
		$img_format = $of_imgformat;
	} else {
		$img_format = 'shop_catalog';
	}

	// 3.0.0 < Fallback conditional.
	if ( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
		$attachment_ids = $product->get_gallery_image_ids();
	} else {
		$attachment_ids = $product->get_gallery_attachment_ids();
	}
	if ( $attachment_ids ) {
		$image_url  = wp_get_attachment_image_src( $attachment_ids[0], 'full' );
		$img_url    = $image_url[0];
		$imgSizes   = all_image_sizes(); // as custom fuction
		$img_width  = $imgSizes[$img_format]['width'];
		$img_height = $imgSizes[$img_format]['height'];
	}

	$title = '<a href="' . get_permalink(). '" title="' . esc_attr( $post->post_title ) . '"><h3>' . get_the_title() . '</h3></a>';

	echo '<div class="front">';

	function_exists( 'woocommerce_show_product_loop_sale_flash' ) ? woocommerce_show_product_loop_sale_flash() : '';

	echo apply_filters( 'woocommerce_template_loop_product_thumbnail', as_image( $img_format ) );

	echo '</div>';

	echo '<div class="back">';

		echo '<div class="item-overlay"></div>';

		function_exists( 'woocommerce_template_loop_rating' ) ? woocommerce_template_loop_rating() : '';

		if ( $attachment_ids ) {

			echo wp_get_attachment_image(  $attachment_ids[0], array( $img_width, $img_height ) );

		} else {

			echo as_image( $img_format );
		}

		echo '<div class="back-buttons">';

			if( ! isset( $products_settings['disable_zoom_button'] ) ) {
				echo '<a href="' . esc_url( as_get_full_img_url() ) . '" class="button tiny item-zoom magnificpopup mfp-image" title="'. esc_attr(get_the_title()) .'"><div class="icon icon-zoom-in" aria-hidden="true"></div></a>';
			}
			if ( ! isset( $products_settings['disable_link_button'] ) ) {
				echo '<a href="' . esc_url( get_permalink() ) . '" class="button tiny" title="' . esc_attr( get_the_title() ) . '"><div class="icon icon-link" aria-hidden="true"></div></a>';
			}

		echo '</div>';

		function_exists( 'woocommerce_template_loop_rating' ) ? woocommerce_template_loop_rating() : '';

	echo '</div>';

}
add_action( 'woocommerce_before_shop_loop_item_title', 'sequoia_loop_product_thumbnail', 20 );

/**
 * CHANGE WOOCOMMERCE PLACEHOLDER IMAGE
 *
 * @return void
 */
function sequoia_placeholder_img_src () {
	return apply_filters( 'sequoia_options', 'placeholder_image', get_template_directory_uri() . '/img/no-image.jpg' );
}
remove_filter( 'woocommerce_placeholder_img_src', 'woocommerce_placeholder_img_src' );
add_filter( 'woocommerce_placeholder_img_src', 'sequoia_placeholder_img_src' );

/**
 * QUICK VIEW IMAGES
 *
 */
function sequoia_quick_view_images_f() {

	global $post, $woocommerce, $product;

	// Get image format from theme options.
	$of_imgformat = apply_filters( 'sequoia_options', 'single_product_image_format', 'as-portrait' );
	if ( 'plugin' === $of_imgformat ) {
		$img_format = 'shop_single';
	} else {
		$img_format = $of_imgformat;
	}

	// 3.0.0 < Fallback conditional
	if ( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
		$attachment_ids = $product->get_gallery_image_ids();
	} else {
		$attachment_ids = $product->get_gallery_attachment_ids();
	}

	echo '<div class="images' . ( $attachment_ids ? ' productslides' : '' ) . '">';

	// MAIN PRODUCT IMAGE - POST THUMBNAIL (FEATURED IMAGE ETC.).
	if ( has_post_thumbnail() ) {

		$post_thumb_id             = get_post_thumbnail_id();
		$default_product_image_src = wp_get_attachment_image_src( $post_thumb_id, $img_format );
		$default_product_image_url = $default_product_image_src[0];

		$image_link   = wp_get_attachment_url( $post_thumb_id );
		$image_class  = 'attachment-' . $post_thumb_id ;
		$image_title = strip_tags( get_the_title( $post_thumb_id ) ) ;
		$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
			'title' => esc_attr( $image_title ),
			'alt'   => esc_attr( $image_title ),
			'class' => esc_attr( $image_class . ' featured' ),
		) );
		$full_image    = as_get_full_img_url();
		$product_title = esc_attr( strip_tags(get_the_title()));
		$product_link  = esc_attr( get_permalink() );

		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<div class="item-img item"><a href="%2$s" data-o_href="%2$s" data-zoom-image="%4$s" class="larger-image-link woocommerce-main-image zoom %5$s" itemprop="image"  title="%3$s">%1$s</a></div>',

			$image, // %1$s .
			$full_image, // %2$s .
			$product_title, // %3$s .
			$default_product_image_url, // %4$s .
			$attachment_ids ? 'mfp-gallery' : 'magnificpopup mfp-image' // %5$s

		), $post->ID );

	} else {

		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ) );

	}

	// PRODUCT GALLERY IMAGES.
	if ( $attachment_ids ) {

		$loop = 0;
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop == 0 || $loop % $columns == 0 ) {
				$classes[] = 'first';
			}
			if ( ( $loop + 1 ) % $columns == 0 ) {
				$classes[] = 'last';
			}

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link ) {
				continue;
			}

			$image_title = esc_attr( get_the_title( $attachment_id ) );
			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array( 'title' => $image_title ));
			$image_class = esc_attr( implode( ' ', $classes ) );

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html',
				sprintf( '<div class="item-img">%4$s</div>', 
					$image_link,
					$image_class,
					$image_title,
					$image 
				), $attachment_id, $post->ID, $image_class
			);

			$loop++;
		}

	}
	echo '</div>';//. images
}
add_action( 'sequoia_quick_view_images', 'sequoia_quick_view_images_f', 25 );


/**
 * SINGLE PRODUCT DISPLAY IMAGES (slider)
 *
 * @param string $img_format
 * @return html
 */
function single_product_images( $img_format = 'shop_single' ) {

	global $post, $woocommerce, $product;

	// get image format from theme options for SINGLE PRODUCT.
	$of_imgformat = apply_filters( 'sequoia_options', 'single_product_image_format', 'as-portrait' );

	if ( is_product() ) { // if on single product page: 

		if ( 'plugin' === $of_imgformat ) {
			$img_format = 'shop_single';
		} else {
			$img_format = $of_imgformat;
		}
		// if not on single product (single block or quick view):.
	} else {

		$img_format = $img_format;
	}

	// 3.0.0 < Fallback conditional.
	if ( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
		$attachment_ids = $product->get_gallery_image_ids();
	} else {
		$attachment_ids = $product->get_gallery_attachment_ids();
	}

	echo '<div class="' . ( $attachment_ids ? 'owl-carousel singleslides magnificgallery' : '' ) . ' images">';

	// MAIN PRODUCT IMAGE - post thumbnail (featured image etc.)
	if ( has_post_thumbnail() ) {

		$post_thumb_id             = get_post_thumbnail_id();
		$default_product_image_src = wp_get_attachment_image_src( $post_thumb_id, $img_format );
		$default_product_image_url = $default_product_image_src[0];

		$image_link  = wp_get_attachment_url( $post_thumb_id );
		$image_class = 'attachment-' . $post_thumb_id ;
		$image_title = strip_tags( get_the_title( $post_thumb_id ) ) ;
		$image       = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
			'title' => esc_attr( $image_title ),
			'alt'   => esc_attr( $image_title ),
			'class' => esc_attr( $image_class . ' featured' )
			) );
		$full_image    = as_get_full_img_url();
		$product_title = esc_attr( strip_tags( get_the_title() ) );
		$product_link  = esc_attr( get_permalink() );

		echo apply_filters( 'woocommerce_single_product_image_html', sprintf('<div class="item-img item"><div class="front">%1$s</div><div class="back"><div class="item-overlay"></div><div class="back-buttons" itemscope><a href="%2$s" data-o_href="%2$s" data-zoom-image="%4$s" class="larger-image-link button tiny woocommerce-main-image zoom %5$s" itemprop="image"  title="%3$s"><div class="icon icon-zoom-in" aria-hidden="true"></div></a></div></div></div>',
			$image,
			$full_image,
			$product_title,
			$default_product_image_url,
			$attachment_ids ? 'mfp-gallery' : 'magnificpopup mfp-image'
		), $post->ID );

	} else {

		echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ) );

	}

	// Product gallery images
	if ( $attachment_ids ) {

		$loop = 0;
		$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

		foreach ( $attachment_ids as $attachment_id ) {

			$classes = array( 'zoom' );

			if ( $loop == 0 || $loop % $columns == 0 ) {
				$classes[] = 'first';
			}

			if ( ( $loop + 1 ) % $columns == 0 ) {
				$classes[] = 'last';
			}

			$image_link = wp_get_attachment_url( $attachment_id );

			if ( ! $image_link ) {
				continue;
			}

			$image_class = esc_attr( implode( ' ', $classes ) );
			$image_title = esc_attr( get_the_title() );
			$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
				'title' => $image_title
				));
			$attachment_src = wp_get_attachment_image_src( $attachment_id, 'large' );
			$full_image    = $attachment_src[0];
			$product_title = esc_attr(get_the_title());
			$product_link  = esc_attr( get_permalink() );

			echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="item-img item"><div class="front">%4$s</div><div class="back"><div class="item-overlay"></div><div class="back-buttons magnificgallery"><a href="%5$s" class="button tiny mfp-gallery" title="%6$s"><div class="icon icon-zoom-in" aria-hidden="true"></div></a> %7$s </div></div></div>',
				$image_link,
				$image_class,
				$image_title,
				$image,
				$full_image,
				$product_title,
				is_product() ? null : '<a href="'. esc_url( $product_link ) .'" class="button tiny" title="%6$s"><div class="icon icon-link" aria-hidden="true"></div></a>'

			), $attachment_id, $post->ID, $image_class );

			$loop++;
		}
	}

	echo '</div>';//. images
}
add_action( 'do_single_product_images', 'single_product_images', 25, 1 );

/**
 * DEQUEUE PRETTYPHOTO FROM WC IN FAVOUR OF THEME'S "MAGNIFIC POPUP" SCRIPT
 *
 * @return void
 */
function prettyPhoto_dequeue () {
	global $sequoia_woo_is_active;
	// if plugin is active.
	if ( $sequoia_woo_is_active ) {
		wp_dequeue_style( 'woocommerce_prettyPhoto_css' );
		wp_deregister_style( 'woocommerce_prettyPhoto_css' );

		wp_dequeue_script( 'prettyPhoto' );
		wp_dequeue_script( 'prettyPhoto-init' );
	}
}
add_action( 'wp_enqueue_scripts', 'prettyPhoto_dequeue', 1000 );