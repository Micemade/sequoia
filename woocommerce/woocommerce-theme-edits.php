<?php
//
/** 
 * WOOCOMMERCE check plugin existence
 *
 */
$sequoia_woo_is_active = false;
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	add_filter( 'sequoia_wc_active', '__return_true', 10, 1 );

	// Variables for fallback for some (eventually) missed  global vars replacements.
	$sequoia_woo_is_active = apply_filters( 'sequoia_wc_active', '' );
	$woo_is_active         = apply_filters( 'sequoia_wc_active', '' ); // fallback for older versions and child theme overrides

	if ( defined( 'WOOCOMMERCE_VERSION' ) ) {
		$sequoia_wc_version = WOOCOMMERCE_VERSION;
	}

	$run_once = new run_once;
	if ( $run_once->run( 'init_woo_theme_values' ) ) {
		sequoia_init_woo_options();
	}

	// THEME WOOCOMMERE CUSTOMIZATIONS.
	require get_theme_file_path( 'woocommerce/theme/index.php' );

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
add_filter( 'sequoia_wc_version', 'sequoia_wc_version_f', 10, 1 );

// YITH WISHLIST check plugin existence.
$sequoia_wishlist_is_active = false;
if ( in_array( 'yith-woocommerce-wishlist/init.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) || class_exists( 'YITH_WCWL' ) ) {

	$sequoia_wishlist_is_active = true; 

}
/**
 * SET INITIAL WC OPTIONS
 *
 * @return void
 */
function sequoia_init_woo_options() {
	//
	$shop_catalog_image_size = array(
		'width' => 300,
		'height' => 180,
		'crop' => 1,
	);
	$shop_single_image_size = array(
		'width' => 300,
		'height' => 300,
		'crop' => 1,
	);
	$shop_thumbnail_image_size = array(
		'width' => 80,
		'height' => 80,
		'crop' => 1,
	);
	update_option( 'shop_catalog_image_size', $shop_catalog_image_size );
	update_option( 'shop_single_image_size', $shop_single_image_size );
	update_option( 'shop_thumbnail_image_size', $shop_thumbnail_image_size );
	// IMPORTANT - theme's WOO template CSS instead of plugin's.
	update_option( 'woocommerce_frontend_css', 'no' );
	// remove "Logout" menu item.
	update_option( 'woocommerce_menu_logout_link', 'no' );
	update_option( 'woocommerce_prepend_shop_page_to_urls', 'yes' );
	update_option( 'woocommerce_prepend_shop_page_to_products', 'yes' );
	update_option( 'woocommerce_prepend_category_to_products', 'yes' );
};

if ( apply_filters( 'sequoia_wc_active', '' ) ) {

	// Support for WooCommerce gallery zoom, lightbox and slider.
	add_theme_support( 'wc-product-gallery-zoom' );
	add_theme_support( 'wc-product-gallery-lightbox' );
	add_theme_support( 'wc-product-gallery-slider' );
	/**
	 * SEQUOIA INIT WOOCOMMERCE
	 *
	 * @return void
	 */
	function sequoia_wc_init () {

		global $sequoia_wc_version;
		add_filter( 'woocommerce_enqueue_styles', '__return_false' );

	}
	add_action( 'init', 'sequoia_wc_init' );

	/**
	 * AJAX UPDATER OF CART
	 *
	 * @param [type] $fragments
	 * @return void
	 */
	function woocommerce_header_add_to_cart_fragment( $fragments ) {

		ob_start();
		$cart_count = WC()->cart->cart_contents_count;
		$cart_link = get_permalink( wc_get_page_id( 'cart' ));
		$cart_action = apply_filters( 'sequoia_options', 'cart_action', 'popup' );

		echo ( 'page' === $cart_action ) ? '<a href="' . esc_url( $cart_link ) . '" class="header-cart-sequoia">' : '<div class="header-cart-sequoia mini-cart-toggle">';
		?>

			<span class="icon-cart-2 mini-cart-icon" aria-hidden="true"></span>

			<span class="cart-contents">

				<?php echo '<span class="count button round">' . esc_html( $cart_count ) . '</span>'; ?>

				<?php echo wp_kses_post( WC()->cart->get_cart_total() ); ?>

			</span>

			<div class="clearfix"></div>

		<?php echo ( 'page' === $cart_action ) ? '</a>' : '</div>';

		$fragments['.header-cart-sequoia'] = ob_get_clean();

		return $fragments;
	}
	add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

	// REMOVE WOO TITLE from PRIMARY div to head (like blog single page title)
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
	add_action( 'as_single_product_summary', 'woocommerce_template_single_title', 5 );

	/**
	 * Changing order in single product
	 *
	 */
	remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
	add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 25 );

	if ( ! function_exists( 'as_get_product_search_form' ) ) {

		/**
		 * Output Product search forms - AS edit.
		 *
		 * @access public
		 * @param bool $echo (default: true)
		 * @return void
		 */
		function as_get_product_search_form( $echo = true  ) {

			do_action( 'as_get_product_search_form' );

			$search_form_template = locate_template( 'product-searchform.php' );
			if ( '' !== $search_form_template  ) {
				require $search_form_template;
				return;
			}

			$placeholder = esc_attr__( 'Search for products', 'sequoia' );

			$form = '<div class="searchform-menu"><form role="search" method="get" id="searchform" action="' . esc_url( home_url( '/'  ) ) . '">

					<input type="search" value="' . get_search_query() . '" name="s" id="s" placeholder="' . esc_attr( $placeholder ) . '" />
					<button type="submit" class="icon-search" id="searchsubmit"></button>
					<input type="hidden" name="post_type" value="product" />
				
			</form></div>';

			if ( $echo ) {
				echo apply_filters( 'as_get_product_search_form', $form );
			} else {
				return apply_filters( 'as_get_product_search_form', $form );
			}

		}
	}

	/**
	 * SHOP META BOX handling
	 *
	 * @return void
	 */
	function only_shop_page_meta() {
		$shop_base_id = wc_get_page_id('shop');
		if( isset( $_GET['post']) && $_GET['post'] !== $shop_base_id ) {
			remove_meta_box( 'catalog-page-meta-box', 'page', 'normal' );
		}
	}
	add_action( 'load-post.php', 'only_shop_page_meta' );

	function remove_shop_page_meta() {
		remove_meta_box( 'catalog-page-meta-box', 'page', 'normal' );
	}
	add_action( 'load-post-new.php', 'remove_shop_page_meta' );

	/**
	 *	PRODUCT CUSTOM ATTRIBUTES - REGISTER TO MENUS and CREATE THEME FILES
	 *	- register taxonomy pa_"custom prod att." to nav menus
	 *	- create "taxonomy-$custom attribute.php" file
	 */
	/**
	 * GET TAXONOMIES AND FILTER OUT PRODUCT ATTRIBUTES ( PREFIX PA_)
	 *
	 * @return array $product_attributes
	 */
	function fetch_prod_atts() {
		$get_tax_args = array(
			'public'   => true,
			'_builtin' => false,
		); 
		$output             = 'names'; // or objects
		$operator           = 'and'; // 'and' or 'or'
		$taxonomies         = get_taxonomies( $get_tax_args, $output, $operator ); 
		$product_attributes = array();
		if ( $taxonomies ) {
			foreach ( $taxonomies  as $taxonomy ) {
				if( strpos( $taxonomy,'pa_' ) !== false ){
					$product_attributes[] = $taxonomy;
				}
			}
		}
		return $product_attributes;
	}
	add_action( 'admin_init', 'create_atts_files', 10 );

	function create_atts_files() {

		WP_Filesystem();
		global $wp_filesystem;

		$product_attributes = fetch_prod_atts();
		$theme_folder       = get_template_directory();
		$content            = '<?php if ( ! defined( "ABSPATH" ) ) exit; wc_get_template( "archive-product.php" );?>';

		foreach ( $product_attributes as $prod_att ) {
			$file	= $theme_folder . '/taxonomy-' . $prod_att .'.php';
			if( ! file_exists( $file ) ) {
				if ( ! $wp_filesystem->put_contents( $file , $content , 0644 ) ) {
					return true;
				}
			}

		}

	}
	/**
	 * REGISTER TAXONOMIES TO WP NAV MENU
	 *
	 * @param [type] $register
	 * @param string $name
	 * @return void
	 */
	function wc_reg_for_menus( $register, $name = '' ) {
		$register = true;
		return $register;
	}
	add_filter( 'woocommerce_attribute_show_in_nav_menus', 'wc_reg_for_menus', 1, 2 );


	/**
	 * REMOVE FIRST / LAST CLASSES IN PRODUCTS PAGE
	 *
	 * remove css classes "first"/"last"
	 */
	function as_remove_first_last( $classes ) {

		if ( is_woocommerce() || is_active_widget( false,false,'woocommerce_products' ) ) {
			$classes = array_diff( $classes, array('first') );	
			$classes = array_diff( $classes, array('last') );
		}

		return $classes;
	}
	add_filter( 'post_class','as_remove_first_last', 100 );


// end if $sequoia_woo_is_active.
}
