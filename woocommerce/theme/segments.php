<?php
/**
 * WooCommerce theme partials
 *
 * @since 1.5.0
 * @package WordPress
 * @subpackage Sequoia
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *  TOP CATALOG PAGE PRODUCT WIDGETS
 *
 *  @return html widgets
 *  @details Details
 */
function sequoia_top_catalog_widgets_f() {

	// If there are categories and / or products to be displayed.
	// on Shop (catalog) page; on product categories page.
	$shoppage_display = get_option( 'woocommerce_shop_page_display', 'both' );
	$catarch_display  = get_option( 'woocommerce_category_archive_display', 'both' );

	// If Shop page (catalog).
	if ( is_shop() ) {
		if ( 'subcategories' === $shoppage_display && sequoia_count_product_cats() ){
			return;
		};
	}
	// If product categry page.
	if ( is_tax( 'product_cat' ) ) {
		// If display is set to sub categories and there are sub categories - abort.
		if ( 'subcategories' === $catarch_display && sequoia_count_product_cats() ) {
			return;
		}
	}

	// If added widgets to "Product filters" sidebar area.
	if ( is_active_sidebar( 'product-filters-widgets' ) ) {

		echo '<div class="row"><div class="product-filters-wrap">';
		echo '<div class="product-filters">';
		echo '<span class="icon icon-close"></span>';
			dynamic_sidebar( 'product-filters-widgets' ); 
			dynamic_sidebar( 'layered-nav-filter-widgets' ); 
		echo '<div class="clearfix"></div></div>';
		echo '<h4 class="product-filters-title">' . esc_html__( 'Product filters', 'sequoia' ) . '</h4>';
		echo '</div></div>'; // product-filters-wrap.
		echo '<div class="product-filters-clearer"></div>';

	}
}
add_action( 'sequoia_top_catalog_widgets' ,'sequoia_top_catalog_widgets_f' );

/**
 * COUNT PRODUCT CATEGORIES
 *
 * @return void
 */
if ( ! function_exists( 'sequoia_count_product_cats' ) ) {
	/**
	 * IF THERE ARE PRODUCT CATEGORIES
	 *
	 * @return boolean $cats_exists
	 */
	function sequoia_count_product_cats() {
		$cats_exists        = false;
		$term               = get_queried_object();
		$parent_id          = empty( $term->term_id ) ? 0 : $term->term_id;
		$product_categories = get_categories( array(
				'parent'       => $parent_id,
				'menu_order'   => 'ASC',
				'hide_empty'   => 0,
				'hierarchical' => 1,
				'taxonomy'     => 'product_cat',
				'pad_counts'   => 1,
		) );
		if ( count( $product_categories ) ) {
			$cats_exists = true;
		}
		return $cats_exists;
	}
}
