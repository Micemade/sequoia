<?php
//
/** 
 *	WOOCOMMERCE check plugin existence
 *
 */
$sequoia_woo_is_active = false;
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
	
	add_filter( "sequoia_wc_active", function($void) { return true; }, 10, 1 );
	
	// Variables for fallback for some (eventually) missed  global vars replacements 
	$sequoia_woo_is_active	= apply_filters( "sequoia_wc_active","");
	$woo_is_active			= apply_filters( "sequoia_wc_active",""); // fallback for older versions and child theme overrides
	
	if( defined('WOOCOMMERCE_VERSION') ) {
		$sequoia_wc_version = WOOCOMMERCE_VERSION ;
	}
	
	$run_once = new run_once;
	if ($run_once->run('init_woo_theme_values')){
		init_woo_theme_values();
	}
	
}
/**
 *  WC VERSION CONTROL
 *
 *  @param [string] $vers_to_check - WC version to check
 *  @return $version_is_higher
 *
 */
function sequoia_wc_version_f( $vers_to_check ) {
	global $sequoia_wc_version, $sequoia_woo_is_active;
	if( ! $sequoia_woo_is_active ) return;
	$version_is_higher = false;
	if ( version_compare( $sequoia_wc_version, $vers_to_check ) >= 0 ) {
		$version_is_higher = true;
	}
	return $version_is_higher;
}
add_filter( 'sequoia_wc_version','sequoia_wc_version_f', 10, 1 );
// add major "WC 3.0.0" update class
function sequoia_wc2_7( $classes ) {
	if( apply_filters( 'sequoia_wc_version', '3.0.0' ) ) {
		$classes[] = "WC2.7";
	}
	return $classes;
}
add_filter('body_class', 'sequoia_wc2_7' );
/**
 *	YITH WISHLIST check plugin existence
 *
 */
$sequoia_wishlist_is_active = false;
if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || class_exists( 'YITH_WCWL' ) ) {
	
	$sequoia_wishlist_is_active = true; 
	
}


function init_woo_theme_values() {
	//
	$shop_catalog_image_size = array(
		'width' => 300,
		'height' => 180,
		'crop' => 1
	);
	$shop_single_image_size = array(
		'width' => 300,
		'height' => 300,
		'crop' => 1
	);
	$shop_thumbnail_image_size = array(
		'width' => 80,
		'height' => 80,
		'crop' => 1
	);
	update_option('shop_catalog_image_size', $shop_catalog_image_size );
	update_option('shop_single_image_size', $shop_single_image_size );
	update_option('shop_thumbnail_image_size', $shop_thumbnail_image_size );
	//
	update_option( 'woocommerce_frontend_css','no' ); // IMPORTANT - theme's WOO template CSS instead of plugin's
	update_option( 'woocommerce_menu_logout_link','no' ); // remove "Logout" menu item	
	update_option( 'woocommerce_prepend_shop_page_to_urls','yes' );
	update_option( 'woocommerce_prepend_shop_page_to_products','yes' ); 
	update_option( 'woocommerce_prepend_category_to_products','yes' );
	//
};
	
if( $sequoia_woo_is_active ) {

	// Support for WooCommerce gallery zoom, lightbox and slider
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	
	
	function wooc_init () {

		global $sequoia_wc_version;
		
		add_theme_support( 'woocommerce' );
		
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );

	}
	add_action('init','wooc_init');	

	/**
	 *	NUMBER OF PRODUCTS ON PRODUCTS PAGE:
	 *
	 */
	add_filter('loop_shop_per_page', 'products_per_page' );
	if (!function_exists('products_per_page')) {
		function products_per_page () {

			$products_page_settings = apply_filters( 'sequoia_options', 'products_page_settings', array('Products per page'=> 6) );
			$products_number =  !empty( $products_page_settings['Products per page']) ? $products_page_settings['Products per page'] : 6;
			return $products_number;
		}
	}
	/**
	 *	NUMBER OF COLUMNS IN PRODUCTS AND PROD. TAXNOMIES PAGE
	 *
	 */
	add_filter('loop_shop_columns', 'loop_columns');
	if (!function_exists('loop_columns')) {
		
		function loop_columns() {
			
			$products_page_settings = apply_filters( 'sequoia_options', 'products_page_settings', array('Products columns'=> 4) );
			$columns =  !empty($products_page_settings['Products columns']) ? $products_page_settings['Products columns'] : 4;
			
			return $columns;

		}
	}
	/**
	 *	NUMBERS FOR RELATED PRODUCTS
	 *
	 **/
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_related_products', 20);
	add_action( 'woocommerce_after_single_product_summary', 'sequoia_output_related_products', 20);
	if ( ! function_exists( 'sequoia_output_related_products' ) ) {
		function sequoia_output_related_products() {
		
			global $sequoia_wc_version;
			
			$products_page_settings = apply_filters( 'sequoia_options', 'products_page_settings', array('Related total'=> 4, 'Related columns' => 4) );
			
			$related_total =  $products_page_settings['Related total'] ? $products_page_settings['Related total'] : 4;
			$related_columns =  $products_page_settings['Related columns'] ? $products_page_settings['Related columns'] : 4;
			
			if ( version_compare( $sequoia_wc_version, "2.1" ) >= 0 ) {
			
				$args = array(
					'posts_per_page' => $related_total,
					'columns' => $related_columns,
					'orderby' => 'rand'
				);
				woocommerce_related_products( apply_filters( 'woocommerce_output_related_products_args', $args ) );
				
			} 
		}
	}
	
	/**
	 *	NUMBERS FOR UPSELL PRODUCTS
	 *
	 **/
	remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_upsell_display', 15 );
	add_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_upsells', 15 );
	 
	if ( ! function_exists( 'woocommerce_output_upsells' ) ) {
		function woocommerce_output_upsells() {
		
			$total =  apply_filters( 'sequoia_options', 'upsell_total', 3 );
			$in_row = apply_filters( 'sequoia_options', 'upsell_in_row', 3 );
			
			woocommerce_upsell_display( $total, $in_row);
		}
	}
	
	/**
	 *	NUMBERS FOR CROSS SELL PRODUCTS
	 *
	 **/	
	add_filter( 'woocommerce_cross_sells_columns', 'sequoia_cross_sells_columns' );
	function sequoia_cross_sells_columns( $columns ) {
		
		$seq_cs_columns =  apply_filters( 'sequoia_options', 'cross_sell_in_row', '' );
		$cs_columns	= $seq_cs_columns ? $seq_cs_columns : 3;
		return $cs_columns;
	}
	
	//
	/**
	 *	AJAX UPDATER OF CART
	 *
	 */
	add_filter('woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment');
	function woocommerce_header_add_to_cart_fragment( $fragments ) {
				
		ob_start();
		$cart_count = WC()->cart->cart_contents_count;
		$cart_link = get_permalink( wc_get_page_id( 'cart' ));
		$cart_action = apply_filters( 'sequoia_options', 'cart_action', 'popup' );
		
		echo ($cart_action == 'page') ? '<a href="'. $cart_link .'" class="header-cart-sequoia">' : '<div class="header-cart-sequoia mini-cart-toggle">';
		?>
				
			<span class="icon-cart-2 mini-cart-icon" aria-hidden="true"></span>
				
			<span class="cart-contents">
				
				<?php echo '<span class="count button round">'.$cart_count.'</span>'; ?>
				
				<?php echo WC()->cart->get_cart_total(); ?>
				
			</span>
				
			<div class="clearfix"></div>
		
		<?php echo ($cart_action == 'page') ? '</a>' : '</div>';
		
		$fragments['.header-cart-sequoia'] = ob_get_clean();
		
		return $fragments;
	}
	
	
	
	/**
	 *	PRODUCTS / PROD.ARCHIVE PAGE IMAGES:
	 *
	 */
	remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
	remove_action( 'woocommerce_after_shop_loop_item_title', 'woocommerce_template_loop_rating', 5 );
	
	add_action( 'woocommerce_before_shop_loop_item_title', 'sequoia_loop_product_thumbnail', 20 );
	//
	if ( ! function_exists( 'sequoia_loop_product_thumbnail' ) ) {

		function sequoia_loop_product_thumbnail( $size = 'shop_catalog', $placeholder_width = 0, $placeholder_height = 0  ) {
			
			global $post, $product, $yith_wcwl;
			
			$products_settings = apply_filters( 'sequoia_options', 'products_settings', array('disable_zoom_button'=> false, 'disable_link_button' => false ) );
			
			// get image format from theme options:
			$of_imgformat = apply_filters( 'sequoia_options', 'shop_image_format', 'as-portrait' );
			if( $of_imgformat == 'as-portrait' ||  $of_imgformat == 'as-landscape' ){
				$img_format = $of_imgformat;
			}else{
				$img_format = 'shop_catalog';
			}
			
			// 3.0.0 < Fallback conditional
			if( apply_filters( 'sequoia_wc_version', '3.0.0' )	) {
				$attachment_ids   = $product->get_gallery_image_ids();
			}else{
				$attachment_ids   = $product->get_gallery_attachment_ids();
			}
			if ( $attachment_ids ) {
				$image_url	= wp_get_attachment_image_src( $attachment_ids[0], 'full' );
				$img_url	= $image_url[0];
				$imgSizes	= all_image_sizes(); // as custom fuction
				$img_width	= $imgSizes[$img_format]['width'];
				$img_height = $imgSizes[$img_format]['height'];
			}
			
			$title = '<a href="' . get_permalink(). '" title="'. esc_attr( $post->post_title ) .'"><h3>'. get_the_title(). '</h3></a>';
			
			echo '<div class="front">';
			
			function_exists('woocommerce_show_product_loop_sale_flash') ? woocommerce_show_product_loop_sale_flash() : '';
				
			echo apply_filters( 'woocommerce_template_loop_product_thumbnail', as_image( $img_format ) );
			
			echo '</div>';
			
			echo '<div class="back">';
			
				echo '<div class="item-overlay"></div>';
				
				function_exists('woocommerce_template_loop_rating') ? woocommerce_template_loop_rating() : '';
			
				//do_action( 'woocommerce_after_shop_loop_item_title' );
			
				if ( $attachment_ids ) {
					
					echo wp_get_attachment_image(  $attachment_ids[0], array($img_width, $img_height) );  // no Freshizer
										
				}else{
					echo as_image( $img_format );
				}
		
				echo '<div class="back-buttons">';
					
					if( !isset($products_settings['disable_zoom_button']) ) {
						echo '<a href="'.as_get_full_img_url().'" class="button tiny item-zoom magnificpopup mfp-image" title="'. esc_attr(get_the_title()) .'"><div class="icon icon-zoom-in" aria-hidden="true"></div></a>';
					}
					if( !isset($products_settings['disable_link_button']) ) {
						echo '<a href="'.get_permalink().'" class="button tiny" title="'. esc_attr(get_the_title()) .'"><div class="icon icon-link" aria-hidden="true"></div></a>';
					}
				
				echo '</div>';
				
				function_exists('woocommerce_template_loop_rating') ? woocommerce_template_loop_rating() : '';
				
			echo '</div>';

		}
	}
	/**
	 *	CHANGE WOOCOMMERCE PLACEHOLDER IMAGE
	 *
	 */
	remove_filter('woocommerce_placeholder_img_src','woocommerce_placeholder_img_src');
	add_filter('woocommerce_placeholder_img_src','sequoia_placeholder_img_src');
	function sequoia_placeholder_img_src () {
		return apply_filters( 'sequoia_options', 'placeholder_image', get_template_directory_uri().'/img/no-image.jpg' );
	}
	/**
	 *	REMOVE WOO TITLE from PRIMARY div to head (like blog single page title)
	 *	
	 */
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	add_action( 'as_single_product_summary', 'woocommerce_template_single_title', 5 );
	//
	/**
	 *	DEQUEUE PRETTYPHOTO FROM WC IN FAVOUR OF THEME'S "MAGNIFIC POPUP" SCRIPT
	 *	
	 */

	function prettyPhoto_dequeue () {
		global $sequoia_woo_is_active;
		// if plugin is active :
		if ( $sequoia_woo_is_active  ) {
			wp_dequeue_style('woocommerce_prettyPhoto_css');
			wp_deregister_style('woocommerce_prettyPhoto_css');
			
			wp_dequeue_script('prettyPhoto');
			wp_dequeue_script('prettyPhoto-init');
		}
	}
	add_action( 'wp_enqueue_scripts','prettyPhoto_dequeue', 1000 );

	/**
	 * Changing order in single product
	 *
	 */
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );


	
	/**
	 *	Quick view images
	 *
	 */
	add_action( 'product_quick_view_images', 'quick_view_images', 25 );
	function quick_view_images() {
		
		global $post, $woocommerce, $product;
		
		// get image format from theme options:
		$of_imgformat = apply_filters( 'sequoia_options', 'single_product_image_format', 'as-portrait' );
		if( $of_imgformat == 'plugin' ) {
			$img_format = 'shop_single';
		}else{
			$img_format = $of_imgformat;
		}
		
		// 3.0.0 < Fallback conditional
		if( apply_filters( 'sequoia_wc_version', '3.0.0' )	) {
			$attachment_ids   = $product->get_gallery_image_ids();
		}else{
			$attachment_ids   = $product->get_gallery_attachment_ids();
		}
		
		echo '<div class="images'. ($attachment_ids ? ' productslides'  : '') .'">';
		
		// MAIN PRODUCT IMAGE - POST THUMBNAIL (FEATURED IMAGE ETC.) 
		if ( has_post_thumbnail() ) {
		
			$post_thumb_id				= get_post_thumbnail_id();
			$default_product_image_src	= wp_get_attachment_image_src( $post_thumb_id, $img_format );
			$default_product_image_url  = $default_product_image_src[0];

			$image_link  		= wp_get_attachment_url( $post_thumb_id );
			$image_class 		= 'attachment-' . $post_thumb_id ;
			$image_title 		= strip_tags( get_the_title( $post_thumb_id ) ) ;
			$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
				'title' => esc_attr( $image_title ),
				'alt'	=> esc_attr( $image_title ),
				'class'	=> esc_attr( $image_class. ' featured' )
				) );
			$full_image			= as_get_full_img_url();
			$product_title		= esc_attr( strip_tags(get_the_title()));
			$product_link		= esc_attr( get_permalink() );

			echo apply_filters( 'woocommerce_single_product_image_html',sprintf('<div class="item-img item"><a href="%2$s" data-o_href="%2$s" data-zoom-image="%4$s" class="larger-image-link woocommerce-main-image zoom %5$s" itemprop="image"  title="%3$s">%1$s</a></div>',
			
				$image,						// %1$s
				$full_image,				// %2$s
				$product_title,				// %3$s
				$default_product_image_url,	// %4$s
				$attachment_ids ? 'mfp-gallery' : 'magnificpopup mfp-image' // %5$s
				

			),  $post->ID );

		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ) );

		}
		
		// PRODUCT GALLERY IMAGES 
		if ( $attachment_ids ) {

			$loop = 0;
			$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'zoom' );

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'first';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
				
					continue;
				$image_title = esc_attr( get_the_title( $attachment_id ) );
				$image       = wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
					'title' => $image_title
					));
				$image_class = esc_attr( implode( ' ', $classes ) );
				

				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="item-img">
	%4$s</div>', 
				$image_link, 
				$image_class, 
				$image_title, 
				$image ), $attachment_id, $post->ID, $image_class );
				
				$loop++;
			}

		}
		echo '</div>';//. images
	}


	
	/**
	 *	SINGLE PRODUCT DISPLAY IMAGES (slider)
	 *	- used in as-single-product-block.php
	 */
	add_action( 'do_single_product_images', 'single_product_images', 25, 1 );
	function single_product_images( $img_format = 'shop_single') {
		
		global $post, $woocommerce, $product;
		
		// get image format from theme options for SINGLE PRODUCT:
		$of_imgformat = apply_filters( 'sequoia_options', 'single_product_image_format', 'as-portrait' );
		
		if( is_product()) { // if on single product page: 
		
			if( $of_imgformat == 'plugin' ) {
				$img_format = 'shop_single';
			}else{
				$img_format = $of_imgformat;
			}
			
		}else{ // if not on single product (single block or quick view): 
			
			$img_format = $img_format;
		}
		
		// 3.0.0 < Fallback conditional
		if( apply_filters( 'sequoia_wc_version', '3.0.0' )	) {
			$attachment_ids   = $product->get_gallery_image_ids();
		}else{
			$attachment_ids   = $product->get_gallery_attachment_ids();
		}
		
		echo '<div class="'. ($attachment_ids ? 'owl-carousel singleslides magnificgallery'  : '') .' images">';
		
		// MAIN PRODUCT IMAGE - post thumbnail (featured image etc.)
		if ( has_post_thumbnail() ) {
		
			$post_thumb_id				= get_post_thumbnail_id();
			$default_product_image_src	= wp_get_attachment_image_src( $post_thumb_id, $img_format );
			$default_product_image_url  = $default_product_image_src[0];

			$image_link  		= wp_get_attachment_url( $post_thumb_id );
			$image_class 		= 'attachment-' . $post_thumb_id ;
			$image_title 		= strip_tags( get_the_title( $post_thumb_id ) ) ;
			$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
				'title' => esc_attr( $image_title ),
				'alt'	=> esc_attr( $image_title ),
				'class'	=> esc_attr( $image_class. ' featured' )
				) );
			$full_image			= as_get_full_img_url();
			$product_title		= esc_attr( strip_tags(get_the_title()));
			$product_link		= esc_attr( get_permalink() );

			echo apply_filters( 'woocommerce_single_product_image_html',sprintf('<div class="item-img item"><div class="front">%1$s</div><div class="back"><div class="item-overlay"></div><div class="back-buttons" itemscope><a href="%2$s" data-o_href="%2$s" data-zoom-image="%4$s" class="larger-image-link button tiny woocommerce-main-image zoom %5$s" itemprop="image"  title="%3$s"><div class="icon icon-zoom-in" aria-hidden="true"></div></a></div></div></div>',
			
				$image,						// %1$s
				$full_image,				// %2$s
				$product_title,				// %3$s
				$default_product_image_url,	// %4$s
				$attachment_ids ? 'mfp-gallery' : 'magnificpopup mfp-image' // %5$s
				

			),  $post->ID );

		} else {

			echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="Placeholder" />', woocommerce_placeholder_img_src() ) );

		}
		
		/**	Product gallery images */
		
		if ( $attachment_ids ) {

			$loop = 0;
			$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 3 );

			foreach ( $attachment_ids as $attachment_id ) {

				$classes = array( 'zoom' );

				if ( $loop == 0 || $loop % $columns == 0 )
					$classes[] = 'first';

				if ( ( $loop + 1 ) % $columns == 0 )
					$classes[] = 'last';

				$image_link = wp_get_attachment_url( $attachment_id );

				if ( ! $image_link )
					continue;
				$image_class	= esc_attr( implode( ' ', $classes ) );
				$image_title	= esc_attr( get_the_title(  ) );
				$image			= wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', $img_format ), array(
					'title' => $image_title
					));
				$attachment_src = wp_get_attachment_image_src( $attachment_id, 'large' );
				
				$full_image		= $attachment_src[0];
				$product_title	= esc_attr(get_the_title());
				$product_link	= esc_attr( get_permalink() );
				
				echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="item-img item"><div class="front">%4$s</div><div class="back"><div class="item-overlay"></div><div class="back-buttons magnificgallery"><a href="%5$s" class="button tiny mfp-gallery" title="%6$s"><div class="icon icon-zoom-in" aria-hidden="true"></div></a> %7$s </div></div></div>', 
					$image_link,	// 1
					$image_class,	// 2
					$image_title,	// 3
					$image,			// 4
					$full_image,	// 5
					$product_title,	// 6
					is_product() ? null : '<a href="'.$product_link .'" class="button tiny" title="%6$s"><div class="icon icon-link" aria-hidden="true"></div></a>'	// 7
					
				), $attachment_id, $post->ID, $image_class );
				
				$loop++;
			}

		}
		
		echo '</div>';//. images
	}
	
	
	
	if ( ! function_exists( 'as_get_product_search_form' ) ) {

		/**
		 * Output Product search forms - AS edit.
		 *
		 * @access public
		 * @param bool $echo (default: true)
		 * @return void
		 */
		function as_get_product_search_form( $echo = true  ) {
		
			do_action( 'as_get_product_search_form'  );

			$search_form_template = locate_template( 'product-searchform.php' );
			if ( '' != $search_form_template  ) {
				require $search_form_template;
				return;
			}
			
			$placeholder = esc_attr__('Search for products', 'sequoia');
			
			$form = '<div class="searchform-menu"><form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">

					<input type="search" value="' . get_search_query() . '" name="s" id="s" placeholder="' . $placeholder . '" />
					<button type="submit" class="icon-search" id="searchsubmit"></button>
					<input type="hidden" name="post_type" value="product" />
				
			</form></div>';

			if ( $echo  )
				echo apply_filters( 'as_get_product_search_form', $form );
			else
				return apply_filters( 'as_get_product_search_form', $form );
		}
	}
	
	
	/**
     * AS YITH AJAX SEARCH
     * 
     * 
     * @return echo
     */
	if ( !function_exists('as_yith_ajax_search') ) {
	
		function as_yith_ajax_search() {
			
			if ( !defined( 'YITH_WCAS' ) ) { return; } 
			wp_enqueue_script('yith_wcas_jquery-autocomplete' );
			
			?>

			<div class="yith-ajaxsearchform-container searchform-menu">
			<form role="search" method="get" id="yith-ajaxsearchform" action="<?php echo esc_url( home_url( '/'  ) ) ?>">
				<div>
					
					<?php 
					$label		= get_option('yith_wcas_search_input_label');
					$placehold	= $label ? $label : esc_attr__('Search for products','sequoia');
					?>
					
					<input type="search"
					   value="<?php echo get_search_query() ?>"
					   name="s"
					   id="yith-s"
					   class="yith-s"
					   placeholder="<?php echo $placehold; ?>"
					   data-loader-icon="<?php echo get_template_directory_uri() . '/img/ajax-loader.gif'; ?>"
					   data-min-chars="<?php echo get_option('yith_wcas_min_chars'); ?>" />
					
					<button type="submit" class="icon-search"></button>
					
					<input type="hidden" name="post_type" value="product" />
					<?php if ( defined( 'ICL_LANGUAGE_CODE' ) ): ?>
						<input type="hidden" name="lang" value="<?php echo( ICL_LANGUAGE_CODE ); ?>" />
					<?php endif ?>
				</div>
			</form>
			</div>
			<script type="text/javascript">

			jQuery(document).ready(function ($) {
				"use strict";

				var el = $('.yith-s'),
					loader_icon = el.data('loader-icon') == '' ? '' : el.data('loader-icon'),
					search_button = $('#yith-searchsubmit'),
					min_chars = el.data('min-chars');

				search_button.on('click', function(){
					var form = $(this).closest('form');
					if( form.find('.yith-s').val()==''){
						return false;
					}
					return true;
				});

				if( el.length == 0 ) el = $('#yith-s');

				el.each(function () {
					var $t = $(this),
						append_to = ( typeof  $t.data('append-to') == 'undefined') ? $t.closest('.yith-ajaxsearchform-container') : $t.data('append-to');

					el.yithautocomplete({
						minChars        : min_chars,
						appendTo        : append_to,
						serviceUrl      : woocommerce_params.ajax_url + '?action=yith_ajax_search_products',
						onSearchStart   : function () {
							$(this).css('background', 'url(' + loader_icon + ') no-repeat right center');
						},
						onSelect        : function (suggestion) {
							if (suggestion.id != -1) {
								window.location.href = suggestion.url;
							}
						}  ,
						onSearchComplete: function () {
							$t.css('background', 'transparent');
						}
					});
				});
			});
			</script>
			<?php  
			} // end function as_yith_ajax_search
		} // end if function_exists as_yith_ajax_search
	/**
	 *	SHOP META BOX handling
	 *
	 *	- removing shop meta box if current page is not registered in WooCommerce as shop base
	 *	always removing "catalog-pre" meta box, EXCEPT if:  current edited page id == shop base page id
	 *	
	 *	admin hooks: load-"ADMIN-PAGE"
	 */
	add_action( 'load-post.php', 'only_shop_page_meta' );
	function only_shop_page_meta() {

		$shop_base_id	= wc_get_page_id('shop');
		
		if( isset($_GET['post']) && $_GET['post'] != $shop_base_id ) {
		
			remove_meta_box( 'catalog-page-meta-box', 'page', 'normal' );
		}
		
	}
	add_action( 'load-post-new.php', 'remove_shop_page_meta' );
	function remove_shop_page_meta() {

		remove_meta_box( 'catalog-page-meta-box', 'page', 'normal' );
	
	}	
	
	/**
	 *	AS WISHLIST
	 *
	 *	extending and modifying YITH WISHLIST plugin ( plugin must be installed and activated )
	 */
	if( class_exists( 'YITH_WCWL_UI' ) ) {
			
		add_action('as_wishlist_button','as_wishlist_button_func', 10); // FOR PB BLOCKS, CATALOG etc.
		
		function as_wishlist_button_func() {

			yith_wcwl_get_template( 'add-to-wishlist.php' );
				
		}
		
		/* end AS WISHLIST */
		
		
		function dequeue_yith_styles() {
			wp_dequeue_style( 'yith-wcwl-font-awesome');
			wp_dequeue_style( 'yith-wcwl-font-awesome-ie7' );
			//wp_dequeue_style( 'yith-wcwl-main' );
		}

		add_action( 'wp_enqueue_scripts', 'dequeue_yith_styles' );
	
	}
	//end YITH WISHLIST related functions
	
	

	/**
	 *	PRODUCT CUSTOM ATTRIBUTES - REGISTER TO MENUS and CREATE THEME FILES
	 *	- register taxonomy pa_"custom prod att." to nav menus
	 *	- create "taxonomy-$custom attribute.php" file
	 */
	
	// get taxonomies and filter out PRODUCT ATTRIBUTES ( PREFIX PA_)
	function fetch_prod_atts() {
		$get_tax_args = array(
			'public'   => true,
			'_builtin' => false
		); 
		$output = 'names'; // or objects
		$operator = 'and'; // 'and' or 'or'
		$taxonomies = get_taxonomies( $get_tax_args, $output, $operator ); 
		$product_attributes = array();
		if ( $taxonomies ) {
			foreach ( $taxonomies  as $taxonomy ) {	
				if( strpos($taxonomy,'pa_')!== false ){
					$product_attributes[] = $taxonomy;
				}
			}
		}
		return $product_attributes;
	}
	
	add_action('admin_init', 'create_atts_files',10);
	function create_atts_files() {
		
		WP_Filesystem();
		global $wp_filesystem;
		
		$product_attributes	= fetch_prod_atts();
		$theme_folder		= get_template_directory();
		$content			= '<?php if ( ! defined( "ABSPATH" ) ) exit; wc_get_template( "archive-product.php" );?>';
		 
		foreach( $product_attributes as $prod_att ) {

			$file	= $theme_folder .  '/taxonomy-' . $prod_att .'.php';
			
			if( !file_exists($file) ) {
			 
				if ( ! $wp_filesystem->put_contents( $file , $content , 0644 ) ) {
					return true;
				}
			
			}
			
		}
		
	}	
 
	add_filter('woocommerce_attribute_show_in_nav_menus', 'wc_reg_for_menus', 1, 2);
	function wc_reg_for_menus( $register, $name = '' ) {
		//if ( $name == 'pa_color' ) 
		$register = true;
		return $register;
	}
	/**
	* Woocommerce custom  Size chart tab
	**/
	require_once get_template_directory() . '/woocommerce/single-product/tabs/custom-size-wootab.php'; // woocommerce custom tabs

	/**
	 *	LIMIT FOR VARIATIONS BEFORE AJAX 
	 */
	function as_wc_ajax_variation_threshold( $qty, $product ) {
		return 70;
	}
	add_filter( 'woocommerce_ajax_variation_threshold', 'as_wc_ajax_variation_threshold', 10, 2 );
	
	/**
	 *	REMOVE FIRST / LAST CLASSES IN PRODUCTS PAGE
	 *
	 *	hook to post_class to remove css classes "first"/"last" which dissrupt theme grid system
	 */
	function as_remove_first_last( $classes ) {
		
		if( is_woocommerce() || is_active_widget( false,false,'woocommerce_products' ) ) {
			$classes = array_diff( $classes, array('first') );	
			$classes = array_diff( $classes, array('last') );
			
		}
		
		return $classes;
		
	}	
	add_filter( 'post_class','as_remove_first_last',100 );


	
} // end if $sequoia_woo_is_active
/**
 *
 *  END OF WOOCOMMECE
 */
//
?>